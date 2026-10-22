@extends('admin.layouts.app')

@section('title')
   Course Approvals
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <!-- ############ Main START-->
        <div class="padding">
            <div class="row">
                <div class="col-md-12">


                    <div class="box">
                        <div class="box-header">
                            <h2>Course Approval's List</h2>
                            <a href="{{ route('admin_approvalCourseAdd') }}" class="btn btn-primary cstmBtn rightALign">Add New</a>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                @include('layouts.partials.alertBox')
                                <table id="datatable" class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                    <thead>
                                    <tr>

                                        <th>Approval</th>
                                        <th>Courses</th>
                                        <th>Approval No.</th>
                                        <th>Provider No.</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Categories</th>
                                        <th>States</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($approvals as $approval)

                                        <tr>
                                            <td>{{ ($approval->approval)?$approval->approval->approval:'none' }}</td>
                                            <td>{{ ($approval->course)?$approval->course->title_dev:'none' }}</td>
                                            <td>{{ $approval->approval_no }}</td>
                                            <td>{{ $approval->provider_no }}</td>
                                            <td>{{ $approval->start_date }}</td>
                                            <td>{{ ($approval->end_date == null)?'Never Expired':$approval->end_date }}</td>
                                            <td>{{ Helper::getCourseApprovalCategories($approval,'string') }}</td>
                                            <td>{{ Helper::getCourseApprovalStates($approval,'string') }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn white dropdown-toggle" data-toggle="dropdown"><i
                                                            class="fa fa-bars"></i></button>

                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item"
                                                           href="{{ route('admin_approvalCourseEdit',$approval->id) }}">Edit</a>
{{--                                                        <span class="dropdown-item spanAncor" onclick="makePayment('{{ route('admin_delete_Approval',$approval->id) }}')" >--}}
{{--                                                                 Delete--}}
{{--                                                                </span>--}}

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
