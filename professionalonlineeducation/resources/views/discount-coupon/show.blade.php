@extends('../admin/layouts.app')

@section('template_title')
    {{ $discountCoupon->name ?? 'Show Discount Coupon' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Discount Coupon</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('discount-coupons.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Code:</strong>
                            {{ $discountCoupon->code }}
                        </div>
                        <div class="form-group">
                            <strong>Type:</strong>
                            {{ $discountCoupon->type }}
                        </div>
                        <div class="form-group">
                            <strong>Dicount:</strong>
                            {{ $discountCoupon->dicount }}
                        </div>
                        <div class="form-group">
                            <strong>Status:</strong>
                            {{ $discountCoupon->status }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
