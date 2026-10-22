@extends('layouts.web')

@section('title', $seminar->course_title . ' | Live Event')
@section('keywords', 'live event, seminar, ' . $seminar->course_title)
@section('description', \Illuminate\Support\Str::limit(strip_tags($seminar->description), 160))

@section('content')
<style>
    .courseDetailsPage {
        margin-top: 60px;
        margin-bottom: 60px;
    }
    .event-product-meta {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }
    .event-product-meta ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .event-product-meta li {
        padding: 0.5rem 0;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }
    .event-product-meta li:last-child {
        border-bottom: 0;
    }
    .event-product-meta label {
        font-weight: 600;
        color: #555;
        margin: 0;
    }
    .event-enroll-actions {
        margin-top: 1.25rem;
    }
    .event-enroll-actions .course_details_btn {
        display: inline-block;
        width: 100%;
        text-align: center;
    }
</style>

<section class="states_section courseDetailsPage">
    <div class="container">
        <div id="CartAlert"></div>
        <div class="shadow_box add_spaces">
            <div class="row">
                <div class="col-md-8">
                    <div class="small_section_titles">
                        @if(!empty($seminar->is_online))
                            <h6 class="text-muted" style="margin-bottom: 0.5rem;">Online event</h6>
                        @else
                            <h6 class="text-muted" style="margin-bottom: 0.5rem;">In person</h6>
                        @endif
                        <h4>{{ $seminar->course_title }}</h4>
                    </div>
                    <div class="course_details seminarDetails">
                        {!! $seminar->description !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="event-product-meta">
                        <h5 style="margin-top: 0; margin-bottom: 1rem;">Event details</h5>
                        <ul>
                            <li>
                                <label>Price</label>
                                @if($seminar->hasDisplayableUsdPrice())
                                    <span class="currency-price" data-usd="{{ Helper::usdDataAmount($seminar->cost) }}">
                                        <span class="curr-symbol">$</span><span class="curr-amount">{{ Helper::formatUsd($seminar->cost) }}</span>
                                    </span>
                                @else
                                    <span>Contact us</span>
                                @endif
                            </li>
                            @if(!empty($seminar->max_students))
                                <li>
                                    <label>Capacity</label>
                                    <span>{{ $seminar->max_students }} students max</span>
                                </li>
                            @endif
                            @if(!empty($seminar->ceus))
                                <li>
                                    <label>CEUs</label>
                                    <span>{{ $seminar->ceus }}</span>
                                </li>
                            @endif
                            <li>
                                <label>Dates</label>
                                <span>{{ \Carbon\Carbon::parse($seminar->start_date)->format('M d') }} – {{ \Carbon\Carbon::parse($seminar->end_date)->format('M d, Y') }}</span>
                            </li>
                            <li>
                                <label>Time</label>
                                <span>{{ \Carbon\Carbon::parse($seminar->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($seminar->end_time)->format('g:i A') }}</span>
                            </li>
                            @if(!empty($seminar->hours))
                                <li>
                                    <label>Duration</label>
                                    <span>{{ $seminar->hours }} hours</span>
                                </li>
                            @endif
                            @if(!empty($seminar->city) || !empty($seminar->state))
                                <li>
                                    <label>Location</label>
                                    <span>{{ trim(implode(', ', array_filter([$seminar->city, $seminar->state]))) }}</span>
                                </li>
                            @endif
                            @if(!empty($seminar->address) || !empty($seminar->location))
                                <li>
                                    <label>Venue</label>
                                    <span>{{ trim($seminar->location . ($seminar->location && $seminar->address ? ' — ' : '') . $seminar->address) }}</span>
                                </li>
                            @endif
                            @if(!empty($seminar->is_online) && !empty($seminar->zoom_link))
                                <li>
                                    <label>Online</label>
                                    <span><a href="{{ $seminar->zoom_link }}" target="_blank" rel="noopener noreferrer">Join link</a></span>
                                </li>
                            @endif
                        </ul>
                        <div class="event-enroll-actions">
                            <button
                                type="button"
                                data-type="add-to-cart-seminar"
                                data-seminar-id="{{ $seminar->id }}"
                                data-seminar-title="{{ e($seminar->course_title) }}"
                                class="altBtn course_details_btn btnLoading"
                                data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Please wait">
                                Add to cart
                            </button>
                            <p style="margin-top:12px;font-size:14px;"><a href="{{ route('addToCartPage') }}">View cart</a> — sign in (if needed), then <strong>Proceed to checkout</strong> from the cart page.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
