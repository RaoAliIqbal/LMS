@extends('admin.layouts.app')

@section('title')
    Student Activity Log — {{ $client->FullName }}
@endsection

@section('content')
<div class="content-main" id="content-main">
    <div class="padding">

        {{-- Page Header --}}
        <div class="row mb-3">
            <div class="col-md-8">
                <h4 class="mb-0">
                    <i class="fa fa-bar-chart text-primary"></i>
                    Student Activity Log
                </h4>
                <p class="text-muted mb-0">
                    {{ $client->FullName }} &mdash; {{ $client->email }}
                    &nbsp;|&nbsp; Student ID: #{{ $client->id * 500 }}
                </p>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('admin_clientCourses', $client->id) }}" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i> Back to Courses
                </a>
                <a href="{{ route('admin_client', $client->id) }}" class="btn btn-default btn-sm">
                    <i class="fa fa-user"></i> Profile
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(count($activityLog) === 0)
            <div class="alert alert-info">No course activity found for this student.</div>
        @endif

        @foreach($activityLog as $entry)
        @php
            $enrollment  = $entry['enrollment'];
            $course      = $entry['course'];
            $chapters    = $entry['chapters'];
            $quizzes     = $entry['quizzes'];
            $evaluation  = $entry['evaluation'];
            $totalMins   = $entry['totalMinutes'];
            $avgScore    = $entry['avgScore'];
            $year        = $entry['year'];
            $isLatest    = $entry['isLatest'];
            $isCompleted = !is_null($enrollment->completed_at);
            $hoursNeeded = $course->hours ?? 0;
            $hoursSpent  = round($totalMins / 60, 2);
            $progress    = ($hoursNeeded > 0) ? min(100, round(($hoursSpent / $hoursNeeded) * 100)) : 0;
        @endphp

        <div class="box mb-4" style="border-left: 4px solid {{ $isLatest ? '#4a89dc' : '#aaa' }};">
            {{-- Course Header --}}
            <div class="box-header d-flex justify-content-between align-items-center" style="background:{{ $isLatest ? '#f0f6ff' : '#f9f9f9' }}">
                <div>
                    <strong>{{ $course->title }}</strong>
                    <span class="badge badge-{{ $isLatest ? 'primary' : 'secondary' }} ml-2">
                        {{ $year }} {{ $isLatest ? '(Current)' : '(Previous)' }}
                    </span>
                    @if($isCompleted)
                        <span class="badge badge-success ml-1"><i class="fa fa-check"></i> Completed</span>
                    @else
                        <span class="badge badge-warning ml-1"><i class="fa fa-clock-o"></i> In Progress</span>
                    @endif
                </div>
                <div class="text-right text-muted small">
                    Required: {{ $hoursNeeded }} hr(s) &nbsp;|&nbsp; Spent: {{ $hoursSpent }} hr(s)
                </div>
            </div>

            <div class="box-body">

                {{-- ── Enrollment Summary ── --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="text-muted small text-uppercase">Enrolled</div>
                        <strong>{{ \Carbon\Carbon::parse($enrollment->enrolled_at)->format('M d, Y') }}</strong>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small text-uppercase">Completed</div>
                        <strong>{{ $isCompleted ? \Carbon\Carbon::parse($enrollment->completed_at)->format('M d, Y') : '—' }}</strong>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small text-uppercase">Avg Quiz Score</div>
                        <strong>{{ $avgScore !== null ? $avgScore . '%' : '—' }}</strong>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small text-uppercase">Evaluation</div>
                        <strong>
                            @if($evaluation)
                                <span class="text-success"><i class="fa fa-check-circle"></i>
                                    Submitted {{ \Carbon\Carbon::parse($evaluation->created_at)->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-muted">Not submitted</span>
                            @endif
                        </strong>
                    </div>
                </div>

                {{-- ── Time Progress Bar ── --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Time Progress</span>
                        <span>{{ $totalMins }} mins / {{ $hoursNeeded * 60 }} mins required</span>
                    </div>
                    <div class="progress" style="height:10px;">
                        <div class="progress-bar {{ $progress >= 100 ? 'bg-success' : 'bg-primary' }}"
                             role="progressbar" style="width:{{ $progress }}%"></div>
                    </div>
                </div>

                {{-- ── Chapter Activity Table ── --}}
                @if(count($chapters))
                <h6 class="mt-3 mb-2 text-uppercase text-muted" style="font-size:11px; letter-spacing:1px;">
                    <i class="fa fa-list"></i> Chapter Activity
                </h6>
                <div class="table-responsive mb-3">
                    <table class="table table-sm table-bordered mb-0" style="font-size:13px;">
                        <thead class="thead-light">
                            <tr>
                                <th>Chapter</th>
                                <th class="text-center">Time Spent</th>
                                <th class="text-center">Quiz Score</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Last Activity</th>
                                <th class="text-center" style="min-width:220px;">Edit Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chapters as $ch)
                            @php
                                $mins = (float)$ch->time_spent;
                                $chComplete = $mins >= 55;
                            @endphp
                            <tr>
                                <td>
                                    {{ optional($ch->chapter)->title ?? 'Chapter #'.$ch->chapter_id }}
                                </td>
                                <td class="text-center">
                                    {{ $mins }} min{{ $mins != 1 ? 's' : '' }}
                                </td>
                                <td class="text-center">
                                    {{ $ch->quiz_percentage !== null ? $ch->quiz_percentage . '%' : '—' }}
                                </td>
                                <td class="text-center">
                                    @if($chComplete)
                                        <span class="badge badge-success">Complete</span>
                                    @else
                                        <span class="badge badge-warning">Incomplete</span>
                                    @endif
                                </td>
                                <td class="text-center text-muted small">
                                    {{ \Carbon\Carbon::parse($ch->updated_at)->format('M d, Y g:i A') }}
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('admin_update_chapter_time', $client->id) }}" class="form-inline justify-content-center">
                                        @csrf
                                        <input type="hidden" name="lesson_id" value="{{ $course->id }}">
                                        <input type="hidden" name="chapter_id" value="{{ $ch->chapter_id }}">
                                        <input type="hidden" name="year" value="{{ $year }}">
                                        <input type="number" name="time_spent" value="{{ $mins }}" min="0" max="999" step="0.1"
                                               class="form-control form-control-sm mr-1" style="width:72px;" title="Minutes">
                                        <label class="mr-1 mb-0 small" title="Also mark chapter quiz as passed (75%)">
                                            <input type="checkbox" name="mark_quiz_passed" value="1"> Quiz
                                        </label>
                                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                {{-- ── Quiz Attempt History ── --}}
                @if(count($quizzes))
                <h6 class="mt-3 mb-2 text-uppercase text-muted" style="font-size:11px; letter-spacing:1px;">
                    <i class="fa fa-pencil-square-o"></i> Quiz Attempt History
                </h6>
                <div class="table-responsive mb-3">
                    <table class="table table-sm table-bordered mb-0" style="font-size:13px;">
                        <thead class="thead-light">
                            <tr>
                                <th>Quiz</th>
                                <th class="text-center">Score</th>
                                <th class="text-center">Result</th>
                                <th class="text-center">Date &amp; Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quizzes as $q)
                            @php $passed = (float)$q->user_percentage >= 75; @endphp
                            <tr>
                                <td>{{ optional($q->quiz)->title ?? 'Quiz #'.$q->quiz_id }}</td>
                                <td class="text-center">{{ $q->user_percentage }}%</td>
                                <td class="text-center">
                                    @if($passed)
                                        <span class="badge badge-success">Pass</span>
                                    @else
                                        <span class="badge badge-danger">Fail</span>
                                    @endif
                                </td>
                                <td class="text-center text-muted small">
                                    {{ \Carbon\Carbon::parse($q->created_at)->format('M d, Y g:i A') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                {{-- ── Compliance Audit Summary ── --}}
                <h6 class="mt-3 mb-2 text-uppercase text-muted" style="font-size:11px; letter-spacing:1px;">
                    <i class="fa fa-shield"></i> Compliance Audit Trail
                </h6>
                <ul class="list-group list-group-flush" style="font-size:13px;">
                    <li class="list-group-item d-flex justify-content-between px-0 py-1">
                        <span><i class="fa fa-sign-in text-primary"></i> &nbsp;Enrolled</span>
                        <span>{{ \Carbon\Carbon::parse($enrollment->enrolled_at)->format('M d, Y g:i A') }}</span>
                    </li>

                    @foreach($chapters as $ch)
                    <li class="list-group-item d-flex justify-content-between px-0 py-1">
                        <span>
                            <i class="fa fa-book text-info"></i>
                            &nbsp;Chapter accessed: {{ optional($ch->chapter)->title ?? '#'.$ch->chapter_id }}
                        </span>
                        <span class="text-muted small">{{ (float)$ch->time_spent }} mins &mdash; {{ \Carbon\Carbon::parse($ch->updated_at)->format('M d, Y g:i A') }}</span>
                    </li>
                    @endforeach

                    @foreach($quizzes as $q)
                    <li class="list-group-item d-flex justify-content-between px-0 py-1">
                        <span>
                            <i class="fa fa-pencil {{ (float)$q->user_percentage >= 75 ? 'text-success' : 'text-danger' }}"></i>
                            &nbsp;Quiz submitted: {{ optional($q->quiz)->title ?? 'Quiz #'.$q->quiz_id }}
                        </span>
                        <span class="text-muted small">{{ $q->user_percentage }}% &mdash; {{ \Carbon\Carbon::parse($q->created_at)->format('M d, Y g:i A') }}</span>
                    </li>
                    @endforeach

                    @if($evaluation)
                    <li class="list-group-item d-flex justify-content-between px-0 py-1">
                        <span><i class="fa fa-file-text-o text-success"></i> &nbsp;Evaluation submitted</span>
                        <span class="text-muted small">{{ \Carbon\Carbon::parse($evaluation->created_at)->format('M d, Y g:i A') }}</span>
                    </li>
                    @endif

                    @if($isCompleted)
                    <li class="list-group-item d-flex justify-content-between px-0 py-1">
                        <span><i class="fa fa-trophy text-warning"></i> &nbsp;Course completed</span>
                        <span class="text-muted small">{{ \Carbon\Carbon::parse($enrollment->completed_at)->format('M d, Y g:i A') }}</span>
                    </li>
                    @endif
                </ul>

            </div>{{-- end box-body --}}
        </div>{{-- end box --}}
        @endforeach

    </div>
</div>
@endsection
