<?php

namespace App\Http\Controllers\Admin;

use App\Models\Seminar;
use App\Models\SeminarUser;
use App\Support\AdminDbLoader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
/**
 * Class SeminarController
 * @package App\Http\Controllers
 */
class SeminarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payload = AdminDbLoader::modelList(Seminar::class, 20);
        $seminars = $payload['items'];
        $view = view('seminar.index', compact('seminars'))
            ->with('i', (request()->input('page', 1) - 1) * $seminars->perPage());

        if ($payload['error']) {
            return $view->with('error', $payload['error']);
        }

        return $view;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $seminar = new Seminar();
        return view('seminar.create', compact('seminar'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Seminar::$rules);

        $seminar = Seminar::create($request->all());

        return redirect()->route('seminars.index')
            ->with('success', 'Seminar created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $seminar = Seminar::with('enrolledUsers')->find($id);
        return view('seminar.show', compact('seminar'));
    }

    public function markAttended($seminarId, $seminarUserId)
    {
        SeminarUser::where('id', $seminarUserId)
                   ->where('seminar_id', $seminarId)
                   ->update(['completed_at' => now()]);

        return redirect()->route('seminars.show', $seminarId)
                         ->with('success', 'Student marked as attended.');
    }

    public function unmarkAttended($seminarId, $seminarUserId)
    {
        SeminarUser::where('id', $seminarUserId)
                   ->where('seminar_id', $seminarId)
                   ->update(['completed_at' => null]);

        return redirect()->route('seminars.show', $seminarId)
                         ->with('success', 'Attendance removed.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $seminar = Seminar::find($id);

        return view('seminar.edit', compact('seminar'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Seminar $seminar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seminar $seminar)
    {
        request()->validate(Seminar::$rules);

        $seminar->update($request->all());

        return redirect()->route('seminars.index')
            ->with('success', 'Seminar updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $seminar = Seminar::find($id)->delete();

        return redirect()->route('seminars.index')
            ->with('success', 'Seminar deleted successfully');
    }
}
