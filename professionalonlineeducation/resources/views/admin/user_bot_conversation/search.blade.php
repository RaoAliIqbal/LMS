@extends('admin.layouts.app')

@section('title')
    User Bot Conversation
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <div class="padding">
            <div class="row">
                <div class="col-md-12">
                        <div class="box">
                            <div class="box-header">
                                <h2>User Bot Conversation {{ $ip }} -- {{ $date }}</h2>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Question</th>
                                            <th>Answer</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($result as $data)
                                                <tr>
                                                  <td>{{ $loop->iteration }}</td>
                                                  <td>{{ $data->question }}</td>
                                                  <td>{{ $data->answer }}</td>
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
