@extends('admin.layouts.app')

@section('title')
    Coupons
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <div class="padding">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            @include('layouts.partials.alertBox')
                            <h2>{{ isset($is_update) ? 'Edit' : 'Add' }} Coupon</h2>
                            <form action="{{ route('admin.coupons.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Code</label>
                                            <input type="text" class="form-control" name="code" id="code" placeholder="Enter coupon code here" value="{{ @$edit_coupon->code??old('coupon')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Discount %</label>
                                            <input type="number" class="form-control" name="discount" id="discount" placeholder="Enter discount %  here" value="{{ @$edit_coupon->dicount??old('discount')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Status</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value="1" @if(@$edit_coupon->status == 1) selected @endif>Active</option>
                                                <option value="0" @if(@$edit_coupon->status == 0) selected @endif>Deactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="coupon_id" id="coupon_id" value="{{ base64_encode(@$edit_coupon->id) }}">
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
                                <h2>Coupons List</h2>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Discount %</th>
                                            <th>Status</th>
                                            <th>Created at</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($coupons AS $coupon)
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
