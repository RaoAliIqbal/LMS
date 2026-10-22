@extends('layouts.web')

@section('content')
<div class="container">
    <section class="page_sidepadding page_sidepadding_home">
        <div class="dashboard_page">
            <div class="dashboard_row">
                <div class="row">
                    <div class="col-lg-12">
                        @include('layouts.partials.alertBox')
                        @if(Session::has('QuizError'))
                            <div class="alert alert-info QuizError">
                                {!! Session::get('QuizError')['msg'] !!}
                                <div class="btnContainerQuiz">
                                    <a href="{{ route('chapterTakeQuiz',['id'=>Session::get('QuizError')['id'],'chapterId'=>Session::get('QuizError')['chapterId'], 'year'=>$year]) }}" class="btn btn-default">Retake Quiz</a>
                                    <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-default">Review</button>
                                    <a href="{{ route('myCourseChapterLearning',['id'=>Session::get('QuizError')['id'],'chapterId'=>Session::get('QuizError')['chapterId'], 'year'=>$year]) }}" class="btn btn-default">Review My material and Retake Quiz</a>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div id="myModal" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-lg">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Your Quiz</h4>
                                        </div>
                                        <div class="modal-body">
                                            <table>
                                                @foreach(Session::get('QuizError')['previousQuiz'] as $quiz)

                                                    @if($quiz['type'] == 'single')
                                                        <tr>

                                                            <td>{!!  array_keys($quiz)[1] !!}</td>
                                                            <td>{!! array_values($quiz)[1]['answer'] !!}</td>
                                                        </tr>
                                                    @endif

                                                    @if($quiz['type'] == 'multi')
                                                        <td>{!!  array_keys($quiz)[1] !!}</td>
                                                        <td>
                                                            <ul>
                                                                @foreach(array_values($quiz)[1] as $multi)
                                                                    <li>{!! $multi[0] !!}</li>
                                                                @endforeach
                                                            </ul>
                                                        </td>
                                                    @endif

                                                @endforeach
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endif

                        <div class="">
                            <div class="row courseTitleCon">
                                <div class="col-md-12">
                                    <h4>{{ $course->title }}</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 style="clear:both;">DOWNLOAD FORMS</h6>
                                    @if($course->pdf_file != '')
                                        <a href="{{ route('downloadPdf',['f'=>$course->pdf_file]) }}" class="btn btn-primary downloadPSDBtn">Download PDF</a>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                            
                                    @if($isTimeCompleted)

                                            @if($isAllQuizPassed)
                                            @if($isEvaluationCompleted)
                                                <a href="{{ route('generateCertificate',[$course->id, 'false', $year]) }}" class="certifcateBtn btn btn-primary">Generate Certificate</a>
                                            @else
                                                <a href="{{ route('courseEvaluation',['id'=>$course->id, 'year'=>$year]) }}" class="certifcateBtn btn btn-primary">Generate Certificate</a>
                                            @endif
                                        @else

                                            <span class="timeAlert">
                                             You need to pass all Quizzes First.
                                        </span>
                                        @endif
                                    @else
                                        <span class="timeAlert">
                                        You have spent {{ Carbon\CarbonInterval::seconds($seconds)->cascade()->forHumans() }} out of {{ $course->hours }} hours.
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <br><br>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="ChaptersCon">
                                        <h6>Chapters</h6>
                                        <ul class="chaptersLi">
                                            @foreach($course->chapters as $chapter)
                                                <li>
                                                    <a href="{{ route('myCourseChapter',['id'=>$course->id,'chapterId'=>$chapter->id, 'year'=>$year]) }}">{{ $chapter->title }}</a>
                                                    @if(in_array($chapter->id,$completedQuizes))
                                                        <span class="completedQuiz"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                    @endif
                                                    @if(isset($chapterTimes[$chapter->id]))
                                                        <p>You have spent {{ round($chapterTimes[$chapter->id], 1) }} mins out of 55 mins{{ ($chapterTimes[$chapter->id] >= 55) ? ' — completed' : '' }}</p>
                                                    @endif

													 <a href="{{ route('myCourseChapter',['id'=>$course->id,'chapterId'=>$chapter->id, 'year'=>$year]) }}" class="btn btn-white btn-sm">View Details</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="UserUploads">
                                        <h6>Uploads</h6>
                                        <form method="post" action="{{ route('uploadUserFiles') }}" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" value="course_documents" name="type">
                                            <input type="hidden" value="{{ $course->id }}" name="course_id">
                                          <div class="row">
                                              <div class="col-md-10">
                                                  <div class="form-group">
                                                      <label for="">Select File</label>
                                                      <input type="file" required class="form-control" name="upload">
                                                  </div>
                                              </div>
                                              <div class="col-md-2">
                                                  <input style="margin-top: 25px" type="submit" class="btn btn-primary" value="Upload">
                                              </div>
                                          </div>
                                            @php $files = auth()->user()->userCourseFiles($course->id)->get(); @endphp
                                            @if(count($files))
                                            <ul class="files">
                                                @foreach($files as $file)
                                                    <li><a href="{{ route('downloadPdf',['f'=>$file->file]) }}">{{ $file->file_name }}</a></li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </form>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    @if($course->video != '')
                                    <div class="ChaptersCon" style="position:relative;">
                                        <h6>Video</h6>
                                        <video
                                            controls
                                            controlsList="nodownload nofullscreen"
                                            disablePictureInPicture
                                            oncontextmenu="return false;"
                                            src="{{ url($course->video) }}"
                                            style="width:100%;">
                                        </video>
                                        {{-- Transparent overlay blocks right-click save --}}
                                        <div style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:1;"
                                             oncontextmenu="return false;"></div>
                                    </div>
                                    @endif

                                    {{-- ===== ZOOM LIVE SESSION CARD (enrolled students only) ===== --}}
                                    @if($course->has_zoom)
                                    <div class="ChaptersCon" style="border:2px solid #2d8cff; border-radius:8px; padding:18px; margin-top:18px; background:#f0f7ff;">
                                        <h6 style="color:#2d8cff; font-size:15px; margin-bottom:12px;">
                                            <i class="fa fa-video-camera"></i> &nbsp;Live Zoom Session
                                        </h6>

                                        @if($course->zoom_link)
                                            {{-- Meeting details available --}}
                                            @if($course->zoom_date)
                                            <p style="margin-bottom:6px; font-size:13px;">
                                                <strong><i class="fa fa-calendar"></i> Date:</strong>
                                                {{ \Carbon\Carbon::parse($course->zoom_date)->format('F j, Y') }}
                                            </p>
                                            @endif

                                            @if($course->zoom_time)
                                            <p style="margin-bottom:6px; font-size:13px;">
                                                <strong><i class="fa fa-clock-o"></i> Time:</strong>
                                                {{ \Carbon\Carbon::createFromFormat('H:i', $course->zoom_time)->format('g:i A') }}
                                            </p>
                                            @endif

                                            @if($course->zoom_meeting_id)
                                            <p style="margin-bottom:4px; font-size:13px;">
                                                <strong><i class="fa fa-hashtag"></i> Meeting ID:</strong>
                                                <span style="font-family:monospace; background:#ddeeff; padding:2px 6px; border-radius:3px;">{{ $course->zoom_meeting_id }}</span>
                                            </p>
                                            @endif

                                            @if($course->zoom_password)
                                            <p style="margin-bottom:10px; font-size:13px;">
                                                <strong><i class="fa fa-lock"></i> Passcode:</strong>
                                                <span style="font-family:monospace; background:#ddeeff; padding:2px 6px; border-radius:3px;">{{ $course->zoom_password }}</span>
                                            </p>
                                            @endif

                                            @if($course->zoom_description)
                                            <p style="font-size:12px; color:#555; background:#fff; border-left:3px solid #2d8cff; padding:8px 10px; border-radius:0 4px 4px 0; margin-bottom:12px;">
                                                {{ $course->zoom_description }}
                                            </p>
                                            @endif

                                            <a href="{{ $course->zoom_link }}"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               style="display:block; text-align:center; background:#2d8cff; color:#fff; padding:10px; border-radius:5px; font-weight:bold; font-size:14px; text-decoration:none;">
                                                <i class="fa fa-video-camera"></i> &nbsp;Join Live Session
                                            </a>
                                        @else
                                            {{-- No meeting scheduled yet --}}
                                            <div style="text-align:center; padding:10px 0;">
                                                <i class="fa fa-clock-o" style="font-size:28px; color:#2d8cff; margin-bottom:8px; display:block;"></i>
                                                <p style="font-size:13px; color:#555; margin-bottom:4px;">
                                                    <strong>Live Zoom session coming soon!</strong>
                                                </p>
                                                <p style="font-size:12px; color:#888; margin-bottom:0;">
                                                    Meeting details will appear here once scheduled. Check back soon.
                                                </p>
                                            </div>
                                        @endif

                                        <p style="font-size:11px; color:#888; text-align:center; margin-top:10px; margin-bottom:0;">
                                            <i class="fa fa-lock"></i> Visible to enrolled students only
                                        </p>
                                    </div>
                                    @endif
                                    {{-- ===== END ZOOM CARD ===== --}}

                                </div>
                            </div>
                            <div class="row">


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .swal2-popup {
        width: 500px; /* Adjust the width as needed */
        height:400px
        }
        
        .swal2-popup {
        font-size: 14px !important;
        font-family: Georgia, serif;
        }
        
        ul.chaptersLi li .percentage {
            position: absolute;
            right: 10px;
            font-weight: bold;
            color: #fff;
        }
        .downloadPSDBtn {
            background: #6ea947;
        }
        
        .downloadPSDBtn:hover {
            background: #5e933b;
        }
    </style>
    <link rel="stylesheet" href="{{ url('courseAssets/css/CourseDetailsPage.css') }}">
    
    <script>
        $(document).ready(function(){
            if("{{ session()->has('success') }}"){
                Swal.fire({
                title: 'Congratulations',
                html: "{!! session('success') !!}",
                icon: 'success',
                });
            }
        });
    </script>
@endpush
