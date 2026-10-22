@extends('admin.layouts.app')

@section('title')
    Course
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <!-- ############ Main START-->
        <div class="padding">
            <div class="row">
                <div class="col-md-12">


                        <div class="box">
                            <div class="box-header">
                                <h2>Course's List</h2>
                                <a href="{{ route('admin_add_lessons') }}" class="btn btn-primary cstmBtn rightALign">Add New</a>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                   @include('layouts.partials.alertBox')
                                    <table id="datatable" class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>

                                            <th>Official Name</th>
                                            <th>Catalog Name</th>
                                            <th>Catalog Number</th>
                                            <th>Thumbnail</th>
{{--                                            <th>Quiz</th>--}}
                                            <th>Evaluation</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($lessons as $lesson)
                                                <tr>

                                                    <td>{{ $lesson->title }}</td>
                                                    <td>{{ $lesson->title_two }}</td>
                                                    <td>{{ $lesson->title_dev }}</td>
                                                    <td><img src="{{ ($lesson->image != '')?url($lesson->image):config('settings.post_placeholder') }}" width="100" alt=""></td>
{{--                                                    <td>{{ ($lesson->quiz != null)?$lesson->quiz->title:'None' }}</td>--}}
                                                    <td>{{ ($lesson->evaluation != null)?$lesson->evaluation->title:'None' }}</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn white dropdown-toggle" data-toggle="dropdown"><i
                                                                        class="fa fa-bars"></i></button>

                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item"
                                                                   href="{{ route('adminLessonUsers',$lesson->id) }}">Enrolled User</a>

                                                                <a class="dropdown-item"
                                                                   href="{{ route('admin_viewChap',$lesson->id) }}">View Chapters</a>

                                                                <a class="dropdown-item"
                                                                   href="{{ route('admin_edit_lessons',$lesson->id) }}">Edit</a>

                                                                <!--<span class="dropdown-item spanAncor" onclick="makePayment('{{ route('admin_delete_lessons',$lesson->id) }}')" >-->
                                                                <!-- Delete-->
                                                                <!--</span>-->

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
