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
                                <a href="{{ route('admin.instructors.add') }}" class="btn btn-primary mt-1 cstmBtn rightALign">Add New Instructor</a>

                                <h2>Instructors List</h2>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Phone No</th>
                                            <th>Status</th>
                                            <th>Created at</th>
                                            <th>Download Resume</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($instructors AS $intstructor)
                                            
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
                                                                {{-- <a class="dropdown-item"
                                                                   href="{{ route('admin.coupons.edit', ['id'=>base64_encode($intstructor->id)]) }}">Edit
                                                                </a>
                                                                <a class="dropdown-item"
                                                                   href="{{ route('admin.coupons.delete', ['id'=>base64_encode($intstructor->id)]) }}">Delete
                                                                </a> --}}
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


@endsection
@push('scripts')
    <script>

        function makePayment(url) {
            var x;
            var r = confirm("Are you sure?");
            if (r == true) {

                window.location.href = url;
            }
        }
    </script>
@endpush
