@extends('admin.layouts.app')

@section('title')
Profile
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <!-- ############ Main START-->
        <div class="padding">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::open(['method'=>'POST','class'=>'validate','files'=>true,'action'=>['Admin\AdminController@UpdateProfile']]) !!}
                    @include('layouts.partials.alertBox')
                    <input type="hidden" name="id" value="">

                        <div class="box">
                            <div class="box-header">
                                <h2>Update Your Profile</h2>
                            </div>
                            <div class="box-body">
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>First Name*</label>
                                        {{ Helper::input('first_name','First Name',$users->first_name,true,['id'=>'first_name'],null) }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Middle Name</label>
                                        {{ Helper::input('middle_name','Middle Name',$users->middle_name,false,['id'=>'first_name'],null) }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Last Name*</label>
                                        {{ Helper::input('last_name','Last Name',$users->last_name,true,['id'=>'first_name'],null) }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Email*</label>
                                        {{ Helper::input('email','Email',$users->email,true,['id'=>'email','readonly'],null) }}
                                    </div>
                                </div>


                                <div class="form-row">
                                    <div class="form-group col-sm-6">
                                        <label>Enter password</label>
                                        {{ Helper::input('new_password','New Password',null,false,['id'=>'new_password'],null,'password') }}
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Confirm password</label>
                                        {{ Helper::input('con_password','Confirm Password',null,false,['id'=>'con_password'],null,'password') }}

                                    </div>
                                </div>
                                <div class="text-right">
                                    {{ Form::submit('Update',['class'=>'btn btn-primary cstm_btn']) }}

                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>

            </div>

        </div>
        <!-- ############ Main END-->
    </div>


@endsection
