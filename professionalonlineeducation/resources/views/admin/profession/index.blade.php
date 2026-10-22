@extends('admin.layouts.app')

@section('title')
    Professions
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <div class="padding">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            @include('layouts.partials.alertBox')
                            <h2>{{ isset($is_update) ? 'Edit' : 'Add' }} Profession</h2>
                            <form action="{{ route('admin.professions.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="">Profession</label>
                                            <input type="text" class="form-control" name="profession" id="profession" placeholder="Enter profession name here" value="{{ @$edit_profession->name??old('profession')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="hidden" name="profession_id" id="profession_id" value="{{ base64_encode(@$edit_profession->id) }}">
                                    </div>
                                </div>
                                <input type="submit" class="btn btn-primary float-right" value="{{ (isset($is_update)) ? 'Update' : 'Add' }}">
                            </form>
                        </div>
                        <div class="box-body">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                        <div class="box">
                            <div class="box-header">
                                <h2>Professions List</h2>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Profession</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($professions AS $profession)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{{ $profession->name }}}</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn white dropdown-toggle" data-toggle="dropdown"><i
                                                                        class="fa fa-bars"></i></button>

                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item"
                                                                   href="{{ route('admin.professions.edit', ['id'=>base64_encode($profession->id)]) }}">Edit
                                                                </a>
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
