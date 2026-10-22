@extends('admin.layouts.app')

@section('title')
    Instructors
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <div class="padding">
            <div class="row">
                <div class="col-md-12">
                    @include('layouts.partials.alertBox')
                        <div class="box">
                            <div class="box-header">
                                <h2>Instructors User courses list</h2>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Instructor</th>
                                            <th>User</th>
                                            <th>Course</th>
                                            <th>Price</th>
                                            <th>Commission %</th>
                                            <th>Commission<br/>Amount</th>
                                            <th>Discount</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($instructor_users AS $instructor_user)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $instructor_user->instructor->first_name }} {{ $instructor_user->instructor->last_name }}</td>
                                                    <td>{{ $instructor_user->user->first_name }}  {{ $instructor_user->user->last_name }}</td>
                                                    <td>{{ $instructor_user->course->title }}</td>
                                                    <td>{{ $instructor_user->course_price }}</td>
                                                    <td>{{ $instructor_user->percentage }}</td>
                                                    <td>{{ ($instructor_user->percentage > 0) ? round(((($instructor_user->course_price-$instructor_user->discount)*$instructor_user->percentage)/100))  : 0 }}</td>

                                                    <td>{{ $instructor_user->discount }}</td>
                                                    <td>
                                                        @if($instructor_user->status)
                                                            <span class="badge badge-success">verified</span>
                                                        @else 
                                                            <span class="badge badge-danger">pending</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            {{-- @php
                                                $iteration = 0;    
                                            @endphp
                                            @foreach($instructors AS $instructor_courses)
                                                @if($instructor_courses->courses->isNotEmpty())
                                                    @foreach($instructor_courses->courses AS $courses)
                                                        <tr>
                                                            <td>{{ ++$iteration }}</td>
                                                            <td>{{ $instructor_courses->first_name }}</td>
                                                            <td>{{ $instructor_courses->last_name }}</td>
                                                            <td>{{ $courses->title }}</td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button class="btn white dropdown-toggle" data-toggle="dropdown"><i
                                                                                class="fa fa-bars"></i></button>
        
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item" href="{{ route('admin.instructor_course.delete',['instructor_id'=>$instructor_courses->id,'course_id'=>$courses->id]) }}">Delete course
                                                                        </a>
                                                                        <a class="dropdown-item" href="{{ route('admin_edit_lessons',['id'=>$courses->id]) }}">Edit course
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            @endforeach --}}
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


@endsection
@push('scripts')
    <script>
        $('.select2Dynamic').select2({
            placeholder: 'Please select',
        });
    </script>
@endpush
