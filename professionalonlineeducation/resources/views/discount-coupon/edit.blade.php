@extends('../admin/layouts.app')

@section('template_title')
    Update Discount Coupon
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Update Discount Coupon</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('discount-coupons.update', $discountCoupon->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('discount-coupon.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
