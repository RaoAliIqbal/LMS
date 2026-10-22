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
                                <h2>User Bot Conversation List</h2>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>IP</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $conversation)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $conversation->ip }}</td>
                                                    <td>{{ $conversation->created_date }}</td>
                                                    <td>
                                                        <a class="btn btn-sm btn-primary " href="{{ route('admin.user_bot_conv.search',['ip'=>$conversation->ip, 'date'=>$conversation->created_date]) }}">
                                                            <i class="fa fa-fw fa-eye"></i> view
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            {{-- @foreach($coupons AS $coupon)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{{ $coupon->code }}}</td>
                                                    <td>{{ $coupon->dicount }}</td>
                                                    <td>
                                                        @if($coupon->status == 1)
                                                            <span class="badge badge-success">Active</span>
                                                        @else 
                                                            <span class="badge badge-danger">Deactive</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ date('d-M-Y', strtotime($coupon->created_at)) }}</td>                                                
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn white dropdown-toggle" data-toggle="dropdown"><i
                                                                        class="fa fa-bars"></i></button>

                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item"
                                                                   href="{{ route('admin.coupons.edit', ['id'=>base64_encode($coupon->id)]) }}">Edit
                                                                </a>
                                                                <a class="dropdown-item"
                                                                   href="{{ route('admin.coupons.delete', ['id'=>base64_encode($coupon->id)]) }}">Delete
                                                                </a>
                                                                @if($coupon->status == 1)
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.coupons.update_status', ['id'=>base64_encode($coupon->id), 'status'=>0]) }}">Deactive
                                                                    </a>
                                                                @else 
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.coupons.update_status', ['id'=>base64_encode($coupon->id), 'status'=>1]) }}">Active
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

        function makePayment(url) {
            var x;
            var r = confirm("Are you sure?");
            if (r == true) {

                window.location.href = url;
            }
        }
    </script>
@endpush
