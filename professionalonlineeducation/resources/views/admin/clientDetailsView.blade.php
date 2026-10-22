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
                            <div class="box-body profileView">
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Name*</label>
                                        <p class="DetailValue">{{ $client->name }}</p>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>City*</label>
                                        <p class="DetailValue">{{ $client->city }}</p>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Zipcode*</label>
                                        <p class="DetailValue">{{ $client->zipcode }}</p>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Phone No*</label>
                                        <p class="DetailValue">{{ $client->phone }}</p>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>State*</label>
                                        <p class="DetailValue">{{ $client->state }}</p>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Clinic Name*</label>
                                        <p class="DetailValue">{{ $client->clinic_name }}</p>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Doctor Name*</label>
                                        <p class="DetailValue">{{ $client->doctor_name }}</p>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Email*</label>
                                        <p class="DetailValue">{{ $client->email }}</p>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Address</label>
                                        <p class="DetailValue">{{ $client->address }}</p>
                                    </div>
                                </div> <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Password</label>
                                        <p class="DetailValue">{{ $client->real_password }}</p>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-sm-6">
                                        <label>Created At</label>
                                        <p class="DetailValue">{{ $client->created_at }}</p>
                                    </div>
                                </div>



                            </div>

                        </div>

                </div>

            </div>

            <br>


        </div>
        <!-- ############ Main END-->
    </div>


@endsection
