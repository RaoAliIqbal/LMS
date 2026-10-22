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
                        {!! Form::open(['method'=>'POST','class'=>'validate','files'=>true,'action'=>['QuizController@exerciseQuestionSave']]) !!}
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
                                        <option {{ (isset($question->type)?($question->type == '4')?'selected':'':'') }} value="4">Match The Column</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row ansSection" style="display: none" id="one">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm addAns" data-number="{{ count($question->answers) }}" data-value="one">Add Answer</button>
                                    <hr>
                                </div>

                                <div class="col-md-12">
                                    <div class="ansCon">
                                        @if(count($question->answers))
                                            @php $i = 0; @endphp
                                            @foreach($question->answers as $answer)
                                                @php $i++; @endphp
                                                <div class="row">
                                                    <div class="form-group col-sm-6">
                                                        <label>Answers</label>
                                                        <input type="text" value="{{ $answer->answer }}" name="answers[{{ $i }}]" class="form-control ">
                                                    </div>
                                                    <div class="form-group col-sm-4" style="text-align: center">
                                                        <label >Select true Answer</label>
                                                        <input type="radio" value="{{ $i }}" {{ ($answer->is_true == 1)?'checked':'' }} name="trueAnswer" class="form-control"><br>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <br><br>
                                                        <button type="button" class="btn btn-danger btn-sm " onclick="removeAns(this)">Remove</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <div class="form-row ansSection" style="display: none" id="two">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm addAns"  data-number="{{ count($question->answers) }}" data-value="two">Add Answer</button>
                                    <hr>
                                </div>

                                <div class="col-md-12">
                                    <div class="ansCon">
                                        @if(count($question->answers))
                                            @php $i = 0; @endphp
                                            @foreach($question->answers as $answer)
                                                @php $i++; @endphp
                                                <div class="row">
                                                    <div class="form-group col-sm-6">
                                                        <label>Answers</label>
                                                        <input type="text" value="{{ $answer->answer }}" name="multi_answer[{{ $i }}]" class="form-control ">
                                                    </div>
                                                    <div class="form-group col-sm-4" style="text-align: center">
                                                        <label >True Answer</label>
                                                        <input type="checkbox" value="{{ $i }}" {{ ($answer->is_true == 1)?'checked':'' }} id="multiCheckBox-{{ $i }}" name="multiTrueAnswer[]" class="form-control"><br>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <br><br>
                                                        <button type="button" class="btn btn-danger btn-sm " onclick="removeAns(this)">Remove</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-row ansSection" style="display: none" id="three">
                                <div class="form-group col-sm-6">
                                    <label>Answers</label>
                                    <input value="{{ (isset($question->answers)?(count($question->answers) == 1)?$question->answers[0]->answer:'':'') }}" type="text" name="text_answer" class="form-control "><br>

                                </div>


                            </div>

                            <div class="form-row ansSection" style="display: none" id="four">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm addAns"  data-number="{{ count($question->answers) }}" data-value="four">Add Answer</button>
                                    <hr>
                                </div>

                                <div class="col-md-12">
                                    <div class="ansCon">
                                        @if(count($question->answers))
                                            @php $i = 0; @endphp
                                            @foreach($question->answers as $answer)
                                                @php $i++; @endphp
                                                <div class="row">
                                                    <div class="form-group col-sm-6">
                                                        <label>Answers</label>
                                                        <textarea name="match_answer[{{ $i }}]" class="form-control">{{ $answer->answer }}</textarea>
                                                    </div>
                                                    <div class="form-group col-sm-4" style="text-align: center">
                                                        <label >True Answer</label>
                                                        <textarea name="answer[{{ $i }}]" class="form-control">{{ $answer->answer_two }}</textarea>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <br><br>
                                                        <button type="button" class="btn btn-danger btn-sm " onclick="removeAns(this)">Remove</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
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


    <div style="display: none" id="row-for-one">
        <div class="row">
            <div class="form-group col-sm-6">
                <label>Answer</label>
                <textarea name="answers[no_goes_here]" id="[no_goes_here]" class="form-control"></textarea><br>
            </div>
            <div class="form-group col-sm-4" style="text-align: center">
                <label >Select true Answer</label>
                <input type="radio" value="no_goes_here" name="trueAnswer" class="form-control"><br>
            </div>
            <div class="col-sm-2">
                <br><br>
                <button type="button" class="btn btn-danger btn-sm " onclick="removeAns(this)">Remove</button>
            </div>
        </div>
    </div>

    <div style="display: none" id="row-for-two">
        <div class="row">
            <div class="form-group col-sm-6">
                <label>Answers</label>
                <textarea name="multi_answer[no_goes_here]" class="form-control"></textarea>
            </div>
            <div class="form-group col-sm-4" style="text-align: center">
                <label >True Answer</label>
                <input type="checkbox" value="no_goes_here" name="multiTrueAnswer[]" id="multiCheckBox-no_goes_here" class="form-control"><br>
            </div>
            <div class="col-sm-2">
                <br><br>
                <button type="button" class="btn btn-danger btn-sm " onclick="removeAns(this)">Remove</button>
            </div>
        </div>
    </div>


    <div style="display: none" id="row-for-four">
        <div class="row">
            <div class="form-group col-sm-6">
                <label>Answers</label>
                <textarea name="match_answer[no_goes_here]" class="form-control"></textarea>
            </div>
            <div class="form-group col-sm-4" style="text-align: center">
                <label >True Answer</label>
                <textarea name="answer[no_goes_here]" class="form-control"></textarea>
            </div>
            <div class="col-sm-2">
                <br><br>
                <button type="button" class="btn btn-danger btn-sm " onclick="removeAns(this)">Remove</button>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>

        $(".addAns").click(function (){
            //alert('a');
            let sectionName = $(this).attr('data-value');
            let ansNum = parseInt($(this).attr('data-number'));
            let html = $("#row-for-"+sectionName).html();
            let newAnsNumber = ansNum+1;
            $(this).attr('data-number',newAnsNumber);
            let newHtml = html.replace(/no_goes_here/g, newAnsNumber)
            $("#"+sectionName+" .ansCon").append(newHtml);
        });
        function removeAns(t){
            var ele = $(t);
            ele.parent().parent().remove();
        }

        $(document).ready(function () {
            settings();
        });
        $("#type").change(function () {
            settings();
        });
        $('input[type="submit"]').on('click', function() {
            //
            $cbx_group = $("input:checkbox[id^='multiCheckBox-']");

//            $cbx_group.prop('required', true);
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
            }else if(type == '4'){
                $("#four").show();
                // $("#three input").prop('required',true);
            }else{

            }
        }
    </script>
@endpush
