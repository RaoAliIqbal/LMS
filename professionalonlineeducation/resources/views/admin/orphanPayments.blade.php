@extends('admin.layouts.app')

@section('title')
    Paid — Missing Courses
@endsection
@section('content')
    <div class="content-main" id="content-main">
        <div class="padding">
            @include('layouts.partials.alertBox')
            <div class="box">
                <div class="box-header">
                    <h2>Payments Without Course Enrollment</h2>
                    <form method="POST" action="{{ route('admin_reconcile_orphans') }}" style="display:inline-block; margin-left: 15px;">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Fix all missing enrollments from payment records?')">
                            Fix All Automatically
                        </button>
                    </form>
                </div>
                <div class="box-body">
                    @if(empty($orphans))
                        <p class="text-success">No orphan payments found — all paid courses appear enrolled.</p>
                    @else
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Payment</th>
                                <th>Student</th>
                                <th>Amount</th>
                                <th>Missing Course(s)</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orphans as $row)
                                @php $log = $row['log']; $user = $row['user']; @endphp
                                <tr>
                                    <td>#{{ $log->id }}<br><small>{{ $log->created_at }}</small></td>
                                    <td>
                                        @if($user)
                                            <a href="{{ route('admin_clientCourses', $user->id) }}">{{ $user->first_name }} {{ $user->last_name }}</a><br>
                                            <small>{{ $user->email }}</small>
                                        @else
                                            <span class="text-danger">User #{{ $log->user_id }} missing</span>
                                        @endif
                                    </td>
                                    <td>${{ $log->amount }}</td>
                                    <td>
                                        @foreach($row['missing'] as $course)
                                            @if($course)
                                                {{ $course->title }}<br>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($user)
                                            @foreach($row['missing'] as $course)
                                                @if($course)
                                                    <form method="POST" action="{{ route('admin_enroll_from_payment', [$user->id, $log->id]) }}" style="margin-bottom:4px;">
                                                        @csrf
                                                        <input type="hidden" name="lesson_id" value="{{ $course->id }}">
                                                        <button type="submit" class="btn btn-primary btn-xs">Add {{ $course->title }}</button>
                                                    </form>
                                                @endif
                                            @endforeach
                                        @else
                                            <a href="{{ route('admin_create_client') }}" class="btn btn-xs btn-default">Create student</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
