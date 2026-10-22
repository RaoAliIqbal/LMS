@extends('layouts.web')


@section('content')


    <section class="page_sidepadding page_sidepadding_home">
        <div class="dashboard_page">

            <div class="dashboard_row QuizRow">
                <div class="container">
                    <div class="row">
                        <div class="timer">
                            <div class="clock" style="margin:2em;"></div>
                            <div class="message"></div>
                        </div>
                        <div class="col-md-12">
                        @php $questions = $quiz->questions()->inRandomOrder()->limit(4)->get(); @endphp
                            @if(count($questions))


                                <ul style="display: none" class="nav nav-tabs" id="myTab" role="tablist">
                                    @foreach($questions as $question)
                                        <li class="nav-item">
                                            <a class="nav-link {{ ($loop->first)?'active':'' }}"
                                               id="{{ $question->id }}-tab" data-toggle="tab" href="#id_{{ $question->id }}" role="tab" aria-controls="home" aria-selected="true">{{ $question->id }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <h1>{{ $quiz->title }}</h1>
                                    <hr class="titleHr" />
                                    <div class="clear"></div>

                                    {!! Form::open(['method'=>'POST','class'=>'validate','files'=>true,'action'=>['QuizController@UserSaveQuiz'], 'id'=>'quizForm']) !!}
                                    <input type="hidden" name="quizId" value="{{ $quiz->id }}">
                                    <input type="hidden" name="chapterId" value="{{ $chapter->id }}">
                                    <input type="hidden" name="courseId" value="{{ $course->id }}">
                                    <input type="hidden" name="year" value="{{ $year }}">
                                    <input type="hidden" name="study_time" id="study_time" value="0">
                                    @foreach($questions as $question)

                                        <div class=" quizQuestion {{ ($loop->first)?'show active':'' }}" id="id_{{ $question->id }}" role="tabpanel" >
                                            <div class="question">{!! $question->question !!}</div>
                                            <div class="clear"></div>
                                            <ul class="ansUl">
                                                @if($question->type == 1)
                                                    @foreach($question->answers as $ans)
                                                        <li class="{{ 'cls_'.$question->id }}">
                                                            <input type="radio" required name="ans[{{ $question->id }}]" value="{{ $ans->id }}"  class="">
                                                            <span>{!! $ans->answer !!}</span>
                                                        </li>
                                                    @endforeach
                                                @elseif($question->type == 2)
                                                    @foreach($question->answers as $ans)
                                                        <li class="{{ 'cls_'.$question->id }}">
                                                            <input type="checkbox" name="ans[{{ $question->id }}][{{ $ans->id }}]" value="{{ $ans->id }}" class="">
                                                            <span>{!! $ans->answer !!}</span>
                                                        </li>
                                                    @endforeach
                                                @elseif($question->type == 3)
                                                    <li class="{{ 'cls_'.$question->id }}">
                                                        <textarea name="ans[{{ $question->id }}]"  class="form-control" placeholder="type your answer"></textarea>
                                                    </li>
                                                @endif
                                            </ul>
                                            <div class="errorBox"></div>


{{--                                            <hr>--}}
{{--                                            @if (!$loop->first)--}}
{{--                                                <button type="button" class="prevtab btn btn-success">Previous</button>--}}
{{--                                            @endif--}}
{{--                                            @if ($loop->last)--}}
{{--                                                <button type="submit" class="btn btn-success">Finish</button>--}}
{{--                                            @else--}}
{{--                                                <button type="button" data-class="{{ 'cls_'.$question->id }}" data-name="ans[{{ $question->id }}]" data-type="{{ $question->type }}" class="nexttab btn btn-success">Next</button>--}}
{{--                                            @endif--}}
                                        </div>

                                    @endforeach
                                    <button type="submit" id="finishQuiz" class="btn btn-primary">Finish</button>
                                    {!! Form::close() !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </section>

@endsection

@push('scripts')
    <input type="hidden" value="{{ $course->id }}" name="lesson_id" id="lesson_id">
    <input type="hidden" value="{{ $chapter->id }}" name="chapter_id" id="chapter_id">
    <input type="hidden" id="addTime" value="{{ route('addTime') }}">
    <input type="hidden" id="previousMin" value="{{ $previousTime }}">
    <style>
        .show {
            opacity: 1;
        }
        .tab-pane.fade {
            display: none;
        }
        div#myTabContent h1 {
            color: #363636;
            font-size: 33px;
            font-weight: bold;
        }
        hr.titleHr {
            margin-top: 5px;
            width: 9%;
            float: left;
            border-top: 5px solid #363636 !important;
        }
        div.clear{
            clear: both !important;
        }
        ul.ansUl li {
            /*padding: 10px;*/
            /*border: 1px solid #eee;*/
            /*margin-bottom: 5px;*/
        }
        .quizQuestion {
            margin-bottom: 50px;
        }

        button.nexttab.btn.btn-success {
            float: right;
            width: 10%;
            font-weight: bold;
        }

        button.prevtab.btn.btn-success {
            width: 10%;
            font-weight: bold;
        }
        .errorBox .error{
            background: #e53939;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js" integrity="sha512-eYSzo+20ajZMRsjxB6L7eyqo5kuXuS2+wEbbOkpaur+sA2shQameiJiWEzCIDwJqaB0a4a6tCuEvCOBHUg3Skg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="{{ url('courseAssets/css/flipclock.css') }}">
    <script src="{{ url('courseAssets/js/flipclock.js') }}"></script>


    <script>
        let addTimeUrl = $("#addTime").val();
        let lesson_id = $("#lesson_id").val();
        let chapter_id = $("#chapter_id").val();
        var clock;

        $(document).ready(function() {

            clock = $('.clock').FlipClock({
                clockFace: 'HourlyCounter'
            });
            let preTime = parseInt($("#previousMin").val());
            clock.setTime(preTime);
        });
        document.addEventListener("visibilitychange", function() {
            if (document.hidden){
                clock.stop();
            } else {
                clock.start();
            }
        });

        // Auto-save study time every 60 seconds during quiz
        setInterval(function() {
            if (typeof clock !== 'undefined' && clock) {
                let time = parseInt(clock.getTime());
                $.ajax({
                    url: addTimeUrl,
                    method: 'POST',
                    data: {
                        lesson_id: lesson_id,
                        chapter_id: chapter_id,
                        time: time,
                        year: "{{ $year }}"
                    }
                });
            }
        }, 60000);

        let quizSubmitting = false;
        $("#quizForm").on("submit", function(e) {
            if (quizSubmitting) {
                return;
            }
            e.preventDefault();
            const form = this;
            const time = parseInt(clock.getTime());
            $("#study_time").val(time);
            $.ajax({
                url: addTimeUrl,
                method: 'POST',
                data: {
                    lesson_id: lesson_id,
                    chapter_id: chapter_id,
                    time: time,
                    year: "{{ $year }}"
                }
            }).always(function() {
                quizSubmitting = true;
                form.submit();
            });
        });

        //


        var validationMsg = "<div class='error'>Answer is required</div>";
        function bootstrapTabControl(){
            var i, items = $('.nav-link'), pane = $('.tab-pane');
            // next
            $('.nexttab').on('click', function(){
                for(i = 0; i < items.length; i++){
                    if($(items[i]).hasClass('active') == true){
                        break;
                    }
                }
                var allow = 0;
                type = $(this).attr('data-type');
                name = $(this).attr('data-name');
                classAttr = $(this).attr('data-class');
                if (type == 2){
//                    $('.'+classAttr+'input:checked')
                    if($('.'+classAttr+' input:checked').length){
                      allow = 1;
                    }else{
                        $('.errorBox').html(validationMsg);
                    }
                }
                if (type == 1){
                    if($('input[name="'+name+'"]:checked').length){
                        allow = 1;
                    }else{
                        $('.errorBox').html(validationMsg);
                    }
                }
                if (type == 3){
                        var text = $('textarea[name="'+name+'"]').val();
                        if(text == ''){
                            $('.errorBox').html(validationMsg);
                        }else{
                            allow = 1;
                        }
                }



                if (allow == 1){
                    $('.errorBox').html('');
                    if(i < items.length - 1){
                        // for tab
                        $(items[i]).removeClass('active');
                        $(items[i+1]).addClass('active');
                        // for pane
                        $(pane[i]).removeClass('show active');
                        $(pane[i+1]).addClass('show active');
                    }
                }

            });
            // Prev
            $('.prevtab').on('click', function(){
                for(i = 0; i < items.length; i++){
                    if($(items[i]).hasClass('active') == true){
                        break;
                    }
                }
                if(i != 0){
                    // for tab
                    $(items[i]).removeClass('active');
                    $(items[i-1]).addClass('active');
                    // for pane
                    $(pane[i]).removeClass('show active');
                    $(pane[i-1]).addClass('show active');
                }
            });
        }
        bootstrapTabControl();



    </script>
@endpush
