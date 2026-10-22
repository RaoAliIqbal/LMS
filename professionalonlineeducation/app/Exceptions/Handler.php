<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use App\Support\StudentEnrollmentLoader;
use App\Models\PaymentLogs;
use App\Services\PaymentService;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->is('/') || $request->is('home')) {
            if (! ($exception instanceof \Illuminate\Validation\ValidationException)
                && ! ($exception instanceof AuthenticationException)) {
                try {
                    return response()->view('web.index', [
                        'courses' => collect(),
                        'paceStatePills' => [],
                    ], 200);
                } catch (\Throwable $e) {
                    \Log::error('Homepage fallback view failed', ['message' => $e->getMessage()]);
                }
            }
        }

        if ($exception instanceof HttpException && $exception->getStatusCode() === 503) {
            return response()->view('errors.maintenance', [], 503);
        }

        $user = $request->user();

        // Staff logged into student URLs — send to admin dashboard (not fake "courses" error).
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('Admin')
            && ($request->is('user', 'user/*') || $request->routeIs('client_*', 'myCourse*'))) {
            if ($exception instanceof HttpException && $exception->getStatusCode() === 403) {
                return redirect()->route('admin_dashboard');
            }
            if ($exception instanceof AuthorizationException) {
                return redirect()->route('admin_dashboard');
            }
        }

        if ($exception instanceof NotFoundHttpException || ($exception instanceof HttpException && $exception->getStatusCode() === 404)) {
            if ($request->is('user/*') || $request->is('user')) {
                return $this->renderStudentFallback($request, $user);
            }
        }

        if ($exception instanceof HttpException && $exception->getStatusCode() >= 500) {
            \Log::error('HTTP '.$exception->getStatusCode().' on '.$request->path(), [
                'message' => $exception->getMessage(),
                'user_id' => optional($user)->id,
            ]);
            if ($request->is('user/*') || $request->is('user')) {
                return $this->renderStudentFallback($request, $user);
            }
            if ($request->is('register', 'login') || $request->routeIs('register', 'login')) {
                return redirect()->back()
                    ->withInput($request->except('password', 'password_confirmation'))
                    ->with('error', 'Something went wrong. Please try again or call 1-888-900-3382.');
            }
            if ($this->isPublicWebsiteRoute($request)) {
                return $this->renderPublicFallback($request, $exception);
            }
        }

        if ($exception instanceof TokenMismatchException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your session expired. Please refresh the page and try again.',
                ], 419);
            }

            $redirect = redirect()->back()
                ->withInput($request->except('password', 'password_confirmation', '_token'))
                ->with('error', 'Your session expired. Please try again.');

            if ($request->is('login', 'register') || $request->routeIs('login', 'register')) {
                $target = $request->is('register') || $request->routeIs('register') ? 'register' : 'login';
                return redirect()->route($target)
                    ->withInput($request->except('password', 'password_confirmation', '_token'))
                    ->with('error', 'Your session expired. Please try again.');
            }

            return $redirect;
        }

        if ($exception instanceof QueryException && $request->is('chatbot/*')) {
            \Log::error('chatbot_query_exception', [
                'path' => $request->path(),
                'message' => $exception->getMessage(),
            ]);

            if ($request->routeIs('chatbot.escalate')) {
                return response()->json([
                    'ok' => false,
                    'message' => 'We could not save your request right now. Please call 1-888-900-3382 or try again in a few minutes.',
                ], 503);
            }

            if ($request->routeIs('chatbot.followup', 'chatbot.followup.confirm')) {
                return response()->view('web.chatbotFollowUpInvalid', [], 200);
            }
        }

        if (($request->is('register', 'login') || $request->routeIs('register', 'login'))
            && ! $exception instanceof \Illuminate\Validation\ValidationException
            && ! $exception instanceof TokenMismatchException) {
            \Log::error('Unhandled exception on '.$request->path(), [
                'message' => $exception->getMessage(),
                'exception' => get_class($exception),
                'user_id' => optional($request->user())->id,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Something went wrong. Please try again or call 1-888-900-3382.',
                ], 500);
            }

            $redirect = $request->isMethod('post')
                ? redirect()->back()->withInput($request->except('password', 'password_confirmation', '_token'))
                : redirect()->route($request->is('login*') ? 'login' : 'register');

            return $redirect->with('error', 'Something went wrong. Please try again or call 1-888-900-3382.');
        }

        if (($request->is('user', 'user/*') || $request->routeIs('client_*', 'myCourse*', 'chapter*', 'addTime', 'saveUserExercise'))
            && ! $exception instanceof \Illuminate\Validation\ValidationException
            && ! $exception instanceof TokenMismatchException
            && ! $exception instanceof AuthenticationException
            && ! $exception instanceof AuthorizationException) {
            \Log::error('Unhandled exception on student route '.$request->path(), [
                'message' => $exception->getMessage(),
                'exception' => get_class($exception),
                'user_id' => optional($request->user())->id,
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Something went wrong. Your progress may not have saved. Please refresh and try again.',
                ], 500);
            }

            if ($user && method_exists($user, 'hasRole') && $user->hasRole('Admin')) {
                return redirect()->route('admin_dashboard');
            }

            return $this->renderStudentFallback($request, $user);
        }

        if (! config('app.debug')
            && ! $request->expectsJson()
            && ! $request->is('admin/*')
            && ! $exception instanceof \Illuminate\Validation\ValidationException
            && ! $exception instanceof AuthenticationException) {
            \Log::error('Unhandled web exception on '.$request->path(), [
                'message' => $exception->getMessage(),
                'exception' => get_class($exception),
            ]);

            if ($request->is('register', 'login') || $request->routeIs('register', 'login')) {
                return redirect()->route($request->is('login*') || $request->routeIs('login') ? 'login' : 'register')
                    ->with('error', 'Something went wrong. Please try again or call 1-888-900-3382.');
            }

            // Password reset: stay on the form and show the error instead of bouncing to the homepage.
            if ($request->is('password/*')) {
                return ($request->isMethod('post') ? redirect()->back() : redirect()->route('password.request'))
                    ->withInput($request->only('email'))
                    ->with('error', 'We could not process your password reset right now. Please try again or call 1-888-900-3382.');
            }

            if ($user && method_exists($user, 'hasRole') && $user->hasRole('Admin')) {
                return redirect()->route('admin_dashboard');
            }

            if ($this->isPublicWebsiteRoute($request)) {
                return $this->renderPublicFallback($request, $exception);
            }

            if ($user && ($request->is('user/*') || $request->is('user'))) {
                return $this->renderStudentFallback($request, $user);
            }

            return redirect()->route('home');
        }

        return parent::render($request, $exception);
    }

    protected function isPublicWebsiteRoute($request): bool
    {
        return $request->is(
            '/',
            'home',
            'about',
            'courses',
            'course/*',
            'events',
            'event',
            'event/*',
            'become-arizona-chiropractic-assistant', 
            'states',
            'state/*',
            'privacy-policy',
            'terms-and-conditions',
            'refund-policy',
            'cookies-policy',
            'investor-education',
            'webinars',
            'tools',
            'chatbot/*'
        );
    }

    protected function renderMyCoursesPage($user = null)
    {
        $courses = collect();
        if ($user) {
            try {
                $courses = StudentEnrollmentLoader::ensureEnrollments($user);
            } catch (\Throwable $e) {
                \Log::error('renderMyCoursesPage enrollment load failed', [
                    'user_id' => optional($user)->id,
                    'message' => $e->getMessage(),
                ]);
            }
            if ($courses->isEmpty()) {
                try {
                    $controller = app(\App\Http\Controllers\EducationController::class);
                    $courses = $controller->loadMyCoursesDirect((int) $user->id, (string) $user->email);
                } catch (\Throwable $e) {
                    \Log::error('renderMyCoursesPage direct load failed', ['message' => $e->getMessage()]);
                }
            }
        }

        return response()->view('client.courses', ['courses' => $courses], 200);
    }

    protected function renderLiveEventsPage()
    {
        return response()->view('client.seminars', ['seminars' => collect()], 200);
    }

    protected function renderStudentFallback($request, $user = null)
    {
        if ($request->routeIs('client_Courses') || $request->is('user/my-courses')) {
            return $this->renderMyCoursesPage($user);
        }

        if ($request->routeIs('client_events') || $request->is('user/live-events')) {
            return $this->renderLiveEventsPage();
        }

        if ($request->routeIs('client_home', 'client_profile') || $request->is('user/dashboard', 'user/dashboard/*')) {
            $paymentLogs = [];
            if ($user) {
                try {
                    $paymentLogs = array_slice(
                        (new PaymentService())->addCourses(
                            PaymentLogs::where('user_id', $user->id)->orderByDesc('id')->get()
                        ),
                        0,
                        5
                    );
                } catch (\Throwable $e) {
                    \Log::error('Handler dashboard payment history load failed', [
                        'user_id' => $user->id,
                        'message' => $e->getMessage(),
                    ]);
                }
            }

            return response()->view('client.profileBasic', [
                'users' => $user,
                'states' => \App\Models\State::all(),
                'courses' => collect(),
                'professions' => collect(),
                'userLinceses' => collect(),
                'paymentLogs' => $paymentLogs,
            ], 200);
        }

        return redirect()->route('client_home');
    }

    protected function renderPublicFallback($request, Throwable $exception)
    {
        if ($request->is('/', 'home')) {
            return response()->view('web.index', [
                'courses' => collect(),
                'paceStatePills' => [],
            ], 200);
        }

        return parent::render($request, $exception);
    }
}
