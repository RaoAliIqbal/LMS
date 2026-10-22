@extends('admin.layouts.app')

@section('title')
  Course Approval
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
                            <h2>Course Approvals</h2>
                        </div>

                        {!! Form::open(['method'=>'POST','class'=>'validate','files'=>true,'action'=>['EducationController@approvalCourseSave']]) !!}
                        @if($courseApproval->id)
                            <input type="hidden" value="{{ $courseApproval->id }}" name="id">
                        @endif
                        <div class="box-body profileView">
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <label for="">Select Course</label>
                                    <select name="course_id" class="form-control">
                                        @foreach($courses as $course)
                                            <option {{ ($courseApproval->lesson_id == $course->id)?'selected':'' }} value="{{ $course->id }}">{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="">Select Approvals</label>
                                    <select required name="approval_id" class="form-control">
                                        <!--<option selected disabled>Select Approval</option>-->
                                        @foreach($approvals as $approval)
                                            <option {{ ($courseApproval->approval_id == $approval->id)?'selected':'' }}  value="{{ $approval->id }}">{{ $approval->approval }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <label for="">Approval No.</label>
                                    <input type="text" required value="{{ $courseApproval->approval_no }}" name="approval_no" class="form-control" />
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="">Provider No.</label>
                                    <input type="text" required value="{{ $courseApproval->provider_no }}" name="provider_no" class="form-control" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    @if(count($states))
                                        <label>Select States*</label>
{{--                                        <label class="pull-right">--}}
{{--                                            Select All--}}
{{--                                            <input data-listId="state" class="selectAll" type="checkbox" >--}}
{{--                                        </label>--}}
                                        <select multiple required name="state_ids[]" id="state" class="form-control">
                                            @foreach($states as $state)
                                                <option
                                                    {{ (in_array($state->id,Helper::getCourseApprovalStates($courseApproval,'ids')))?'selected':'' }}
                                                    value="{{ $state->id }}">{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="form-group col-sm-6">
                                    @if(count($categories))
                                        <label>Select Categories* </label>
{{--                                        <label class="pull-right">--}}
{{--                                            Select All--}}
{{--                                            <input data-listId="category" class="selectAll" type="checkbox" >--}}
{{--                                        </label>--}}

                                        <select required multiple name="category_ids[]" id="category" class="form-control">
                                            @foreach($categories as $category)
                                                <option
                                                    {{ (in_array($category->id,Helper::getCourseApprovalCategories($courseApproval,'ids')))?'selected':'' }}
                                                    value="{{ $category->id }}">{{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <label for="">Start Date</label>
                                    <input type="date" required value="{{ $courseApproval->start_date }}" name="start_date" class="form-control" />
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="">End Date</label>
                                    <input type="date" id="endDate" {{ ($courseApproval->end_date === null)?'disabled':'required' }} value="{{ $courseApproval->end_date }}" name="end_date" class="form-control" />
                                </div>
                                <div class="form-group col-sm-2">
                                    <br>
                                    <br>
                                    <label for="">Never Expired</label>

                                    <input type="checkbox"  {{ ($courseApproval->end_date === null)?'checked':'' }}  id="neverExpired"  />
                                </div>
                            </div>
                            <br>
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
        $(".selectAll").change(function (){
            let ele = $(this);
            let listId = ele.attr('data-listId');
            let isChecked = $(this).is(':checked');
            $('#'+listId+' option').prop('selected', isChecked);
            $('select').selectpicker();
        });
        $('select').change(function (){
            $(this).prev().find('.selectAll').prop('checked',false);
            $('select').selectpicker();
        });
        $("#neverExpired").change(function () {
            let endDate = $("#endDate");
            if ($('#neverExpired').is(':checked')) {
                endDate.prop('disabled',   true);
                endDate.prop('required',   false);
            }else{
                endDate.prop('disabled',   false);
                endDate.prop('required',   true);
            }
        });

        $(document).ready(function() {
            $('#state').select2();
            $('select[name="course_id"]').select2();
        });
    </script>
@endpush
