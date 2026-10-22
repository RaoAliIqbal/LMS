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
                            {!! Form::open(['method'=>'POST','class'=>'validate','files'=>true,'action'=>['EvaluationController@save']]) !!}
                           @if($pageData['type'] == 'edit')
                                <input type="hidden" value="{{ $evaluation->id }}" name="id">
                            @endif
                            <div class="box-body profileView">
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Evaluation Title*</label>
                                        {{ Helper::input('title','Title',isset($evaluation->title)?$evaluation->title:'',true,['id'=>'title'],null) }}
                                    </div>
                                </div>

                                <ul class="questionList">

                                    @if($evaluation->questions)
                                        @foreach($evaluation->questions as $question)

                                            <li>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="">Question</label>
                                                        <textarea name="question[]" class="form-control">{{ $question->question }}</textarea>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <select name="type[]" id="" class="form-control">
                                                            <option {{ ($question->type == 1)?'selected':'' }} value="1">Agree/Disagree</option>
                                                            <option {{ ($question->type == 2)?'selected':'' }} value="2">Yes/No</option>
                                                            <option {{ ($question->type == 3)?'selected':'' }} value="3">Text Area</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button class="btn btn-sm btn-danger" onclick="removeLi(this)">Remove</button>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif

                                </ul>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="button" class="btn-primary  btn btn-sm cstm_btn" id="AddQuestion">Add Question</button>
                                    </div>
                                </div>
                                <br>
                                <hr>"
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
        $("#AddQuestion").click(function (){
            var html = '<li> <div class="row"> <div class="form-group col-md-6"><label for="">Question</label> <textarea name="question[]" class="form-control"></textarea> </div>'+
                        '<div class="col-md-2"><select name="type[]" id="" class="form-control"><option value="1">Agree/Disagree</option><option value="2">Yes/No</option><option value="3">Text Area</option> </select> </div>'+
                        '<div class="col-md-2"> <button class="btn btn-sm btn-danger" onclick="removeLi(this)">Remove</button> </div> </div> </li>';
            $(".questionList").append(html);
            $('select').selectpicker('deselectAll');

        });
        function removeLi(t){
            let ele = $(t);
            ele.parent().parent().parent().remove();
        }
    </script>

    <style>
        ul.questionList {
            list-style: none;
            padding: 0;
            /* width: 50%; */
            margin-top: 21px;
            margin-bottom: 20px;
        }
        ul.questionList li {
            border: 1px solid #efefef;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            position: relative;
        }
        ul.questionList li:hover {
            border-color: #323232;
        }
        /*ul.questionList li span {*/
        /*    position: absolute;*/
        /*    top: -11px;*/
        /*    left: -5px;*/
        /*    background: #fb0000;*/
        /*    color: #fff;*/
        /*    line-height: 1;*/
        /*    padding: 3px 4px;*/
        /*    border-radius: 50px;*/
        /*    font-family: cursive;*/
        /*    cursor: pointer;*/
        /*}*/
        ul.questionList button {
            margin-top: 40px;
        }
    </style>
@endpush
