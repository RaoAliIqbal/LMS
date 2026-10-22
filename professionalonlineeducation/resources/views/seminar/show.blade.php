@extends('../admin/layouts.app')

@section('template_title')
    {{ $seminar->course_title ?? 'Show Seminar' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Seminar</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('seminars.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group"><strong>Seminar Id:</strong> {{ $seminar->seminar_id }}</div>
                        <div class="form-group"><strong>Course Title:</strong> {{ $seminar->course_title }}</div>
                        <div class="form-group"><strong>CEUs:</strong> {{ $seminar->ceus }}</div>
                        <div class="form-group"><strong>Hours:</strong> {{ $seminar->hours }}</div>
                        <div class="form-group"><strong>Start Date:</strong> {{ $seminar->start_date }}</div>
                        <div class="form-group"><strong>End Date:</strong> {{ $seminar->end_date }}</div>
                        <div class="form-group"><strong>Start Time:</strong> {{ $seminar->start_time }}</div>
                        <div class="form-group"><strong>End Time:</strong> {{ $seminar->end_time }}</div>
                        <div class="form-group"><strong>Location:</strong> {{ $seminar->location }}</div>
                        <div class="form-group"><strong>Address:</strong> {{ $seminar->address }}, {{ $seminar->city }}, {{ $seminar->state }} {{ $seminar->zip_code }}</div>
                        <div class="form-group"><strong>Cost:</strong> {{ $seminar->cost }}</div>
                        <div class="form-group"><strong>Max Students:</strong> {{ $seminar->max_students }}</div>
                        <div class="form-group"><strong>Description:</strong> {{ $seminar->description }}</div>
                    </div>
                </div>

                {{-- ── Enrolled Students & Attendance ───────────────────── --}}
                <div class="card mt-4">
                    <div class="card-header">
                        <span class="card-title"><strong>Enrolled Students</strong> &mdash; Mark Attendance to Enable Certificate</span>
                    </div>
                    <div class="card-body">
                        @if($seminar->enrolledUsers->count())
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Enrolled At</th>
                                        <th>Price Paid</th>
                                        <th>Attended / Completed</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($seminar->enrolledUsers as $attendee)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $attendee->FullName }}</td>
                                            <td>{{ $attendee->email }}</td>
                                            <td>{{ $attendee->pivot->enrolled_at ? \Carbon\Carbon::parse($attendee->pivot->enrolled_at)->format('M d, Y') : '—' }}</td>
                                            <td>${{ number_format($attendee->pivot->price, 2) }}</td>
                                            <td>
                                                @if($attendee->pivot->completed_at)
                                                    <span class="badge badge-success">
                                                        ✓ {{ \Carbon\Carbon::parse($attendee->pivot->completed_at)->format('M d, Y') }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">Not marked</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($attendee->pivot->completed_at)
                                                    <form method="POST" action="{{ route('seminar.unmarkAttended', [$seminar->id, $attendee->pivot->id]) }}" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning"
                                                                onclick="return confirm('Remove attendance for {{ $attendee->FullName }}?')">
                                                            Remove
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('seminar.markAttended', [$seminar->id, $attendee->pivot->id]) }}" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            Mark Attended
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">No students enrolled yet.</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
