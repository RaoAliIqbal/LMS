@extends('admin.layouts.app')

@section('title') Zoom Meetings @endsection

@section('content')
<div class="content-main" id="content-main">
<div class="padding">

    {{-- Header --}}
    <div class="row mb-3">
        <div class="col-md-8">
            <h4 style="margin:0; font-weight:700; color:#1a73e8;">
                <i class="fa fa-video-camera"></i> &nbsp;Zoom Meetings Manager
            </h4>
            <p class="text-muted" style="margin:4px 0 0; font-size:13px;">
                Create and manage Zoom live sessions for your courses.
            </p>
        </div>
        <div class="col-md-4 text-right">
            <button class="btn btn-default btn-sm" onclick="testZoomConnection()" id="testBtn">
                <i class="fa fa-plug"></i> Test Connection
            </button>
            <div id="connectionStatus" style="font-size:12px; margin-top:5px;"></div>
        </div>
    </div>

    @include('layouts.partials.alertBox')

    {{-- Stats row --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div style="background:#e8f4fd; border-left:4px solid #1a73e8; border-radius:6px; padding:14px 18px;">
                <div style="font-size:28px; font-weight:800; color:#1a73e8;">{{ count($lessons) }}</div>
                <div style="font-size:12px; color:#555; font-weight:600;">Courses with Zoom Enabled</div>
            </div>
        </div>
        <div class="col-md-4">
            <div style="background:#e8f8f0; border-left:4px solid #28a745; border-radius:6px; padding:14px 18px;">
                <div style="font-size:28px; font-weight:800; color:#28a745;">{{ count($zoomMeetings) }}</div>
                <div style="font-size:12px; color:#555; font-weight:600;">Active Zoom Meetings</div>
            </div>
        </div>
        <div class="col-md-4">
            <div style="background:#fff8e1; border-left:4px solid #ffc107; border-radius:6px; padding:14px 18px;">
                <div style="font-size:28px; font-weight:800; color:#ffc107;">
                    {{ \App\Models\Lesson::where('has_zoom', 0)->count() }}
                </div>
                <div style="font-size:12px; color:#555; font-weight:600;">Courses without Zoom</div>
            </div>
        </div>
    </div>

    {{-- Zoom-enabled courses table --}}
    <div class="box mb-4">
        <div class="box-header" style="display:flex; align-items:center; justify-content:space-between;">
            <h3 style="margin:0; font-size:15px; font-weight:700;">
                <i class="fa fa-video-camera" style="color:#1a73e8;"></i> Courses with Zoom Sessions
            </h3>
        </div>
        <div class="box-body p-0">
            @if(count($lessons))
            <div class="table-responsive">
                <table class="table table-hover m-0" style="font-size:13px;">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th>Course</th>
                            <th>Meeting ID</th>
                            <th>Date & Time</th>
                            <th>Join Link</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lessons as $lesson)
                        <tr id="lesson-row-{{ $lesson->id }}">
                            <td>
                                <strong>{{ $lesson->title }}</strong><br>
                                <span class="text-muted" style="font-size:11px;">ID: {{ $lesson->id }}</span>
                            </td>
                            <td>
                                <span style="font-family:monospace; background:#e8f4fd; padding:3px 8px; border-radius:4px; font-size:12px;">
                                    {{ $lesson->zoom_meeting_id ?? '—' }}
                                </span>
                            </td>
                            <td>
                                @if($lesson->zoom_date)
                                    <strong>{{ \Carbon\Carbon::parse($lesson->zoom_date)->format('M j, Y') }}</strong><br>
                                    <span class="text-muted">{{ $lesson->zoom_time ? \Carbon\Carbon::createFromFormat('H:i', $lesson->zoom_time)->format('g:i A') : '' }}</span>
                                @else
                                    <span class="text-muted">No date set</span>
                                @endif
                            </td>
                            <td>
                                @if($lesson->zoom_link)
                                <a href="{{ $lesson->zoom_link }}" target="_blank" class="btn btn-primary btn-xs">
                                    <i class="fa fa-video-camera"></i> Join
                                </a>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin_edit_lessons', $lesson->id) }}" class="btn btn-default btn-xs">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <button class="btn btn-danger btn-xs" onclick="deleteMeeting({{ $lesson->id }})">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center" style="padding:40px 20px; color:#888;">
                <i class="fa fa-video-camera" style="font-size:36px; color:#c5d5ea;"></i>
                <p style="margin:12px 0 0; font-size:14px;">No courses have Zoom meetings yet.</p>
                <p style="font-size:12px;">Go to <a href="{{ route('admin_lessons') }}">Courses</a> → Edit a course → Add Zoom session.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- All Zoom meetings from API --}}
    @if(count($zoomMeetings))
    <div class="box">
        <div class="box-header">
            <h3 style="margin:0; font-size:15px; font-weight:700;">
                <i class="fa fa-list" style="color:#1a73e8;"></i> All Scheduled Zoom Meetings (from Zoom API)
            </h3>
        </div>
        <div class="box-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-0" style="font-size:13px;">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th>Topic</th>
                            <th>Meeting ID</th>
                            <th>Start Time</th>
                            <th>Duration</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($zoomMeetings as $meeting)
                        <tr>
                            <td><strong>{{ $meeting['topic'] }}</strong></td>
                            <td><code>{{ $meeting['id'] }}</code></td>
                            <td>{{ isset($meeting['start_time']) ? \Carbon\Carbon::parse($meeting['start_time'])->format('M j, Y g:i A') : '—' }}</td>
                            <td>{{ $meeting['duration'] ?? '—' }} min</td>
                            <td>
                                <span class="label label-success">Scheduled</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>
</div>
@endsection

@push('scripts')
<script>
function testZoomConnection() {
    var btn = document.getElementById('testBtn');
    var status = document.getElementById('connectionStatus');
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Testing...';
    btn.disabled = true;

    fetch('{{ route("admin_zoom_test") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = '<i class="fa fa-plug"></i> Test Connection';
        btn.disabled = false;
        if (data.success) {
            status.innerHTML = '<span style="color:#28a745;"><i class="fa fa-check-circle"></i> Connected as ' + data.email + '</span>';
        } else {
            status.innerHTML = '<span style="color:#dc3545;"><i class="fa fa-times-circle"></i> ' + data.message + '</span>';
        }
    })
    .catch(() => {
        btn.innerHTML = '<i class="fa fa-plug"></i> Test Connection';
        btn.disabled = false;
        status.innerHTML = '<span style="color:#dc3545;">Connection error</span>';
    });
}

function deleteMeeting(lessonId) {
    if (!confirm('Delete this Zoom meeting? This will remove it from Zoom and the course.')) return;

    fetch('/admin/zoom/delete-meeting/' + lessonId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            var row = document.getElementById('lesson-row-' + lessonId);
            if (row) row.remove();
            alert('Meeting deleted successfully.');
        } else {
            alert('Error: ' + data.message);
        }
    });
}
</script>
@endpush
