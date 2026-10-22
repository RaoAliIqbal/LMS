@extends('admin.layouts.app')

@section('title')
    Customers Courses
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <!-- ############ Main START-->
        <div class="padding">
            <div class="row">
                <div class="col-md-12">

                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        @if ($message = Session::get('info'))
                            <div class="alert alert-info">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        <div class="box" style="margin-bottom: 20px;">
                            <div class="box-header">
                                <h2>Grant Course (Complimentary)</h2>
                            </div>
                            <div class="box-body">
                                @if(isset($lessons) && count($lessons))
                                    <form method="POST" action="{{ route('admin_grant_client_course', $client->id) }}" class="form-inline">
                                        @csrf
                                        <div class="form-group" style="margin-right: 10px;">
                                            <label for="lesson_id" style="margin-right: 8px;">Course</label>
                                            <select name="lesson_id" id="lesson_id" class="form-control" required style="min-width: 320px;">
                                                <option value="" disabled selected>Select a course</option>
                                                @foreach($lessons as $lesson)
                                                    @php $code = $courseCodes[$lesson->id] ?? 'Course'; @endphp
                                                    <option value="{{ $lesson->id }}">{{ $code }} — {{ $lesson->title }} (${{ $lesson->price }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" name="price" value="0">
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Grant this course to {{ addslashes($client->email) }} at no charge?')">
                                            Grant Free Access
                                        </button>
                                    </form>
                                    <p class="text-muted" style="margin-top: 10px; margin-bottom: 0;">
                                        Use this when payment was received but the course did not appear on the student profile.
                                    </p>
                                @else
                                    <div class="alert alert-warning" style="margin-bottom: 0;">
                                        Course list could not be loaded. Please refresh the page or contact support at 1-888-900-3382.
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="box">
                            <div class="box-header">
                                <h2>Customer's Courses</h2>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Course Files</th>
                                            <th>Enrolled At</th>
                                            <th>Completed At</th>
                                            <th>Action</th>
                                            <th>Email</th>
                                        </tr>
                                        </thead>
                                        <caption style="caption-side:top; padding:0 0 8px 0;">
                                            <a href="{{ route('admin_clientActivity', $client->id) }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-bar-chart"></i> View Full Activity Log
                                            </a>
                                        </caption>
                                        <tbody>
                                       
                                            @foreach($courses as $course)

                                                @php $files = $client->userCourseFiles($course->lesson_id)->get(); @endphp
                                                <tr>
                                                    <td>{{ ($course->course)?$course->course->title:'' }}</td>
                                                    <td>
                                                        @if(count($files))
                                                            <div>
                                                                <button type="button" onclick="ViewFiles(this)"  class="btn btn-primary btn-sm">View Files</button>
                                                                <div class="filesContainer">
                                                                    <ul class="files">
                                                                        @foreach($files as $file)
                                                                            <li><a href="{{ route('downloadPdf',['f'=>$file->file]) }}">{{ $file->file_name }}</a></li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        @else
                                                            No File Found
                                                        @endif
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($course->enrolled_at)->format('d M, Y') }}</td>
                                                    <td>{{ ($course->completed_at == null)?'Not completed yet':$course->completed_at }}</td>
                                                    <td>
                                                        <a href="{{ route('admin_delete_client_course', [$course->lesson_id, $course->user_id]) }}" class="btn btn-danger btn-sm">Delete</a>
                                                        @if(!is_null($course->completed_at))
                                                            @if($course->is_show == 1)
                                                                <a href="{{ route('admin_toggle_client_course', [$course->lesson_id, $course->user_id,0]) }}" class="btn btn-warning btn-sm text-white">Hide</a>
                                                            @else
                                                                <a href="{{ route('admin_toggle_client_course', [$course->lesson_id, $course->user_id, 1]) }}" class="btn btn-primary btn-sm">Show</a>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!is_null($course->completed_at))
                                                            <form method="POST"
                                                                  action="{{ route('admin_resendCourseCompletion', [$course->user_id, $course->id]) }}"
                                                                  onsubmit="return confirm('Resend completion email to {{ addslashes($client->email) }} for this course?')">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-info" title="Resend completion email with correct dates">
                                                                    <i class="fa fa-envelope-o"></i> Resend Email
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="text-muted" style="font-size:11px;">Not completed</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                </div>

            </div>

        </div>
        <!-- ############ Main END-->
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Files</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="modalBody">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <style>
        ul.files {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        ul.files li {
            padding: 10px;
            margin-bottom: 10px;
            background: #efefef;
        }

        .filesContainer {
            display: none;
        }
    </style>
    <script>
        function ViewFiles(t){
            let ele = $(t);
            let html = ele.parent().find('.filesContainer').html();
            $("#modalBody").html(html);
            $("#myModal").modal('show');
        }
    </script>
@endpush
