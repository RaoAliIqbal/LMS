@extends('admin.layouts.app')

@section('title')
    {{ optional($data['lesson'])->title ?? 'Chapters' }}
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <!-- ############ Main START-->
        <div class="padding">
            <div class="row">
                <div class="col-md-12">


                        <div class="box">
                            <div class="box-header">
                                <h2>Chapter's List</h2>
                                <a href="{{ route('admin_add_Chap', optional($data['lesson'])->id ?? 0) }}" class="btn btn-primary cstmBtn rightALign">Add New</a>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                   @include('layouts.partials.alertBox')
                                    <table id="datatable" class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>
{{--                                            <th>Chapter No.</th>--}}
                                            <th>Title</th>
                                            <th>Exercise</th>
                                            <th>Quiz</th>
                                            <th>Action</th>

                                        </tr>
                                        </thead>
                                        <tbody>

                                        @if(!empty($data['chapters']))
                                            @foreach($data['chapters'] as $chapter)
                                                <tr>
{{--                                                    <td>{{ $chapter->chapter_no }}</td>--}}
                                                    <td>{{ $chapter->title }}</td>
                                                    <td>{{ ($chapter->exercise != null)?$chapter->exercise->title:'None' }}</td>
                                                    <td>{{ ($chapter->quiz != null)?$chapter->quiz->title:'None' }}</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn white dropdown-toggle" data-toggle="dropdown"><i
                                                                        class="fa fa-bars"></i></button>

                                                            <div class="dropdown-menu">

                                                                <a class="dropdown-item"
                                                                   href="{{ route('admin_edit_Chap',$chapter->id) }}">Edit</a>

                                                                <span class="dropdown-item spanAncor" onclick="makePayment('{{ route('admin_delete_chapter',$chapter->id) }}')" >
                                                                 Delete
                                                                </span>

                                                            </div>
                                                        </div>
                                                    </td>
                                                   </tr>

                                            @endforeach
                                        @endif
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
            var r = confirm("If you delete a Parent chapter, all child chapters will also deleted, Are you sure?");
            if (r == true) {

                window.location.href = url;
            }
        }
    </script>
@endpush
