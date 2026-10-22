<?php

namespace App\Http\Controllers\Admin;

use App\Models\DiscountCoupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
/**
 * Class DiscountCouponController
 * @package App\Http\Controllers
 */
class DiscountCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discountCoupons = DiscountCoupon::paginate();

        return view('discount-coupon.index', compact('discountCoupons'))
            ->with('i', (request()->input('page', 1) - 1) * $discountCoupons->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $discountCoupon = new DiscountCoupon();
        return view('discount-coupon.create', compact('discountCoupon'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(DiscountCoupon::$rules);

        $discountCoupon = DiscountCoupon::create($request->all());

        return redirect()->route('discount-coupons.index')
            ->with('success', 'DiscountCoupon created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $discountCoupon = DiscountCoupon::find($id);

        return view('discount-coupon.show', compact('discountCoupon'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $discountCoupon = DiscountCoupon::find($id);

        return view('discount-coupon.edit', compact('discountCoupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  DiscountCoupon $discountCoupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DiscountCoupon $discountCoupon)
    {
        request()->validate(DiscountCoupon::$rules);

        $discountCoupon->update($request->all());

        return redirect()->route('discount-coupons.index')
            ->with('success', 'DiscountCoupon updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $discountCoupon = DiscountCoupon::find($id)->delete();

        return redirect()->route('discount-coupons.index')
            ->with('success', 'DiscountCoupon deleted successfully');
    }
}
