@extends('admin.layouts.app')

@section('title')
{{ $client->name }}
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
                <div class="col-md-4">



                        <div class="box">
                            <div class="box-header">
                                <h2>{{ $client->name }}'s Details</h2>
                            </div>
                            {!! Form::open(['method'=>'POST','class'=>'validate','files'=>true,'action'=>['Admin\AdminController@UpdateUser']]) !!}
                            <input type="hidden"  value="{{ $client->id }}" name="id">

                            <div class="box-body profileView">
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Name*</label>
                                        {{ Helper::input('name','Name',$client->name,true,['id'=>'first_name'],null) }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>City*</label>
                                        {{ Helper::input('city','City',$client->city,true,['id'=>'city'],null) }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Zipcode*</label>
                                        {{ Helper::input('zipcode','Zipcode',$client->zipcode,true,['id'=>'zipcode'],null) }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Phone No*</label>
                                        {{ Helper::input('phone','Phone',$client->phone,true,['id'=>'phone'],null) }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>State*</label>
                                        <select name="state" class="form-control">
                                            @foreach($states as $state)
                                                <option {{ ($state->name == $client->state)?'selected':'' }} value="{{ $state->name }}">{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Clinic Name*</label>
                                        {{ Helper::input('clinic_name','Clinic Name',$client->clinic_name,true,['id'=>'clinic_name'],null) }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Doctor Name*</label>
                                        {{ Helper::input('doctor_name','Doctor Name',$client->doctor_name,true,['id'=>'doctor_name'],null) }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Email*</label>
                                        {{ Helper::input('email','Email',$client->email,true,['id'=>'email'],null) }}
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Address</label>
                                        <textarea class="form-control" name="address">{{ $client->address }}</textarea>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-sm-6">
                                        <label>Created At</label>
                                        <p class="DetailValue">{{ $client->created_at }}</p>
                                    </div>
                                </div>

                                <div class="text-right">
                                    {{ Form::submit('Update',['class'=>'btn btn-primary cstm_btn']) }}
                                </div>

                            </div>
                            {!! Form::close() !!}
                        </div>

                </div>
                <div class="col-md-4">
                    {!! Form::open(['method'=>'POST','class'=>'validate','files'=>true,'action'=>['Admin\AdminController@UpdateUser']]) !!}

                    <input type="hidden" name="id" value="{{ $client->id }}">

                    <div class="box">
                        <div class="box-header">
                            <h2>Update User Status</h2>
                        </div>
                        <div class="box-body">

                            <div class="form-row">
                                <div class="form-group col-sm-12">
                                    <label>Current Status</label>
                                    <p class="DetailValue">{{ config('settings.userStatus.'.$client->status) }}</p>
                                </div>

                            </div>

                            <div class="form-row">

                                <div class="form-group col-sm-12">


                                    <label>Change Status</label>
                                    <select name="status" class="form-control">
                                        <option {{ $client->status == '0'?'selected':'' }} value="1">Activate</option>
                                        <option {{ $client->status == '1'?'selected':'' }} value="0">Deactivate</option>
                                    </select>
                                   </div>

                            </div>
                            <div class="text-right">
                                {{ Form::submit('Update',['class'=>'btn btn-primary cstm_btn']) }}

                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="col-md-4">
                    {!! Form::open(['method'=>'POST','class'=>'validate','files'=>true,'action'=>['Admin\AdminController@UpdateUserPassword']]) !!}

                    <input type="hidden" name="id" value="{{ $client->id }}">

                    <div class="box">
                        <div class="box-header">
                            <h2>Update Password</h2>
                        </div>
                        <div class="box-body">

                            <div class="form-row">
                                <div class="form-group col-sm-12">
                                    <label>Enter password</label>
                                    {{ Helper::input('new_password','New Password',null,false,['id'=>'new_password'],null,'password') }}
                                </div>
                                <div class="form-group col-sm-12">
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

            <br>


        </div>
        <!-- ############ Main END-->
    </div>


@endsection
