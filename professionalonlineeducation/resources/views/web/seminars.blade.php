@extends('layouts.web')
@section('title','Live Events | Professional Online Education')
@section('keywords','Professional Online Education, live events, seminars, continuing education')
@section('description','Browse upcoming live training events and seminars. Register online with Professional Online Education — Canham College, Inc.')
@section('content')
    <section class="top_section_banner events-hero" style="background-image:url('{{ url("webAssets/images/banner-homepage.jpg") }}');">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="top_banner_titles">
                        <h3>Live Events</h3>
                        <p class="events-hero__sub">Hands-on and live training — dates, locations, and pricing in one place.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="category_section blue_bg_section section_tb_pads events-page">
        <div class="container">
            <div class="row row_btm_space">
                <div class="col-sm-12 col-md-10 col-md-offset-1 text-center">
                    <div class="small_section_titles white_txt inc_size_title events-page__heading">
                        <h4>Upcoming events</h4>
                        <p class="events-page__lead white_txt">Browse all upcoming events below. Add an event to your cart, then open your cart to check out when you are ready.</p>
                    </div>
                </div>
            </div>

            @if(count($seminars) > 0)
                <div class="events-grid-shell">
                    <div id="CartAlert" class="events-page__alert"></div>
                    <div class="row events-grid">
                        @foreach($seminars as $seminar)
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 events-grid__col">
                                <article class="event-card">
                                    <div class="event-card__top">
                                        @if(!empty($seminar->is_online))
                                            <span class="event-card__badge event-card__badge--online">Online</span>
                                        @else
                                            <span class="event-card__badge event-card__badge--live">In person</span>
                                        @endif
                                        <h3 class="event-card__title">{{ $seminar->course_title }}</h3>
                                    </div>
                                    <ul class="event-card__meta">
                                        @if(!empty($seminar->city) || !empty($seminar->state))
                                            <li class="event-card__meta-item">
                                                <span class="event-card__meta-label">Location</span>
                                                <span class="event-card__meta-value">{{ trim(implode(', ', array_filter([$seminar->city, $seminar->state]))) }}</span>
                                            </li>
                                        @endif
                                        @if(!empty($seminar->hours))
                                            <li class="event-card__meta-item">
                                                <span class="event-card__meta-label">Duration</span>
                                                <span class="event-card__meta-value">{{ $seminar->hours }} hours</span>
                                            </li>
                                        @endif
                                        <li class="event-card__meta-item">
                                            <span class="event-card__meta-label">Dates</span>
                                            <span class="event-card__meta-value">{{ \Carbon\Carbon::parse($seminar->start_date)->format('M j') }} – {{ \Carbon\Carbon::parse($seminar->end_date)->format('M j, Y') }}</span>
                                        </li>
                                        <li class="event-card__meta-item">
                                            <span class="event-card__meta-label">Time</span>
                                            <span class="event-card__meta-value">{{ \Carbon\Carbon::parse($seminar->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($seminar->end_time)->format('g:i A') }}</span>
                                        </li>
                                    </ul>
                                    <div class="event-card__footer">
                                        @if($seminar->hasDisplayableUsdPrice())
                                            <p class="event-card__price currency-price" data-usd="{{ Helper::usdDataAmount($seminar->cost) }}">
                                                <span class="event-card__price-label">From</span>
                                                <span class="curr-symbol">$</span><span class="curr-amount">{{ Helper::formatUsd($seminar->cost) }}</span>
                                            </p>
                                        @else
                                            <p class="event-card__price event-card__price--contact">
                                                <span class="event-card__price-label">Pricing</span>
                                                <span class="event-card__price-note">Contact us</span>
                                            </p>
                                        @endif
                                        <div class="event-card__actions">
                                            <button
                                                type="button"
                                                data-type="add-to-cart-seminar"
                                                data-seminar-id="{{ $seminar->id }}"
                                                data-seminar-title="{{ e($seminar->course_title) }}"
                                                class="altBtn event-card__btn event-card__btn--primary btnLoading"
                                                data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Please wait">
                                                Add to cart
                                            </button>
                                            <a href="{{ route('eventDetails',$seminar->id) }}" class="event-card__btn event-card__btn--ghost">Details</a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-sm-12 col-md-8 col-md-offset-2">
                        <div class="events-empty">
                            <h4 class="events-empty__title">No events scheduled</h4>
                            <p class="events-empty__text">There are no live events listed right now. Please check back soon or contact us for other training options.</p>
                            <a href="{{ route('coursesPage') }}" class="course_small_btn">Browse courses</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <style>
        .events-hero .top_banner_titles h3 { margin-bottom: 0.35em; }
        .events-hero__sub {
            color: rgba(255,255,255,0.92);
            font-size: 17px;
            max-width: 640px;
            margin: 0 auto;
            line-height: 1.5;
            font-weight: 400;
        }
        .events-page__heading h4 {
            margin-bottom: 0.5rem;
            color: #fff !important;
        }
        .events-page__lead {
            font-size: 16px;
            line-height: 1.55;
            font-weight: 400;
            margin-bottom: 0;
            color: #fff !important;
            opacity: 1;
        }
        .events-grid-shell {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            padding: 8px 12px 40px;
        }
        @media (min-width: 768px) {
            .events-grid-shell { padding: 8px 16px 48px; }
        }
        .events-page__alert { min-height: 0; margin-bottom: 12px; }
        .events-page__alert .alert { margin-bottom: 0; }

        .events-grid__col {
            margin-bottom: 22px;
        }
        @media (min-width: 992px) {
            .events-grid__col { margin-bottom: 26px; }
        }

        .event-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.12);
            text-align: left;
            overflow: hidden;
            width: 100%;
            min-height: 0;
            display: flex;
            flex-direction: column;
            margin: 0;
            border: 1px solid rgba(255,255,255,0.25);
            transition: box-shadow 0.25s ease, transform 0.25s ease;
        }
        .event-card:hover {
            box-shadow: 0 16px 48px rgba(0,0,0,0.16);
            transform: translateY(-2px);
        }
        .event-card__top {
            padding: 1.1rem 1.25rem 0.75rem;
            border-bottom: 1px solid #eef2f6;
        }
        .event-card__badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 0.35rem 0.65rem;
            border-radius: 999px;
            margin-bottom: 0.65rem;
        }
        .event-card__badge--live { background: #e8f4fc; color: #0f75bc; }
        .event-card__badge--online { background: #eef7e8; color: #3d7c1f; }
        .event-card__title {
            font-size: 15px;
            font-weight: 700;
            line-height: 1.35;
            color: #1a1a1a;
            margin: 0;
            min-height: 2.7em;
        }
        .event-card__meta {
            list-style: none;
            margin: 0;
            padding: 0.85rem 1.25rem;
            flex: 1;
        }
        .event-card__meta-item {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 0.4rem 0;
            font-size: 14px;
            border-bottom: 1px solid #f0f3f6;
        }
        .event-card__meta-item:last-child { border-bottom: 0; }
        .event-card__meta-label { color: #6b7280; font-weight: 600; }
        .event-card__meta-value { color: #111827; text-align: right; }
        .event-card__footer {
            padding: 1rem 1.25rem 1.2rem;
            background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
            border-top: 1px solid #eef2f6;
        }
        .event-card__price {
            margin: 0 0 0.85rem;
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f75bc;
        }
        .event-card__price-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin-bottom: 0.15rem;
        }
        .event-card__price--contact .event-card__price-note {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f75bc;
        }
        .event-card__actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        @media (min-width: 480px) {
            .event-card__actions { flex-direction: row; flex-wrap: wrap; }
        }
        .event-card__btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: "Mulish", Sans-serif;
            font-weight: 600;
            font-size: 15px;
            padding: 12px 18px;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
            flex: 1;
            min-width: 0;
        }
        .event-card__btn--primary {
            background: #0f75bc;
            color: #fff !important;
            box-shadow: 0 4px 14px rgba(15, 117, 188, 0.35);
        }
        .event-card__btn--primary:hover {
            background: #0a5d96;
            color: #fff !important;
        }
        .event-card__btn--ghost {
            background: #fff;
            color: #0f75bc !important;
            border: 2px solid #0f75bc;
        }
        .event-card__btn--ghost:hover {
            background: #f0f7fc;
            color: #0a5d96 !important;
        }

        .events-empty {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            padding: 2rem 1.5rem;
            text-align: center;
        }
        .events-empty__title { color: #fff; margin-top: 0; font-weight: 700; }
        .events-empty__text { color: rgba(255,255,255,0.9); font-size: 16px; line-height: 1.55; margin-bottom: 1.25rem; }
    </style>
@endsection
