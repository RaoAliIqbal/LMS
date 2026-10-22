@extends('admin.layouts.app')

@section('title')
  Approval
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
                            <h2>Approval</h2>
                        </div>
                        {!! Form::open(['method'=>'POST','class'=>'validate','files'=>true,'action'=>['EducationController@approvalSave']]) !!}
                        @if($approval->id)
                            <input type="hidden" value="{{ $approval->id }}" name="id">
                        @endif
                        <div class="box-body profileView">
                            <div class="form-row">
                                <div class="form-group col-sm-12">
                                    <label>Title*</label>
                                    {{ Helper::input('title','Title',isset($approval->approval)?$approval->approval:'',true,['id'=>'title'],null) }}
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

@endpush
