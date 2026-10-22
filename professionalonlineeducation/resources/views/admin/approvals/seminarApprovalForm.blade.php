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
                            <h2>Seminar Approvals</h2>
                        </div>

                        {!! Form::open(['method'=>'POST','class'=>'validate','files'=>true,'action'=>['EducationController@approvalSeminarSave']]) !!}
                        @if($seminarApproval->id)
                            <input type="hidden" value="{{ $seminarApproval->id }}" name="id">
                        @endif
                        <div class="box-body profileView">
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <label for="">Select Seminar</label>
                                    <select name="seminar_id" class="form-control">
                                        <!--<option selected disabled>Select Course</option>-->
                                        @foreach($seminars as $seminar)
                                            <option {{ ($seminarApproval->seminar_id == $seminar->id)?'selected':'' }} value="{{ $seminar->id }}">{{ $seminar->course_title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="">Select Approvals</label>
                                    <select required name="approval_id" class="form-control">
                                        <!--<option selected disabled>Select Approval</option>-->
                                        @foreach($approvals as $approval)
                                            <option {{ ($seminarApproval->approval_id == $approval->id)?'selected':'' }}  value="{{ $approval->id }}">{{ $approval->approval }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <label for="">Approval No.</label>
                                    <input type="text" required value="{{ $seminarApproval->approval_no }}" name="approval_no" class="form-control" />
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="">Provider No.</label>
                                    <input type="text" required value="{{ $seminarApproval->provider_no }}" name="provider_no" class="form-control" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    @if(count($states))
                                        <label>Select States*</label>
                                        <select multiple required name="state_ids[]" id="state" class="form-control">
                                            @foreach($states as $state)
                                                <option
                                                    {{ (in_array($state->id,Helper::getCourseApprovalStates($seminarApproval,'ids')))?'selected':'' }}
                                                    value="{{ $state->id }}">{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="form-group col-sm-6">
                                    @if(count($categories))
                                        <label>Select Categories* </label>
                                        <select required multiple name="category_ids[]" id="category" class="form-control">
                                            @foreach($categories as $category)
                                                <option
                                                    {{ (in_array($category->id,Helper::getCourseApprovalCategories($seminarApproval,'ids')))?'selected':'' }}
                                                    value="{{ $category->id }}">{{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <label for="">Start Date</label>
                                    <input type="date" required value="{{ $seminarApproval->start_date }}" name="start_date" class="form-control" />
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="">End Date</label>
                                    <input type="date" id="endDate" {{ ($seminarApproval->end_date === null)?'disabled':'required' }} value="{{ $seminarApproval->end_date }}" name="end_date" class="form-control" />
                                </div>
                                <div class="form-group col-sm-2">
                                    <br>
                                    <br>
                                    <label for="">Never Expired</label>

                                    <input type="checkbox"  {{ ($seminarApproval->end_date === null)?'checked':'' }}  id="neverExpired"  />
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
    </script>
@endpush
