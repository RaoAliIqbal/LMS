@extends('admin.layouts.app')

@section('title')
    Customers Payments
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <!-- ############ Main START-->
        <div class="padding">
            <div class="row">
                <div class="col-md-12">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success"><p>{{ $message }}</p></div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger"><p>{{ $message }}</p></div>
                    @endif

                        <div class="box">
                            <div class="box-header">
                                <h2>Customer's Payment Logs — {{ $client->email }}</h2>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>
                                            <th>Invoice #</th>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Amount</th>
                                            <th>Enrollment</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @if(count($paymentLogs))
                                            @foreach($paymentLogs as $paymentLog)
                                                <tr>
                                                    <td>{{ $paymentLog['log']->transaction_id ?: '—' }}</td>
                                                    <td>{{ $paymentLog['log']->created_at }}</td>
                                                    <td>
                                                        @foreach($paymentLog['courses'] as $course)
                                                            {{ $course->title }} <br/>
                                                        @endforeach
                                                        @foreach($paymentLog['seminars'] as $seminar)
                                                            {{ $seminar->paymentHistoryDescription() }} <br/>
                                                        @endforeach
                                                    </td>
                                                    <td>${{ $paymentLog['log']->amount }}</td>
                                                    <td>
                                                        @foreach($paymentLog['courses'] as $course)
                                                            @if(in_array($course->id, $enrolledLessonIds ?? []))
                                                                <span class="label label-success">Enrolled</span><br>
                                                            @else
                                                                <form method="POST" action="{{ route('admin_enroll_from_payment', [$client->id, $paymentLog['log']->id]) }}" style="display:inline-block; margin-bottom:4px;">
                                                                    @csrf
                                                                    <input type="hidden" name="lesson_id" value="{{ $course->id }}">
                                                                    <button type="submit" class="btn btn-warning btn-xs" onclick="return confirm('Add {{ addslashes($course->title) }} to this student profile?')">
                                                                        Add to profile
                                                                    </button>
                                                                </form><br>
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                                <p class="text-muted" style="margin-top: 12px;">
                                    If a course shows <strong>Add to profile</strong>, payment was logged but enrollment is missing — click to fix.
                                    Or use <a href="{{ route('admin_clientCourses', $client->id) }}">Enrolled Courses → Grant Free Access</a>.
                                </p>
                            </div>
                        </div>

                </div>

            </div>

        </div>
        <!-- ############ Main END-->
    </div>


@endsection
