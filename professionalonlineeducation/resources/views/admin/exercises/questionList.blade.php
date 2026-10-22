@extends('admin.layouts.app')

@section('title')
    Quiz Questions
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <!-- ############ Main START-->
        <div class="padding">
            <div class="row">
                <div class="col-md-12">


                        <div class="box">
                            <div class="box-header">
                                <h2>Question's List</h2>
                                <a href="{{ route('admin_addQuestion_exercise',$quiz->id) }}" class="btn btn-primary cstmBtn rightALign">Add New</a>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                   @include('layouts.partials.alertBox')
                                    <table id="datatable" class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>

                                            <th>Questions</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($questions as $question)
                                                <tr>

                                                    <td>{!!  $question->question !!}</td>


                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn white dropdown-toggle" data-toggle="dropdown"><i
                                                                        class="fa fa-bars"></i></button>

                                                            <div class="dropdown-menu">

                                                                <a class="dropdown-item"
                                                                   href="{{ route('admin_editQuestion_exercise',['id'=>$question->quiz_id,'qid'=>$question->id]) }}">Edit Question</a>

                                                                <span class="dropdown-item spanAncor" onclick="makePayment('{{ route('admin_DeleteQuestion',['id'=>$question->quiz_id,'qid'=>$question->id]) }}')" >
                                                                 Delete Question
                                                                </span>

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
