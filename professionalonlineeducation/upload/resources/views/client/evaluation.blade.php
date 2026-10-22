@extends('layouts.web')

@section('content')
<div class="container">
    <section class="page_sidepadding page_sidepadding_home">
        <div class="dashboard_page">

            <div class="dashboard_row">
                <div class="row">
                    <div class="col-lg-12">
                        @include('layouts.partials.alertBox')
                        <div class="">
                            <div class="row courseTitleCon">
                                <div class="col-md-12">
                                    <h4>{{ $evaluation->title }}</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="ChapterDescription">
                                        <form action="{{  route('saveEval') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                                            <input type="hidden" name="evaluation_id" value="{{ $evaluation->id }}">
                                            <input type="hidden" name="year" value="{{ $year }}">
                                            @php
                                                $questions = json_decode($evaluation->questions);
                                            @endphp
                                            
                                            @foreach($questions as $key=>$question)
                                                <div class="form-group">
                                                    <label for="">{{ $question->question }}</label>
                                                   @if($question->type == 1)
                                                    <div class="evalRadios">
                                                       <div class="radio">
                                                           <label><input type="radio" onclick="removeBox(this)" name="{{ $question->question }}" value="strongly_agree">Strongly Agree</label>
                                                       </div>
                                                       <div class="radio">
                                                           <label><input type="radio" onclick="removeBox(this)" name="{{ $question->question }}" value="agree">Agree</label>
                                                       </div>
                                                       <div class="radio">
                                                           <label><input type="radio" onclick="removeBox(this)" name="{{ $question->question }}" value="disagree">Disagree</label>
                                                       </div>
                                                       <div class="radio">
                                                           <label><input type="radio" onclick="removeBox(this)" name="{{ $question->question }}" value="strongly_disagree">Strongly Disagree</label>
                                                       </div>
                                                       <div class="radio">
                                                           <label><input type="radio" onclick="AddBox(this)" name="{{ $question->question }}" value="na">NA</label>
                                                       </div>
                                                   </div>
                                                    @elseif($question->type == 3)
                                                        <div class="">
                                                            <label for="">Comments</label>
                                                            <textarea rows="50" name="{{ $question->question }}" class="form-control" placeholder="Type Your Comments..."></textarea>
                                                        </div>
                                                    @else
                                                        <div class="evalRadios YesNo">
                                                            <div class="radio">
                                                                <label><input type="radio" onclick="removeBox(this)" name="{{ $question->question }}"  value="yes">Yes</label>
                                                            </div>
                                                            <div class="radio">
                                                                <label><input type="radio" onclick="AddBox(this)" name="{{ $question->question }}" value="no">No</label>
                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                            <input type="submit" class="btn btn-primary rightBtn" value="Next" />
                                        </form>
                                    </div>
                                </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        @if(isset($eligible_for_discount))
            Swal.fire({
            title: "Congratulation!",
            text: "Save 12.5% off your next {{ $course_type }} course (use coupon code G2f7eT).",
            icon: "success"
            });

        @endif
        function AddBox(e){
            // let ele = $(e);
            // let name = ele.attr('name');
            // let textarea = '<textarea name="'+name+'" style="width: 50%;height: 150px" placeholder="Please type your comments" class="form-control"></textarea>';
            // ele.parent().parent().parent().parent().append(textarea);
        }
        function removeBox(e){
            // let ele = $(e);
            // ele.parent().parent().parent().parent().find('textarea').remove();
        }

    </script>
    <style>
.rightBtn{float: right}
        .courseTitleCon {
            text-align: center;
            background: #1580ce;
            color: #fff;
            border-radius: 5px;
            margin: 20px 0;
        }
        .evalRadios {
            display: flex;
            flex-flow: row;
            justify-content: space-between;
            width: 80%;
        }

        .evalRadios .radio {
            margin-top: 10px !important;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .cart_block {
            display: none;
        }
.evalRadios.YesNo {
    justify-content: flex-start;
}

.evalRadios.YesNo .radio {
    margin-right: 30px;
}
    </style>
@endpush
