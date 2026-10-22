@extends('admin.layouts.app')
@section('title')
    Dashboard
@endsection
@section('content')

    <div class="content-main " id="content-main">
        <!-- ############ Main START-->
        <div class="padding">
            <div class="row">
                <div class="col-md-2 box-blue">
                    <h2>{{ $clients }}</h2>
                    <h3>Users</h3>
                    <a href="{{ route('admin_client_view') }}">View</a>
                </div>
                <div class="col-md-2 box-blue">
                    <h2>{{ $courses }}</h2>
                    <h3>Courses</h3>
                    <a href="{{ route('admin_lessons') }}">View</a>
                </div>


            </div>
        </div>
    </div>

@endsection
