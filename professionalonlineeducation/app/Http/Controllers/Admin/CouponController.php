<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use App\Support\AdminDbLoader;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{

    public function index(){
        $payload = AdminDbLoader::modelList(DiscountCoupon::class, 0, function ($q) {
            $q->latest();
        });
        $coupons = $payload['items'];
        $view = view('admin.coupon.index', compact('coupons'));

        if ($payload['error']) {
            return $view->with('error', $payload['error']);
        }

        return $view;
    }

    public function store(Request $req){//add and update the coupon codes
        $req->validate([
            'code'      => ['required', 'string', 'min:1', 'max:50', 'alpha_dash', Rule::unique('discount_coupons')->ignore(@base64_decode($req->coupon_id))],
            'discount'  => ['required', 'numeric', 'min:1', 'max:99'],
            'status'    => ['required', 'in:1,0'],
            'coupon_id' => ['nullable']
        ]);

        if(isset($req->coupon_id) && !empty($req->coupon_id)){
            $coupon = DiscountCoupon::findOrFail(base64_decode($req->coupon_id));
            $msg    = 'Coupon updated successfully';
        }else{
            $coupon = new DiscountCoupon();
            $msg    = 'Coupon added successfully';
        }
        $coupon->code    = $req->code;
        $coupon->dicount = $req->discount;
        $coupon->type    = 'percentage';
        $coupon->status  = $req->status;
        $coupon->save();

        return redirect()->route('admin.coupons.index')->with('info', $msg);
    }

    public function edit($id){//edit copon
        if(isset($id) && !empty($id)){
            $data = array(
                'edit_coupon'   => DiscountCoupon::findOrFail(base64_decode($id)),
                'coupons'       => DiscountCoupon::latest()->get(),
                'is_update'     => true
            );
            return view('admin.coupon.index')->with($data);
        }
        abort(404);
    }

    public function delete($id){
        DiscountCoupon::destroy(base64_decode($id));
        return redirect()->route('admin.coupons.index')->with('info', 'Coupon deleted successfully');
    }

    public function updateStatus($id, $status){
        if(in_array($status, [1,0])){
            DiscountCoupon::where('id', base64_decode($id))->update(['status'=>$status]);
            return redirect()->route('admin.coupons.index')->with('info', 'Status updated successfully');
        }
        abort(404);
    }

}
