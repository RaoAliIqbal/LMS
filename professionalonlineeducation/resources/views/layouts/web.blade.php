<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="google-site-verification" content="EoftgFh6E3AZC6x7eq2Eo_4tWQvnTLxJIuYV6rQjvQE" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ── SEO: Core ──────────────────────────────────────────────── --}}
    <title>@yield('title', 'Professional Online Education | Canham College, Inc.')</title>
    <meta name="description" content="@yield('description', 'Canham College, Inc. — PACE approved provider of Chiropractic CE, Chiropractic Assistant Training, Massage Therapy CE, Behavioral Health, CNA and more. Toll Free: 1-888-900-3382.')">
    <meta name="keywords"    content="@yield('keywords', 'chiropractic continuing education, chiropractic assistant training, massage therapy CE, behavioral health, CNA, PACE approved, online CE courses, Canham College')">
    <meta name="author"      content="Canham College, Inc.">
    <meta name="robots"      content="index, follow">
    <link rel="canonical"    href="{{ url()->current() }}">

    {{-- ── SEO: Open Graph (Facebook / LinkedIn) ─────────────────── --}}
    <meta property="og:type"        content="website">
    <meta property="og:site_name"   content="Professional Online Education — Canham College, Inc.">
    <meta property="og:title"       content="@yield('title', 'Professional Online Education | Canham College, Inc.')">
    <meta property="og:description" content="@yield('description', 'PACE approved Chiropractic CE, Assistant Training, Massage Therapy CE and more. Canham College, Inc. — Toll Free 1-888-900-3382.')">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:image"       content="{{ url('webAssets/images/banner-homepage.jpg') }}">
    <meta property="og:locale"      content="en_US">

    {{-- ── SEO: Twitter Card ──────────────────────────────────────── --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="@yield('title', 'Professional Online Education | Canham College, Inc.')">
    <meta name="twitter:description" content="@yield('description', 'PACE approved Chiropractic CE and Assistant Training courses online.')">
    <meta name="twitter:image"       content="{{ url('webAssets/images/banner-homepage.jpg') }}">

    {{-- ── SEO: JSON-LD Structured Data (LocalBusiness) ─────────── --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "EducationalOrganization",
        "name": "Canham College, Inc.",
        "alternateName": "Professional Online Education",
        "url": "https://www.ProfessionalOnlineEducation.com",
        "logo": "{{ url('webAssets/images/logo-footer.png') }}",
        "description": "PACE approved provider of Chiropractic CE, Chiropractic Assistant Training, Massage Therapy CE, Behavioral Health, CNA and Peer Support Certification courses.",
        "telephone": "+18889003382",
        "email": "mgr@professionalonlineeducation.com",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "2934 E Avalon Drive",
            "addressLocality": "Phoenix",
            "addressRegion": "AZ",
            "postalCode": "85016",
            "addressCountry": "US"
        },
        "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"],
            "opens": "08:00",
            "closes": "17:00"
        },
        "sameAs": [
            "https://www.facebook.com/CanhamCollege/",
            "https://www.instagram.com/rosalind_canham/",
            "https://www.linkedin.com/company/canham-college/"
        ],
        "hasCredential": {
            "@type": "EducationalOccupationalCredential",
            "credentialCategory": "PACE Approved Provider"
        }
    }
    </script>

    <link rel="icon"       href="{{ url('webAssets/images/favicon.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ url('webAssets/images/favicon.png') }}">

    @include('layouts.partials.meta')

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-VN252P5VY8"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-VN252P5VY8');
</script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-N3M39QM');</script>
<!-- End Google Tag Manager -->

</head>
<body>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N3M39QM"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    @include('layouts.partials.header')

    @yield('content')

    @include('layouts.partials.footer')

    @include('layouts.partials.scripts')

    {{-- Shopping cart icon --}}
    <div class="cart_block" style="position:fixed;top:14px;right:14px;bottom:auto;z-index:99999;">
        <a href="{{ route('addToCartPage') }}"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <span id="cartCount">{{ Helper::getCartCount() }}</span></a>
    </div>

    {{-- Navbar mobile toggle --}}
    <script>
        $('.fa.fa-bars').click(function(){
            $('.navbar-nav.navbar').slideToggle();
        });
    </script>

    {{-- ADA Accessibility Widget --}}
    @include('layouts.partials.ada-widget')

    @php
        $googleReviewUrl = config('settings.google_review_url');
        $showGoogleReviewWidget = is_string($googleReviewUrl)
            && $googleReviewUrl !== ''
            && stripos($googleReviewUrl, 'PLACEHOLDER') === false;
    @endphp
    {{-- ── Google Review floating widget (only when GOOGLE_REVIEW_URL is set) ───── --}}
    @if($showGoogleReviewWidget)
    <style>
    .gr-widget {
        position: fixed;
        bottom: 160px;
        left: 18px;
        z-index: 99997;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0;
        animation: grSlideIn .5s ease;
    }
    @keyframes grSlideIn {
        from { opacity:0; transform:translateX(-30px); }
        to   { opacity:1; transform:translateX(0); }
    }
    .gr-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 6px 28px rgba(0,0,0,.16);
        width: 200px;
        padding: 14px 14px 12px;
        border-left: 4px solid #1379ba;
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .gr-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 36px rgba(19,121,186,.22);
    }
    .gr-top {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 7px;
    }
    .gr-title {
        font-size: 12px;
        font-weight: 700;
        color: #1a2e44;
        line-height: 1.3;
    }
    .gr-stars {
        display: flex;
        gap: 1px;
        margin-bottom: 8px;
    }
    .gr-stars span { color: #FBBC05; font-size: 14px; }
    .gr-text {
        font-size: 11px;
        color: #666;
        line-height: 1.5;
        margin: 0 0 10px;
    }
    .gr-btn {
        display: block;
        background: #1379ba;
        color: #fff !important;
        text-align: center;
        padding: 7px 10px;
        border-radius: 8px;
        font-size: 11.5px;
        font-weight: 600;
        text-decoration: none !important;
        transition: background .2s;
    }
    .gr-btn:hover {
        background: #0e5a96;
        color: #fff !important;
        text-decoration: none !important;
    }
    .gr-tail {
        width: 0; height: 0;
        border-left: 10px solid transparent;
        border-right: 10px solid transparent;
        border-top: 10px solid #fff;
        margin-left: -130px;
        filter: drop-shadow(0 3px 3px rgba(0,0,0,.08));
    }
    @media (max-width: 575px) {
        .gr-widget { bottom: 130px; left: 10px; }
        .gr-card { width: 170px; }
    }
    </style>

    <div class="gr-widget">
        <div class="gr-card">
            <div class="gr-top">
                <svg width="24" height="24" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" style="flex-shrink:0;">
                    <path fill="#EA4335" d="M24 9.5c3.1 0 5.8 1.1 8 2.9l6-6C34.1 3.5 29.3 1.5 24 1.5 14.9 1.5 7.1 7 3.4 14.8l7 5.4C12.1 13.6 17.6 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v8.5h12.7c-.6 3-2.3 5.5-4.8 7.2l7.4 5.7c4.3-4 6.8-9.9 7.2-16.9z"/>
                    <path fill="#FBBC05" d="M10.4 28.8A14.6 14.6 0 0 1 9.5 24c0-1.7.3-3.3.8-4.8l-7-5.4A22.5 22.5 0 0 0 1.5 24c0 3.6.9 7 2.4 10l6.5-5.2z"/>
                    <path fill="#34A853" d="M24 46.5c5.4 0 9.9-1.8 13.2-4.8l-7.4-5.7c-1.6 1.1-3.6 1.7-5.8 1.7-6.4 0-11.9-4.3-13.6-10.1l-6.5 5.2C7.1 41 14.9 46.5 24 46.5z"/>
                </svg>
                <div class="gr-title">Enjoying our courses?</div>
            </div>
            <div class="gr-stars">
                <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
            </div>
            <p class="gr-text">Your review helps others find quality CE courses!</p>
            <a href="{{ $googleReviewUrl }}"
               target="_blank" rel="noopener noreferrer" class="gr-btn">
                ⭐ Leave a Review
            </a>
        </div>
    </div>
    @endif

    {{-- Currency Converter Widget — injected per-page only on checkout --}}

    @include('layouts.partials.chatbot-support')

</body>
</html>
