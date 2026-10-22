@extends('layouts.course')

@section('content')
    
    <input type="hidden" value="{{ $course->id }}" name="lesson_id" id="lesson_id">
    <input type="hidden" value="{{ $chapter->id }}" name="chapter_id" id="chapter_id">
    <button type="button" style="position:fixed !important; background: #1fd65c; border-color: #53b7b0;" data-exitTo="{{ route('myCourseChapter',['id'=>$course->id,'chapterId'=>$chapter->id, 'year'=>$year]) }}" id="saveAndExit">Exit</button>
    <div class="timer">
        <div class="clock" style="margin:2em;"></div>
        <div class="message"></div>
    </div>
    <section class="course_pg">

        <div class="course_sidebar d-none d-xl-block">
            <div class="scrollbar-inner">
                <div class="course_sidebar_headr">
                    <div id="counter"><label id="minutes">00</label>:<label id="seconds">00</label></div>

                    <span class="hme_icon hme_icon01"><a href="{{ route('myCourseChapter',['id'=>$course->id,'chapterId'=>$chapter->id, 'year'=>$year]) }}"><i class="fa fa-home"></i></a></span>
                    <h2>{{ $chapter->title }}</h2>
                </div>
                <div class="course_sidebar_lst">
                    <ul>
                        @if(count($chapter->topics))
                            @php $i = 0; @endphp
                            @foreach($chapter->topics as $topic)
                                @php $i++; $exercise = $topic->exercise; @endphp
                                <li class="">
                                    <a class="scrolltolesson"
                                       chapter="{{ $i }}"
                                       topic="{{ $topic->id }}"
                                       id="chapt_{{ $i }}"
                                       href="javascript:void(0);">
                                        <span>Topic {{ $i }}</span> {{ $topic->title }}
                                    </a>
                                    @if($exercise)
                                        {{--                                        <ul class="sub-menu">--}}
                                        {{--                                            <li><a id="exercise_{{ $exercise->id }} "--}}
                                        {{--                                                       chapter="{{ $i }}"--}}
                                        {{--                                                       topic="{{ $exercise->id }}" href="javascript:void(0);">--}}
                                        {{--                                                        {{ $exercise->title }}</a>--}}
                                        {{--                                            </li>--}}
                                        {{--                                        </ul>--}}
                                    @endif
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="course_content">
            @if(count($chapter->topics))
                @php
                    $totalExercises = 0;
                    $i = 0;
                    $topics = $chapter->topics;
                @endphp
                @foreach($topics as $topic)
                    @php
                        $i++;
                        $exercise = $topic->exercise;
                        $questions = isset($exercise->questions)?$exercise->questions:[];
                    @endphp
                   <div class="course_content_rownew {{ $loop->first ? 'show' : '' }}" id="les{{ $i }}" data-id="{{ $i }}">
                        <div class="scrollbar-inner">
                            @if($loop->index != 0)
                                <div id="previous_chapter" class="course_btn" onclick="prevchapter('{{ $i-1 }}')"><i class="fa fa-angle-up"></i> <span>Previous</span></div>
                            @endif
                            <div class="course_content_row">
                                <span class="course_content_arow"><i class="fa fa-angle-left"></i></span>
                                <div class="course_content_wrap">
                                    <h1><span>Topic {{ $i }}</span>{{ $topic->title }}</h1>
                                    {!! $topic->description !!}
                                    <hr>
                                    @if($exercise && count($questions) != 0)
                                        @php

                                            $userAnswerDb = $exercise->userAnswers($user->id)->get();
                                            $userAns = ($userAnswerDb != null) ? $userAnswerDb:[];

                                            if(count($userAnswerDb) === 0){ $totalExercises++; }
                                            @endphp

                                        <h1><span id="excerciseSectionHeading">Exercise </span>{{ $exercise->title }}</h1>
                                        {!! $exercise->description !!}
                                        @php $ij = 0; @endphp
                                        <form id="form_{{ $exercise->id }}">
                                            <input type="hidden" value="{{ $exercise->id }}" name="quiz_id">
                                            @foreach($questions as $question)
                                                @php $ij++; @endphp
                                                {{--  <div style="margin-bottom: 15px;"> <b>{{ $ij }}. {{ strip_tags(html_entity_decode($question->question)) }}</b></div>--}}
                                                <div class='questionCon'> <b>{{ $ij }}</b> <div class="question">{!! $question->question !!}</div></div>
                                                @php $answers = $question->answers; @endphp

                                                @if(count($answers))
                                                    <div class="AnsContainer">
                                                        @if($question->type == '1')
                                                        
                                                            <span class="ansInfo">Select the correct answer</span>
                                                            <ul>
                                                                @php
                                                                    $userAnswer = Helper::getUserAnsByQuestionId($question->id,$userAns);
                                                                @endphp
                                                                @php $ik = 0; @endphp
                                                                @foreach($answers as $answer)
                                                                    @php $ik++; @endphp
                                                                    <li>
                                                                        <div class="form-group">
                                                                            <input name="question[{{ $question->id }}]" {{ ($userAnswer->answer_id == $answer->id)?'checked':'' }} class="element_{{ $i }}"
                                                                                   value="{{ $answer->id }}" type="radio" data-model="myModal_{{ $i }}"
                                                                                   data-form="form_{{ $exercise->id }}"
                                                                                   onclick="showCorrectAns(this, false)">
                                                                            <label for="">{!! $answer->answer !!}</label>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @elseif($question->type == '2')
                                                            <span class="ansInfo">Type your answer in box below</span>
                                                            <ul>
                                                                @php
                                                                    $userAnswer = Helper::getUserAnsByQuestionIdCheckBox($question->id,$userAns);
                                                                @endphp
                                                                @php $il = 0; @endphp
                                                                @foreach($answers as $answer)
                                                                    @php $il++; @endphp
                                                                    <li>
                                                                        <div class="form-group">
                                                                            <input name="question[{{ $question->id }}][{{ $answer->id }}]" {{ (in_array($answer->id,$userAnswer))?'checked':'' }} class="element_{{ $i }}"  value="{{ $answer->id }}" type="checkbox">
                                                                            <label for="">{!! $answer->answer !!}</label>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @elseif($question->type == '3')
                                                            <span class="ansInfo">Type your answer in box below</span>
                                                            <ul>
                                                                @foreach($answers as $answer)
                                                                    <li>
                                                                        <div class="form-group">
                                                                            @php
                                                                                $userAnswer = Helper::getUserAnsByQuestionId($question->id,$userAns);
                                                                            @endphp
                                                                            <textarea class="form-control" class="element_{{ $i }}"  name="question[{{ $question->id }}]" id="" cols="30"
                                                                                      rows="10" data-model="myModal_{{ $i }}"
                                                                                      data-form="form_{{ $exercise->id }}"
                                                                                      onfocusout="showCorrectAns(this, false)">{{ ( $userAnswer != null ) ? $userAnswer->answer : ''  }}</textarea>
                                                                            <div class="correctAns">{!! $answer->answer !!}</div>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @elseif($question->type == '4')
                                                            <input type="hidden" name="question[{{ $question->id }}]" value="">
                                                            @php
                                                                $setZero = [];
                                                                $setOne = [];
                                                                $setTwo = [];
                                                                foreach ($answers as $answer){
                                                                    $setZero[] = $answer->answer;
                                                                    $setOne[] = $answer->answer;
                                                                    $setTwo[] = $answer->answer_two;
                                                                }
                                                                shuffle($setOne);
                                                            @endphp
                                                            @include('client.partials.matchTheColumn')
                                                        @endif
                                                    </div>
                                                @endif

                                            @endforeach
                                        </form>
                                        <div id="myModal_{{ $i }}" class="modal fade" role="dialog">
                                            <div class="modal-dialog modal-lg">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Correct Answers</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{--                                                        @include('client.partials.correctAns',['ex'=>$exercise,'q'=>$questions])--}}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <button type="button"
                                                data-model="myModal_{{ $i }}"
                                                data-form="form_{{ $exercise->id }}"
                                                onclick="showCorrectAns(this)" class="btn btn-primary btn-showCorrectAns">Submit Answers</button>
                                    @endif

                                </div>
                            </div>
                            @if(count($topics) != $i)
                                <div id="next_chapter" class="course_btn"  onclick="nextchapter('{{ $i+1 }}')">
                                    <span>Next</span> <i class="fa fa-angle-down"></i>
                                </div>
                            @endif
                            @if(count($topics) === $i)
                                
                                <div id="next_chapter" class="course_btn" >
                                    <a id="completeCourse"  href="{{ ($chapter->quiz && in_array($chapter->quiz->id,$quizzes) == false) ? route('chapterQuiz',['id'=>$course->id,'chapterId'=>$chapter->id, 'year'=>$year]) : route('chapterComplete',['id'=>$course->id,'chapterId'=>$chapter->id, 'year'=>$year]) }}"><span>Complete</span> <i class="fa fa-angle-down"></i></a>
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
            @endif

        </div>

    </section>
    <!-- Modal -->
    <div id="instruction" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Instructions</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <ul>
                        <li>DO NOT open multiple browser tabs/windows</li>
                        <li>Keep website running while completing exercises</li>
                        <li>Click on SUBMIT ANSWERS after completing exercise</li>
                        <li>ACTIVE participation is required, inactivity for 15 minutes it automatically logs you out and your time will not be save.</li>
                        <li>Before leaving course or shutting down computer, CLICK SAVE and EXIT button at top of page</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <!-- Modal -->
    <div id="alert" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">⚠️ Alert: Inactivity Timeout ⚠️</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Reminder: No activity for 15 mins will log you out, and progress won't be saved. Please stay active to continue working. Save your work periodically to avoid any loss.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <input type="hidden" id="addTime" value="{{ route('addTime') }}">
    {{--    <input type="hidden" id="previousMin" value="{{ ($previousTime[0] != 0)? ($previousTime[0])*60:0 }}">--}}
    <input type="hidden" id="previousMin" value="{{ $previousTime }}">
    <link rel="stylesheet" href="{{ url('courseAssets/css/CoursePage.css') }}">
    <style>
        .MTCorrectAnsCon {
            border: 1px solid #6ea947;
            box-shadow: 0 0 2px 1px #a8ff6e82;
            padding: 12px !important;
        }

        .MTWrongAnsCon {
            border: 1px solid #e90101;
            box-shadow: 0 0 2px 1px #d82e0582;
            padding: 12px !important;
        }
        span.MTCorrectAns {
            position: relative;
            top: -28px;
            left: -83px;
            background: #6ea947;
            color: #fff;
            padding: 2px 4px;
            line-height: 0;
            border-radius: 50px;
        }

        span.MTWrongAns {
            position: relative;
            top: -28px;
            left: -83px;
            background: #e90000;
            color: #fff;
            padding: 1px 6px;
            line-height: 0;
            border-radius: 50px;
        }
        .questionCon .question {
            width: 100%;
            margin-left: 15px;
        }
        .questionCon{
            margin-bottom: 15px;
            clear: both;
            display: flex;
            align-items: center;

        }
        .form-group.bg_red {
            background: #ff5f66;
        }
        .form-group.bg_red label, .form-group.bg_green label {
            display: contents;
        }
        .form-group.bg_green {
            background: #89ff59;
        }
        .form-group.bg_red, .form-group.bg_green {
            padding: 5px 5px;
        }
        span.ansInfo {
            color: #007bff;
            margin: 10px 0;
            display: block;
            font-weight: bold;
        }
        .draggable {
            touch-action: none
        }
        #target, #source {
            background: #ddd;
            float: left;
            margin: 0 6px 12px 6px;
            padding: 6px;
            width: 100%;
            min-height: 160px;
        }
        #sourceFields, #targetFields { padding: 0 12px 12px 12px; }
        #targetFields { margin-bottom: 6px; }
        .targetFieldEle, .sourceFieldEle { margin: 6px; }
        .sourceFieldEle {
            border-radius: 4px;
            box-shadow: 1px 1px 1px rgb(0 0 0 / 25%);
            font-size: 11px;
            margin: 6px;
            padding: 2px 6px;
            width: 100%;
            z-index: 1000;
            background: #fff;
            padding: 10px;
        }
        .sourceFieldEle:hover { background: #ddd; border-color: #aaa; cursor: default; }
        .targetFieldEle {

            display: inline-block;
            font-size: small;
            line-height: 24px;
            margin: 6px 12px;
            vertical-align: middle;
            background: #fff;
            padding: 20px;
            margin-left: 0 !important;
            width: 100% !important;
        }
        .targetFieldEle .k-icon:hover {
            cursor: pointer;
        }
        .outputEle {
            clear: left;
            display: none;
            height: 200px;
            margin: 12px;
            width: 500px;
        }
        .questionCon b {
            width: 21px;
        }
        
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js" integrity="sha512-eYSzo+20ajZMRsjxB6L7eyqo5kuXuS2+wEbbOkpaur+sA2shQameiJiWEzCIDwJqaB0a4a6tCuEvCOBHUg3Skg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ url('courseAssets/js/CoursePage.js') }}"></script>
    <link rel="stylesheet" href="{{ url('courseAssets/css/flipclock.css') }}">
    <script src="{{ url('courseAssets/js/flipclock.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="{{ url('webAssets/js/matchTheColumn.js') }}"></script>

    {{-- ═══════════════════════════════════════════════
         VIDEO & CONTENT PROTECTION
    ═══════════════════════════════════════════════ --}}
    <style>
        /* Block right-click context menu on all video/iframe elements */
        video, iframe { pointer-events: none; }
        /* Re-enable pointer events ONLY for controls via overlay trick */
        .video-protect-wrap { position: relative; display: inline-block; width: 100%; }
        .video-protect-wrap video,
        .video-protect-wrap iframe { pointer-events: auto; }
        /* Prevent text selection on course content */
        .course_content_wrap {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        /* Transparent overlay on top of video to block right-click download */
        .video-overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 10;
            background: transparent;
        }
    </style>

    <script>
        // ── 1. Block right-click on entire page ──────────────────
        document.addEventListener('contextmenu', function(e) {
            if (e.target.tagName === 'VIDEO' || e.target.tagName === 'IFRAME' ||
                e.target.closest('.course_content_wrap')) {
                e.preventDefault();
                return false;
            }
        });

        // ── 2. Block keyboard shortcuts for saving/downloading ───
        document.addEventListener('keydown', function(e) {
            // Block: Ctrl+S, Ctrl+U (view source), Ctrl+Shift+I/J/C (DevTools)
            // F12 (DevTools), Ctrl+A (select all)
            if (
                (e.ctrlKey && ['s','u','a','p'].includes(e.key.toLowerCase())) ||
                (e.ctrlKey && e.shiftKey && ['i','j','c'].includes(e.key.toLowerCase())) ||
                e.key === 'F12'
            ) {
                e.preventDefault();
                return false;
            }
        });

        // ── 3. Block drag-and-drop of media elements ─────────────
        document.addEventListener('dragstart', function(e) {
            if (e.target.tagName === 'VIDEO' || e.target.tagName === 'IMG' ||
                e.target.tagName === 'IFRAME') {
                e.preventDefault();
            }
        });

        // ── 4. Wrap all videos with overlay after DOM loads ──────
        document.addEventListener('DOMContentLoaded', function() {
            // Remove controlsList download button from native video elements
            document.querySelectorAll('video').forEach(function(v) {
                v.setAttribute('controlsList', 'nodownload');
                v.setAttribute('oncontextmenu', 'return false;');
                v.disablePictureInPicture = true;

                // Wrap in overlay div
                var wrap = document.createElement('div');
                wrap.style.position = 'relative';
                wrap.style.display = 'block';
                v.parentNode.insertBefore(wrap, v);
                wrap.appendChild(v);

                var overlay = document.createElement('div');
                overlay.className = 'video-overlay';
                overlay.addEventListener('contextmenu', function(e){ e.preventDefault(); });
                wrap.appendChild(overlay);
            });

            // Remove download controls from iframes (Vimeo/YouTube)
            document.querySelectorAll('iframe').forEach(function(f) {
                var src = f.src || '';
                // Add no-download params for Vimeo
                if (src.includes('vimeo.com') && !src.includes('download=0')) {
                    f.src = src + (src.includes('?') ? '&' : '?') + 'download=0&title=0&byline=0&portrait=0';
                }
                // Add no-related for YouTube
                if (src.includes('youtube.com') || src.includes('youtu.be')) {
                    if (!src.includes('rel=0')) {
                        f.src = src + (src.includes('?') ? '&' : '?') + 'rel=0&modestbranding=1';
                    }
                }
            });
        });

        // ── 5. Detect DevTools open (basic detection) ────────────
        (function() {
            var threshold = 160;
            var check = function() {
                if (window.outerWidth - window.innerWidth > threshold ||
                    window.outerHeight - window.innerHeight > threshold) {
                    // DevTools likely open — blur the content
                    document.body.style.filter = 'blur(5px)';
                } else {
                    document.body.style.filter = '';
                }
            };
            window.addEventListener('resize', check);
        })();
    </script>

    <script>

        $(document).ready(function (){
         
            $("#instruction").modal('show');
        })
        let addTimeUrl = $("#addTime").val();
        let lesson_id = $("#lesson_id").val();
        let chapter_id = $("#chapter_id").val();
        let totalExercises = Number('{{ $totalExercises }}');
        let isExerciseSubmitted = 0;
        var clock;

        $(document).ready(function() {

            // setInterval(openAlert, 5000);

            clock = $('.clock').FlipClock({
                clockFace: 'HourlyCounter'
            });
            let preTime = parseInt($("#previousMin").val());
            clock.setTime(preTime);
        });
        function openAlert(){
            $("#alert").modal('show');
            // saveAndExit(true);
            // alert('erro');
            // setTimeout(() => {
            //     // window.location.href="{{ route('logout',['show_message'=>true]) }}"
            // }, 2000);
        }

        document.addEventListener("visibilitychange", function() {
            if (document.hidden){
                clock.stop();
            } else {
                clock.start();
            }
        });


        $("#completeCourse").click(function(e){
            e.preventDefault();
            if(isExerciseSubmitted >= totalExercises) {
                $.blockUI({ css: {
                        border: 'none',
                        padding: '15px',
                        backgroundColor: '#000',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        opacity: .5,
                        color: '#fff'
                    } });
                let ele = $(this);
                let time = parseInt(clock.getTime());
                $.ajax({
                    url: addTimeUrl,
                    method: 'POST',
                    data: {
                        lesson_id: lesson_id,
                        chapter_id: chapter_id,
                        time: time,
                        year : "{{$year}}"
                        // min:min,
                        // sec:sec
                    },
                    success: function (res) {

                        location.assign(ele.attr("href"));
                    }
                });
            }else{
                alert('You need to complete all exercises.');
            }

        });
        $("#saveAndExit").click(function(){
            // console.log('click');
            saveAndExit();

        });
        function saveAndExit(auto_save=false){
            if(auto_save == false){
                $.blockUI({ css: {
                    border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff'
                } });
            }


            let time = parseInt(clock.getTime());

            $.ajax({
                url: addTimeUrl,
                method: 'POST',
                data: {
                    lesson_id:lesson_id,
                    chapter_id:chapter_id,
                    time:time,
                    year:"{{ $year }}"
                },
                success: function (res) {
                    if(auto_save == false){
                        location.assign($("#saveAndExit").attr("data-exitTo"));
                    }
                }
            });
        }
        function saveExcersize(formId,modal, is_show_modal=true){
            let form = $("#"+formId).serialize();
            isExerciseSubmitted = isExerciseSubmitted+1;
            $.ajax({
                url: '{{ route("saveUserExercise") }}',
                method: 'POST',
                data: form,
                success: function (res) {
                    if(is_show_modal){
                        modal.find('.modal-body').html(res);
                        modal.modal('show');
                    }
                }
            });
            
            if(is_show_modal == false){
                saveAndExit(true);//autosave the stime
            }
        }
        function showCorrectAns(t, is_modal=true){

            $(".error").remove();
            let ele = $(t);
            let modal = $("#"+ele.attr('data-model'));

            let textarea = ele.parent().find('textarea');
            let checkboxes = ele.parent().find('input[type=checkbox]');
            let radios = ele.parent().find('input[type=radio]');
            let showModal = true;

            let checkboxesName = [];
            let radioName = [];
            radios.each(function (key,val){
                let radio = $(val);
                radioName.push(radio.attr('name'));
            })
            if(radioName.length != 0){
                radioName = Array.from(new Set(radioName));
                for (let i = 0; i < radioName.length; i++){
                    let radio = $("input[name='"+radioName[i]+"']");
                    if(radio.is( ":checked" ) == false){
                        showModal = false;
                        radio.parent().parent().parent().after('<div class="error">Answer required</div>');
                    }
                }
            }
            textarea.each(function(key,val){
                let input = $(val);
                if(input.val() === ''){
                    showModal = false;
                    input.after('<div class="error">Answer required</div>');
                }
            })


            if(showModal == true){
                saveExcersize(ele.attr('data-form'),modal, is_modal);
                // modal.modal('show');
            }
        }



        let idleTimeout;
        function handleIdle() {
            saveAndExit(true);
            setTimeout(() => {
                window.location.href="{{ route('logout',['show_message'=>true]) }}"
            }, 2000);
        }
        // Reset the timeout
        function resetIdleTimeout() {
            clearTimeout(idleTimeout);
            idleTimeout = setTimeout(handleIdle, 900000); // 5000 milliseconds = 5 seconds
        }

        // Add event listeners for mouse movement and button presses
        document.addEventListener("mousemove", resetIdleTimeout);
        document.addEventListener("keypress", resetIdleTimeout);
        document.addEventListener("wheel", resetIdleTimeout);

        // Auto-save study time every 60 seconds to prevent data loss during outages
        setInterval(function() {
            if (typeof clock !== 'undefined' && clock) {
                saveAndExit(true);
            }
        }, 60000);
        
        resetIdleTimeout();

        @if(request()->has('success'))
            Swal.fire({
            title: "Success",
            text: "Thank you for purchasing a course with us, please go to your dashboard and select the course to begin.",
            icon: "success"
        });
        @endif
        (function(){
          const old = window.scrolltolesson;
          window.scrolltolesson = function(chapter, topic){
            var $t = $("#les" + chapter);
            if(!$t.length) return;
            if (typeof old === "function") return old(chapter, topic);
            $('html, body').animate({ scrollTop: $t.offset().top }, 800);
          };
        })();

    </script>
@endpush
