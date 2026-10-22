@extends('admin.layouts.app')

@section('title')
{{ $pageData['name'] }}
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <!-- ############ Main START-->
        <div class="padding">
            <div class="row">
                <div class="col-md-12">
                    @include('layouts.partials.alertBox')
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">



                        <div class="box">
                            <div class="box-header">
                                <h2>{{ $pageData['name']  }}</h2>
                            </div>
                            {!! Form::open(['method'=>'POST','class'=>'validate','files'=>true,'action'=>['EducationController@chapSave']]) !!}
                           @if($pageData['type'] == 'edit')
                                <input type="hidden" value="{{ $chapter->id }}" name="id">
                            @endif
                            <input type="hidden" value="{{ $lesson->id }}" name="lesson_id">


                            <div class="box-body profileView">

                                <div class="form-row" style="display: none">
                                    <div class="form-group col-sm-12">
                                        <div class="radio">
                                            <label><input type="radio" name="chapType" value="main" id="mainChap" checked>Main Chapter</label>
                                        </div>
                                        @if(count($mainChapters))
                                        <div class="radio">
                                            <label><input type="radio" name="chapType" value="sub" id="subChap">Sub Chapter</label>
                                        </div>
                                         @endif
                                    </div>
                                </div>
                                <div style="display: none" class="form-row sub">
                                    <div class="form-group col-sm-12">
                                        <label>Main Chapter*</label>
                                        <select name="parent_id" id="parent_id" class="form-control">
                                            @foreach($mainChapters as $mainChapter)
                                                <option data-no="{{ $mainChapter->chapter_no }}" {{ ($pageData['type'] == 'edit')?($mainChapter->id == $chapter->parent_id)?'selected':'':'' }} value="{{ $mainChapter->id }}">{{ $mainChapter->chapter_no }} -- {{ $mainChapter->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


{{--                                <div class="form-row both">--}}
{{--                                    <div class="form-group col-sm-12">--}}
{{--                                        <label>Chapter No*</label>--}}
{{--                                       <div class="chapNoCon" >--}}
{{--                                           <div class="chapNo"></div>--}}
{{--                                           <div class="chapInput">--}}
{{--                                               {{ Helper::input('chapter_no','Chapter No',isset($chapter->chapter_no)?$chapter->chapter_no:'',true,['id'=>'title'],null,'number') }}--}}
{{--                                           </div>--}}
{{--                                       </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="form-row both">
                                    <div class="form-group col-sm-12">
                                        <label>Title*</label>
                                        <input type="title" name="title" value="{{ isset($chapter->title)?$chapter->title:'' }}" onchange="saveChapter()" required id="title" class="form-control ChapterTitle">
                                    </div>
                                </div>
                                <div class="form-row both">
                                    <div class="form-group col-sm-12">
                                        <label>Description*</label>
                                        <textarea id="description" onchange="saveChapter()" name="description" class="html">{{ isset($chapter->description)?$chapter->description:'' }}</textarea>
                                    </div>
                                </div>



                                    <div class="form-row both">
{{--                                    <div class="form-group col-sm-6">--}}
{{--                                        <label>Select Exercise</label>--}}
{{--                                        <select onchange="saveChapter()" id="exercise_id" name="exercise_id" class="form-control">--}}
{{--                                            <option selected disabled>Select Exercise</option>--}}
{{--                                            @foreach($exercise as $item)--}}
{{--                                                <option {{ ($chapter->exercise_id == $item->id)?'selected':'' }} value="{{ $item->id }}">{{ $item->title }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
                                        <div class="form-group col-sm-12">
                                            <label>Select Quiz</label>
                                            <select onchange="saveChapter()" id="quiz_id" name="quiz_id" class="form-control">
                                                @if(count($quiz))
                                                    <option selected disabled>Select Quiz</option>
                                                    @foreach($quiz as $item)
                                                        <option {{ ($chapter->quiz_id == $item->id)?'selected':'' }} value="{{ $item->id }}">{{ $item->title }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                </div>


                                @include('admin.lesson.topics')
                                <div class="text-right">
                                    {{ Form::submit('Save',['class'=>'btn btn-primary cstm_btn']) }}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>

                </div>

            </div>

            <br>


        </div>
        <!-- ############ Main END-->
    </div>
    <style>
        .radio input {
            margin-right: 10px;
        }
        .chapInput, .chapNo {
            display: inline-block;
        }
    </style>


@endsection

@push('scripts')
    <script>

       $("#AddTopic").click(function (){
            $("#topicModal").modal('show');
       });
        $('#mainChap').click(function(){
             if ($(this).is(':checked')) {
             $(".sub").css('display','none');
                 $(".chapNo").html('');
             };

        });
       $('#subChap').click(function(){
           if ($(this).is(':checked')) {
               $(".sub").css('display','block');
               addChapNo();
           };

       });
       function addChapNo() {
           var no = $('#parent_id').find(':selected').attr('data-no');
            $(".chapNo").html(no+'.');
       }
       $("#parent_id").change(function () {
           addChapNo();
       })
    </script>
@endpush
