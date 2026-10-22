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
                            <h2>Add Instructor Course</h2>
                            <form action="{{route('admin.instructor_course.store')}}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-5">
                                        <label for="">Instructors</label>
                                        <select class="form-control dynamicSelect select2Dynamic" multiple name="instructor_id[]" required>
                                            @foreach($instructors AS $instructor)
                                                <option value="{{ $instructor->id }}">{{ $instructor->first_name }} {{ $instructor->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Courses</label>
                                        <select class="form-control dynamicSelect select2Dynamic" multiple name="course_id" required>
                                            @foreach($courses AS $course)
                                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="submit" class="btn btn-primary mt-4 ml-5" value="Assign course">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                            </div>
                        </div>
                    </div>
                        <div class="box">
                            <div class="box-header">
                                <h2>Instructors courses list</h2>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Course</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @php
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
                                            @endforeach
                                            {{-- @foreach($instructors AS $intstructor)
                                            
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $intstructor->first_name }}</td>
                                                    <td>{{ $intstructor->last_name }}</td>
                                                    <td>{{ $intstructor->email }}</td>
                                                    <td>{{ $intstructor->phone_no }}</td>
                                                    <td>
                                                        @if($intstructor->status == 1)
                                                            <span class="badge badge-success">Active</span>
                                                        @else 
                                                            <span class="badge badge-danger">Deactive</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ date('d-M-Y', strtotime($intstructor->created_at)) }}</td>
                                                    <td>
                                                        @if(file_exists($intstructor->resume))
                                                            <a href="{{ asset($intstructor->resume) }}" class="btn btn-primary" download>Download</a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn white dropdown-toggle" data-toggle="dropdown"><i
                                                                        class="fa fa-bars"></i></button>

                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.instructors.edit', ['id'=>base64_encode($intstructor->id)]) }}">Update Instructor
                                                                </a>
                                                                @if($intstructor->status == 1)
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.instructors.update_status', ['id'=>base64_encode($intstructor->id), 'status'=>0]) }}">Deactive
                                                                    </a>
                                                                @else 
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.instructors.update_status', ['id'=>base64_encode($intstructor->id), 'status'=>1]) }}">Active
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
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
