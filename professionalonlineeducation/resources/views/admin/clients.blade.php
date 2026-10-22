@extends('admin.layouts.app')

@section('title')
    Customers
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <!-- ############ Main START-->
        <div class="padding">
            @if(isset($lessons))
                <div class="row">
                    <div class="col-md-6 searchBox">
                        <h5>Search By Courses</h5>
                        @if(count($lessons))
                        <form action="">
                            <div class="row">
                                <div class="col-md-12 " style="display: flex">
                                    <select name="course_id" class="form-control" id="">
                                        <option value="" selected disabled>Select Course</option>
                                        @foreach($lessons as $lesson)
                                            <option {{ ($lesson->id == $request->course_id) ? 'selected' : '' }} value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                                        @endforeach
                                    </select>
                                    <input type="submit" class="btn btn-primary btn-sm" value="Search">
                                    <a style="margin-left: 5px" href="{{ route('admin_client_view') }}" class="btn btn-warning btn-sm">Reset</a>
                                </div>

                            </div>
                        </form>
                        @else
                            <div class="alert alert-warning">Course list could not be loaded. Please refresh or call 1-888-900-3382.</div>
                        @endif
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    @include('layouts.partials.alertBox')
                        <div class="box">
                            <div class="box-header">
                                <h2>{{ $pageData['title'] }}</h2>
                                <a href="{{ route('admin_create_client') }}" class="btn btn-success btn-sm pull-right" style="margin-top: -4px;">+ Add Student + Course</a>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>User Status</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($clients as $client)
                                                <tr>
                                                    <td>{{ $client->FullName }} </td>
                                                    <td>{{ $client->email }}</td>
                                                    <td>{{ ($client->status == 0)?'Deactivated':'Activated' }}</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn white dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i></button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="{{ route('admin_client',$client->id) }}">Update Details</a>
                                                                <a class="dropdown-item" href="{{ route('admin_clientDetails',$client->id) }}">View Details</a>
                                                                <a class="dropdown-item" href="{{ route('admin_clientCourses',$client->id) }}">Enrolled Courses</a>
                                                                <a class="dropdown-item" href="{{ route('admin_clientPaymentLogs',$client->id) }}">Payment History</a>
                                                                <a class="dropdown-item" href="{{ route('admin_clientLicences',$client->id) }}">Licences</a>
                                                                <a class="dropdown-item" href="{{ route('admin_deleteUser',$client->id) }}">Delete User</a>
                                                                @if($client->status != '0')
                                                                    <a class="dropdown-item" href="{{ route('admin_clientDeactivate',$client->id) }}">Deactivate User</a>
                                                                @endif
                                                                @if($client->status == '0')
                                                                    <a class="dropdown-item" href="{{ route('admin_clientActivate',$client->id) }}">Activate User</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                </div>

            </div>

        </div>
        <!-- ############ Main END-->
    </div>
    <style>
        .searchBox {
            background: #fff;
            padding: 10px;
            margin-left: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
    </style>

@endsection
