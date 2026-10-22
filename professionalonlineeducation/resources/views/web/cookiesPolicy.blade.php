@extends('layouts.web')
@section('title','Cookies Policy | Professional Online Education')
@section('keywords','Cookies Policy, Professional Online Education, Canham College, cookies, tracking')
@section('description','Learn how Professional Online Education uses cookies on our website and how you can manage your cookie preferences.')
@section('content')

<style>
/* ── Hero ── */
.policy-hero {
    background: linear-gradient(135deg,#054f7a 0%,#0877b8 50%,#1a99d6 100%);
    padding: 60px 0 50px;
    position: relative;overflow: hidden;
}
.policy-hero::before {
    content:'';position:absolute;inset:0;
    background: url('/webAssets/images/banner-homepage.jpg') center/cover no-repeat;
    opacity:.12;
}
.policy-hero .ph-inner { position:relative;z-index:1; }
.policy-hero .ph-badge {
    display:inline-flex;align-items:center;gap:8px;
    background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);
    color:#fff;font-size:12px;font-weight:600;
    padding:5px 14px;border-radius:50px;margin-bottom:14px;letter-spacing:.5px;
}
.policy-hero h1 { color:#fff;font-size:36px;font-weight:800;margin:0 0 10px;line-height:1.2; }
.policy-hero .ph-meta {
    color:rgba(255,255,255,.8);font-size:13px;
    display:flex;align-items:center;gap:16px;flex-wrap:wrap;
}
.policy-hero .ph-meta span { display:flex;align-items:center;gap:5px; }

/* ── Breadcrumb ── */
.policy-breadcrumb {
    background:#e8f4fc;border-bottom:1px solid #c2e0f4;padding:10px 0;font-size:12.5px;
}
.policy-breadcrumb a { color:#0877b8;text-decoration:none; }
.policy-breadcrumb a:hover { text-decoration:underline; }
.policy-breadcrumb .sep { color:#aaa;margin:0 6px; }

/* ── Layout ── */
.policy-wrap { padding:40px 0 60px;background:#f4f9fd; }
.policy-sidebar { position:sticky;top:90px; }
.policy-toc {
    background:#fff;border-radius:14px;border:1px solid #e3f2fd;
    overflow:hidden;box-shadow:0 2px 16px rgba(8,119,184,.08);
}
.policy-toc .toc-head {
    background:linear-gradient(135deg,#054f7a,#1a99d6);color:#fff;
    padding:14px 18px;font-size:12px;font-weight:700;letter-spacing:1px;
    text-transform:uppercase;display:flex;align-items:center;gap:8px;
}
.policy-toc ul { padding:10px 0;margin:0; }
.policy-toc ul li { list-style:none!important;padding:0;margin:0; }
.policy-toc ul li a {
    display:flex;align-items:center;gap:9px;padding:9px 18px;
    font-size:12.5px;color:#444;font-weight:500;
    text-decoration:none;border-left:3px solid transparent;transition:all .2s;
}
.policy-toc ul li a:hover,
.policy-toc ul li a.active {
    background:#e8f4fc;color:#054f7a;border-left-color:#1a99d6;font-weight:600;
}
.policy-toc ul li a .toc-num {
    width:20px;height:20px;border-radius:50%;background:#e3f2fd;color:#0877b8;
    font-size:10px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;
}
.policy-toc ul li a:hover .toc-num,
.policy-toc ul li a.active .toc-num { background:#0877b8;color:#fff; }

/* Cookie type cards */
.cookie-type-grid { display:flex;flex-wrap:wrap;gap:12px;margin:14px 0; }
.cookie-type-card {
    flex:1;min-width:140px;
    border-radius:10px;padding:14px 16px;
    border:1.5px solid #e3f2fd;background:#f5fbff;
    text-align:center;
}
.cookie-type-card .ctc-icon {
    width:38px;height:38px;border-radius:50%;
    background:linear-gradient(135deg,#054f7a,#1a99d6);
    color:#fff;font-size:15px;
    display:flex;align-items:center;justify-content:center;margin:0 auto 8px;
}
.cookie-type-card h5 { font-size:12.5px;font-weight:700;color:#054f7a;margin:0 0 5px; }
.cookie-type-card p { font-size:11.5px;color:#666;margin:0;line-height:1.5; }

/* ── Content cards ── */
.policy-card {
    background:#fff;border-radius:14px;border:1px solid #e3f2fd;
    margin-bottom:20px;overflow:hidden;
    box-shadow:0 2px 12px rgba(8,119,184,.06);transition:box-shadow .2s;
}
.policy-card:hover { box-shadow:0 4px 24px rgba(8,119,184,.12); }
.policy-card .pc-head {
    display:flex;align-items:center;gap:14px;
    padding:16px 22px 14px;border-bottom:1px solid #e8f4fc;background:#f5fbff;
}
.policy-card .pc-icon {
    width:42px;height:42px;border-radius:10px;
    background:linear-gradient(135deg,#054f7a,#1a99d6);
    color:#fff;font-size:17px;
    display:flex;align-items:center;justify-content:center;flex-shrink:0;
}
.policy-card .pc-head h4 { margin:0;font-size:15px;font-weight:700;color:#054f7a;line-height:1.3; }
.policy-card .pc-body { padding:18px 22px; }
.policy-card .pc-body p { font-size:14px;line-height:1.8;color:#444;margin-bottom:10px; }
.policy-card .pc-body p:last-child { margin-bottom:0; }
.policy-card .pc-body ul { margin:8px 0 10px 0;padding:0; }
.policy-card .pc-body ul li {
    list-style:none!important;
    padding:5px 0 5px 22px;font-size:14px;color:#444;line-height:1.7;position:relative;
}
.policy-card .pc-body ul li::before {
    content:'\f00c';font-family:FontAwesome;
    position:absolute;left:0;top:6px;color:#1a99d6;font-size:11px;
}

/* Intro card */
.policy-intro-card {
    background:linear-gradient(135deg,#e3f2fd,#e8f4fc);
    border:1px solid #b3d9f2;border-radius:14px;
    padding:22px 26px;margin-bottom:24px;
    display:flex;align-items:flex-start;gap:16px;
}
.policy-intro-card .pic-icon {
    width:48px;height:48px;border-radius:12px;
    background:linear-gradient(135deg,#054f7a,#1a99d6);
    color:#fff;font-size:20px;
    display:flex;align-items:center;justify-content:center;flex-shrink:0;
}
.policy-intro-card p { margin:0;font-size:14px;color:#0877b8;line-height:1.7;font-weight:500; }

/* Contact card */
.policy-contact-card {
    background:linear-gradient(135deg,#054f7a,#0877b8);
    border-radius:14px;padding:24px 26px;margin-top:10px;color:#fff;
}
.policy-contact-card h4 { color:#fff;font-size:15px;font-weight:700;margin-bottom:12px; }
.policy-contact-card .cc-item {
    display:flex;align-items:center;gap:10px;
    margin-bottom:8px;font-size:13.5px;color:rgba(255,255,255,.9);
}
.policy-contact-card .cc-item i { width:18px;text-align:center;color:#90c4e8; }
.policy-contact-card a { color:#90c4e8;text-decoration:none; }
.policy-contact-card a:hover { color:#fff;text-decoration:underline; }
.policy-print-btn {
    display:inline-flex;align-items:center;gap:7px;
    background:#fff;border:1.5px solid #b3d9f2;color:#0877b8;
    font-size:12px;font-weight:600;padding:7px 16px;border-radius:50px;
    cursor:pointer;transition:all .2s;text-decoration:none;margin-top:8px;
}
.policy-print-btn:hover { background:#e3f2fd;border-color:#1a99d6;color:#054f7a;text-decoration:none; }

@media(max-width:768px){
    .policy-hero h1 { font-size:26px; }
    .policy-sidebar { position:static;margin-bottom:24px; }
    .cookie-type-grid { flex-direction:column; }
}
</style>

{{-- Hero --}}
<div class="policy-hero">
    <div class="container ph-inner">
        <div class="ph-badge"><i class="fa fa-circle"></i> Legal</div>
        <h1 style="display:flex;align-items:center;gap:12px;">
            <span style="font-size:28px;">🍪</span> Cookies Policy
        </h1>
        <div class="ph-meta">
            <span><i class="fa fa-calendar"></i> Effective Date: January 1, 2024</span>
            <span><i class="fa fa-refresh"></i> Last Updated: January 1, 2024</span>
        </div>
    </div>
</div>

{{-- Breadcrumb --}}
<div class="policy-breadcrumb">
    <div class="container">
        <a href="{{ route('home') }}">Home</a>
        <span class="sep">/</span>
        <span style="color:#555;">Cookies Policy</span>
    </div>
</div>

{{-- Main Content --}}
<div class="policy-wrap">
    <div class="container">
        <div class="row">

            {{-- Sidebar TOC --}}
            <div class="col-md-3 hidden-xs hidden-sm">
                <div class="policy-sidebar">
                    <div class="policy-toc">
                        <div class="toc-head"><i class="fa fa-list-ul"></i> Contents</div>
                        <ul>
                            <li><a href="#section1"><span class="toc-num">1</span> What Are Cookies?</a></li>
                            <li><a href="#section2"><span class="toc-num">2</span> How We Use Cookies</a></li>
                            <li><a href="#section3"><span class="toc-num">3</span> Types of Cookies</a></li>
                            <li><a href="#section4"><span class="toc-num">4</span> Managing Cookies</a></li>
                            <li><a href="#section5"><span class="toc-num">5</span> Policy Updates</a></li>
                            <li><a href="#section6"><span class="toc-num">6</span> Contact Us</a></li>
                        </ul>
                    </div>
                    <br>
                    <a href="javascript:window.print()" class="policy-print-btn"><i class="fa fa-print"></i> Print this page</a>
                </div>
            </div>

            {{-- Content --}}
            <div class="col-md-9">

                <div class="policy-intro-card">
                    <div class="pic-icon" style="font-size:22px;">🍪</div>
                    <p>This website uses cookies to improve your experience. By continuing to use our site, you agree to the use of cookies as described in this policy.</p>
                </div>

                <div id="section1" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-question-circle"></i></div>
                        <h4>1. What Are Cookies?</h4>
                    </div>
                    <div class="pc-body">
                        <p>Cookies are small text files stored on your device when you visit a website. They allow websites to remember your preferences, keep sessions active, and understand how you interact with the site — helping us deliver a faster and more personalized experience.</p>
                    </div>
                </div>

                <div id="section2" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-cogs"></i></div>
                        <h4>2. How We Use Cookies</h4>
                    </div>
                    <div class="pc-body">
                        <p>We use cookies to:</p>
                        <ul>
                            <li>Remember your preferences and settings</li>
                            <li>Keep you logged in securely</li>
                            <li>Analyze website traffic and usage patterns</li>
                            <li>Improve overall site performance</li>
                        </ul>
                    </div>
                </div>

                <div id="section3" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-tags"></i></div>
                        <h4>3. Types of Cookies We Use</h4>
                    </div>
                    <div class="pc-body">
                        <div class="cookie-type-grid">
                            <div class="cookie-type-card">
                                <div class="ctc-icon"><i class="fa fa-lock"></i></div>
                                <h5>Essential</h5>
                                <p>Required for basic site functionality. Cannot be disabled.</p>
                            </div>
                            <div class="cookie-type-card">
                                <div class="ctc-icon"><i class="fa fa-bar-chart"></i></div>
                                <h5>Performance</h5>
                                <p>Help us understand how visitors use our site anonymously.</p>
                            </div>
                            <div class="cookie-type-card">
                                <div class="ctc-icon"><i class="fa fa-sliders"></i></div>
                                <h5>Functional</h5>
                                <p>Remember your choices for an improved, personalized experience.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="section4" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-toggle-on"></i></div>
                        <h4>4. Managing Cookies</h4>
                    </div>
                    <div class="pc-body">
                        <p>You have the right to control cookies. You can:</p>
                        <ul>
                            <li>Accept or reject cookies via your browser settings</li>
                            <li>Delete cookies already stored on your device</li>
                            <li>Set your browser to block cookies from specific websites</li>
                        </ul>
                        <p style="color:#888;font-size:13px;margin-top:8px;"><i class="fa fa-info-circle" style="color:#f57c00;margin-right:5px;"></i> Please note that disabling certain cookies may affect website functionality and limit some features.</p>
                    </div>
                </div>

                <div id="section5" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-refresh"></i></div>
                        <h4>5. Updates to This Policy</h4>
                    </div>
                    <div class="pc-body">
                        <p>We may update this Cookies Policy as needed to reflect changes in technology, legislation, or our business practices. Any updates will be posted on this page with a revised effective date.</p>
                    </div>
                </div>

                <div id="section6" class="policy-contact-card">
                    <h4><i class="fa fa-envelope" style="margin-right:8px;"></i> Contact Us</h4>
                    <p style="color:rgba(255,255,255,.8);font-size:13.5px;margin-bottom:14px;">Questions about our use of cookies? Get in touch:</p>
                    <div class="cc-item"><i class="fa fa-building"></i> <strong>Canham College, Inc.</strong> dba ProfessionalOnlineEducation.com</div>
                    <div class="cc-item"><i class="fa fa-map-marker"></i> 2934 E Avalon Drive, Phoenix, AZ 85016</div>
                    <div class="cc-item"><i class="fa fa-phone"></i> Toll Free: <a href="tel:18889003382">1-888-900-3382</a></div>
                    <div class="cc-item"><i class="fa fa-envelope"></i> <a href="mailto:mgr@professionalonlineeducation.com" style="color:#fff;text-decoration:underline;">mgr@professionalonlineeducation.com</a></div>
                    <div class="cc-item"><i class="fa fa-clock-o"></i> Monday &ndash; Friday, 8:00 AM &ndash; 5:00 PM</div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
(function(){
    var sections = document.querySelectorAll('[id^="section"]');
    var links    = document.querySelectorAll('.policy-toc a');
    window.addEventListener('scroll', function(){
        var scrollY = window.pageYOffset + 120;
        sections.forEach(function(sec){
            if(sec.offsetTop <= scrollY && sec.offsetTop + sec.offsetHeight > scrollY){
                links.forEach(function(l){ l.classList.remove('active'); });
                var active = document.querySelector('.policy-toc a[href="#'+sec.id+'"]');
                if(active) active.classList.add('active');
            }
        });
    });
})();
</script>

@endsection
