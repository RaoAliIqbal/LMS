@extends('admin.layouts.app')

@section('title')
    Evaluations
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <!-- ############ Main START-->
        <div class="padding">
            <div class="row">
                <div class="col-md-12">


                        <div class="box">
                            <div class="box-header">
                                <h2>Evaluation's List</h2>
                                <a href="{{ route('admin_add_evaluation') }}" class="btn btn-primary cstmBtn rightALign">Add New</a>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                   @include('layouts.partials.alertBox')
                                    <table id="datatable" class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>

                                            <th>Title</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($evaluations as $evaluation)
                                                <tr>

                                                    <td>{{ $evaluation->title }}</td>

                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn white dropdown-toggle" data-toggle="dropdown"><i
                                                                        class="fa fa-bars"></i></button>

                                                            <div class="dropdown-menu">

                                                                <a class="dropdown-item"
                                                                   href="{{ route('admin_edit_evaluation',$evaluation->id) }}">Edit Evaluation</a>

{{--                                                                <span class="dropdown-item spanAncor" onclick="makePayment('{{ route('admin_delete_quiz',$evaluation->id) }}')" >--}}
{{--                                                                 Delete Evaluation--}}
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
