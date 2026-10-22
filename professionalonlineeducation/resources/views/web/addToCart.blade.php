@extends('layouts.web')

@section('content')
    <section class="top_section_banner" style="background-image:url('{{ url("webAssets/images/banner-homepage.jpg") }}');">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="top_banner_titles">
                        <h3>Cart Page</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="category_section blue_bg_section section_tb_pads less_pb_spaces">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="small_section_titles white_txt inc_size_title">
                        <h4>Cart Page</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cartpage">
            <div class="container">

                <section class="cartpage">
                    <div class="container">
                        <table id="cart" class="table table-hover table-condensed">
                            <thead>
                                <tr>
                                    <th style="width:50%">Name</th>
                                    <th style="width:10%">Price</th>
                                    <th style="width:10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($courses as $course)
                                <tr>
                                    <td data-th="Product">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h4 class="nomargin">{{ $course->title }}</h4>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-th="Price">
                                        <span class="currency-price" data-usd="{{ Helper::usdDataAmount($course->price) }}"><span class="curr-symbol">$</span><span class="curr-amount">{{ Helper::formatUsd($course->price) }}</span></span>
                                    </td>
                                    <td class="actions">
                                        <a href="{{ route('removeCart',$course->id) }}">
                                            <span class="removeCart" data-id="{{ $course->id }}">x</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            @forelse($seminars as $seminar)
                                <tr>
                                    <td data-th="Product">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h4 class="nomargin"><span class="text-muted" style="font-size:12px;">Live event</span><br>{{ $seminar->course_title }}</h4>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-th="Price">
                                        <span class="currency-price" data-usd="{{ Helper::usdDataAmount($seminar->price_for_display) }}"><span class="curr-symbol">$</span><span class="curr-amount">{{ Helper::formatUsd($seminar->price_for_display) }}</span></span>
                                    </td>
                                    <td class="actions">
                                        <a href="{{ route('removeSeminarCart',$seminar->id) }}">
                                            <span class="removeCart" data-id="{{ $seminar->id }}">x</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            @if(count($courses) === 0 && count($seminars) === 0)
                                <tr>
                                    <td colspan="3"><p>Your Cart is empty</p></td>
                                </tr>
                            @endif
                            </tbody>
                            @if(count($courses) || count($seminars))
                                <tfoot>
                                    <tr>
                                        <td class="hidden-xs"></td>
                                        <td class="hidden-xs text-center"></td>
                                        <td><a href="{{ route('checkoutPage') }}" class="btn btn-primary">Proceed to checkout</a></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </section>
            </div>
    </section>

@endsection
