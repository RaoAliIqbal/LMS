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
                            {!! Form::open(['method'=>'POST','class'=>'validate','files'=>true,'action'=>['QuizController@questionSave']]) !!}
                           @if($pageData['type'] == 'edit')
                                <input type="hidden" value="{{ $question->id }}" name="id">
                            @endif
                            <input type="hidden" value="{{ $id }}" name="quiz_id">
                            <div class="box-body profileView">

                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Question*</label>
                                        <textarea name="question" class="html">{{ isset($question->question)?$question->question:'' }}</textarea>
                                    </div>
                                </div>


                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Question mark*</label>
                                        {{ Helper::input('mark','Title',isset($question->mark)?$question->mark:'',true,['id'=>'title'],null,'number') }}
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Question Type</label>
                                        <select id="type" name="type" class="form-control">
                                            <option {{ (isset($question->type)?($question->type == '1')?'selected':'':'') }} value="1">Select One Option</option>
                                            <option {{ (isset($question->type)?($question->type == '2')?'selected':'':'') }} value="2">Select Multiple Options</option>
                                            <option {{ (isset($question->type)?($question->type == '3')?'selected':'':'') }} value="3">Enter Text Value</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row ansSection" style="display: none" id="one">
                                    <div class="form-group col-sm-6">
                                        <label>Answers</label>

                                        <input value="{{ (isset($question)?(count($question->answers) == 3)?$question->answers[0]->answer:'':'') }}"  type="text" name="answer[1]" class="form-control"><br>
                                        <input value="{{ (isset($question)?(count($question->answers) == 3)?$question->answers[1]->answer:'':'') }}"  type="text" name="answer[2]" class="form-control"><br>
                                        <input value="{{ (isset($question)?(count($question->answers) == 3)?$question->answers[2]->answer:'':'') }}"  type="text" name="answer[3]" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-6" style="text-align: center">
                                        <label >True Answer</label>
                                        <input type="radio" value="1" {{ (isset($question)?(count($question->answers) == 3)?($question->answers[0]->is_true == 1)?'checked':'':'':'') }} name="trueAnswer" class="form-control"><br>
                                        <input type="radio" value="2" {{ (isset($question)?(count($question->answers) == 3)?($question->answers[1]->is_true == 1)?'checked':'':'':'') }} name="trueAnswer" class="form-control"><br>
                                        <input type="radio" value="3" {{ (isset($question)?(count($question->answers) == 3)?($question->answers[2]->is_true == 1)?'checked':'':'':'') }} name="trueAnswer" class="form-control">
                                    </div>


                                </div>

                                <div class="form-row ansSection" style="display: none" id="two">
                                    <div class="form-group col-sm-6">
                                        <label>Answers</label>
                                        <input value="{{ (isset($question)?(count($question->answers) == 3)?$question->answers[0]->answer:'':'') }}" type="text" name="multi_answer[1]" class="form-control"><br>
                                        <input value="{{ (isset($question)?(count($question->answers) == 3)?$question->answers[1]->answer:'':'') }}" type="text" name="multi_answer[2]" class="form-control"><br>
                                        <input value="{{ (isset($question)?(count($question->answers) == 3)?$question->answers[2]->answer:'':'') }}" type="text" name="multi_answer[3]" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-6" style="text-align: center">
                                        <label >True Answer</label>
                                        <input type="checkbox" value="1" {{ (isset($question)?(count($question->answers) == 3)?($question->answers[0]->is_true == 1)?'checked':'':'':'') }} id="multiCheckBox-1" name="multiTrueAnswer[]" class="form-control"><br>
                                        <input type="checkbox" value="2" {{ (isset($question)?(count($question->answers) == 3)?($question->answers[1]->is_true == 1)?'checked':'':'':'') }} id="multiCheckBox-2" name="multiTrueAnswer[]" class="form-control"><br>
                                        <input type="checkbox" value="3" {{ (isset($question)?(count($question->answers) == 3)?($question->answers[2]->is_true == 1)?'checked':'':'':'') }} id="multiCheckBox-3" name="multiTrueAnswer[]" class="form-control">
                                    </div>


                                </div>

                                <div class="form-row ansSection" style="display: none" id="three">
                                    <div class="form-group col-sm-6">
                                        <label>Answers</label>
                                        <input value="{{ (isset($question)?(count($question->answers) == 1)?$question->answers[0]->answer:'':'') }}" type="text" name="text_answer" class="form-control"><br>

                                    </div>


                                </div>

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


@endsection

@push('scripts')
    <script>

        $(document).ready(function () {
            settings();
        });
        $("#type").change(function () {
            settings();
        });
        $('input[type="submit"]').on('click', function() {
            //
            $cbx_group = $("input:checkbox[id^='multiCheckBox-']");

            $cbx_group.prop('required', true);
            if($cbx_group.is(":checked")){
                $cbx_group.prop('required', false);
            }
        });
        function settings() {
            var type = $("#type").val();
            $(".ansSection input").prop('required',false);
            $(".ansSection").hide();
            if (type == '1'){
                $("#one").show();
                // $("#one input").prop('required',true);
            }else if(type == '2'){
                $("#two").show();
                // $("#two input").prop('required',true);
            }else if(type == '3'){
                $("#three").show();
                // $("#three input").prop('required',true);
            }else{

            }
        }
    </script>
@endpush
