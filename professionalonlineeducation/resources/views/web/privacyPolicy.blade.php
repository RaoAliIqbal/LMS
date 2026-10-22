@extends('layouts.web')
@section('title','Privacy Policy | Professional Online Education')
@section('keywords','Privacy Policy, Professional Online Education, Canham College, data protection, personal data')
@section('description','Read the Privacy Policy of Professional Online Education. Learn how we collect, use, and protect your personal information.')
@section('content')

<style>
/* ── Hero ── */
.policy-hero {
    background: linear-gradient(135deg,#054f7a 0%,#0877b8 50%,#1a99d6 100%);
    padding: 60px 0 50px;
    position: relative;
    overflow: hidden;
}
.policy-hero::before {
    content:'';
    position:absolute;inset:0;
    background: url('/webAssets/images/banner-homepage.jpg') center/cover no-repeat;
    opacity:.12;
}
.policy-hero .ph-inner { position:relative;z-index:1; }
.policy-hero .ph-badge {
    display:inline-flex;align-items:center;gap:8px;
    background:rgba(255,255,255,.15);
    border:1px solid rgba(255,255,255,.3);
    color:#fff;font-size:12px;font-weight:600;
    padding:5px 14px;border-radius:50px;
    margin-bottom:14px;letter-spacing:.5px;
}
.policy-hero h1 {
    color:#fff;font-size:36px;font-weight:800;
    margin:0 0 10px;line-height:1.2;
}
.policy-hero .ph-meta {
    color:rgba(255,255,255,.8);font-size:13px;
    display:flex;align-items:center;gap:16px;flex-wrap:wrap;
}
.policy-hero .ph-meta span { display:flex;align-items:center;gap:5px; }

/* ── Breadcrumb ── */
.policy-breadcrumb {
    background:#e8f4fc;border-bottom:1px solid #c2e0f4;
    padding:10px 0;font-size:12.5px;
}
.policy-breadcrumb a { color:#0877b8;text-decoration:none; }
.policy-breadcrumb a:hover { text-decoration:underline; }
.policy-breadcrumb .sep { color:#aaa;margin:0 6px; }

/* ── Layout ── */
.policy-wrap { padding:40px 0 60px;background:#f4f9fd; }
.policy-sidebar {
    position:sticky;top:90px;
}
.policy-toc {
    background:#fff;border-radius:14px;
    border:1px solid #e3f2fd;
    overflow:hidden;
    box-shadow:0 2px 16px rgba(8,119,184,.08);
}
.policy-toc .toc-head {
    background:linear-gradient(135deg,#054f7a,#1a99d6);
    color:#fff;padding:14px 18px;
    font-size:12px;font-weight:700;letter-spacing:1px;
    text-transform:uppercase;
    display:flex;align-items:center;gap:8px;
}
.policy-toc ul { padding:10px 0;margin:0; }
.policy-toc ul li { list-style:none!important;padding:0;margin:0; }
.policy-toc ul li a {
    display:flex;align-items:center;gap:9px;
    padding:9px 18px;
    font-size:12.5px;color:#444;font-weight:500;
    text-decoration:none;border-left:3px solid transparent;
    transition:all .2s;
}
.policy-toc ul li a:hover,
.policy-toc ul li a.active {
    background:#e8f4fc;color:#054f7a;
    border-left-color:#1a99d6;font-weight:600;
}
.policy-toc ul li a .toc-num {
    width:20px;height:20px;border-radius:50%;
    background:#e3f2fd;color:#0877b8;
    font-size:10px;font-weight:700;
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;
}
.policy-toc ul li a:hover .toc-num,
.policy-toc ul li a.active .toc-num {
    background:#0877b8;color:#fff;
}

/* ── Content cards ── */
.policy-card {
    background:#fff;border-radius:14px;
    border:1px solid #e3f2fd;
    margin-bottom:20px;
    overflow:hidden;
    box-shadow:0 2px 12px rgba(8,119,184,.06);
    transition:box-shadow .2s;
}
.policy-card:hover { box-shadow:0 4px 24px rgba(8,119,184,.12); }
.policy-card .pc-head {
    display:flex;align-items:center;gap:14px;
    padding:16px 22px 14px;
    border-bottom:1px solid #e8f4fc;
    background:#f5fbff;
}
.policy-card .pc-icon {
    width:42px;height:42px;border-radius:10px;
    background:linear-gradient(135deg,#054f7a,#1a99d6);
    color:#fff;font-size:17px;
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;
}
.policy-card .pc-head h4 {
    margin:0;font-size:15px;font-weight:700;color:#054f7a;
    line-height:1.3;
}
.policy-card .pc-body { padding:18px 22px; }
.policy-card .pc-body p { font-size:14px;line-height:1.8;color:#444;margin-bottom:10px; }
.policy-card .pc-body p:last-child { margin-bottom:0; }
.policy-card .pc-body ul { margin:8px 0 10px 0;padding:0; }
.policy-card .pc-body ul li {
    list-style:none!important;
    padding:5px 0 5px 22px;
    font-size:14px;color:#444;line-height:1.7;
    position:relative;
}
.policy-card .pc-body ul li::before {
    content:'\f00c';font-family:FontAwesome;
    position:absolute;left:0;top:6px;
    color:#1a99d6;font-size:11px;
}
.policy-card .pc-body .highlight-box {
    background:#e8f4fc;border-left:4px solid #1a99d6;
    border-radius:0 8px 8px 0;
    padding:12px 16px;margin:12px 0;
    font-size:13.5px;color:#054f7a;font-weight:600;
}
.policy-card .pc-body .highlight-box i { margin-right:6px; }

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
    border-radius:14px;padding:24px 26px;margin-top:10px;
    color:#fff;
}
.policy-contact-card h4 { color:#fff;font-size:15px;font-weight:700;margin-bottom:12px; }
.policy-contact-card .cc-item {
    display:flex;align-items:center;gap:10px;
    margin-bottom:8px;font-size:13.5px;color:rgba(255,255,255,.9);
}
.policy-contact-card .cc-item i { width:18px;text-align:center;color:#90c4e8; }
.policy-contact-card a { color:#90c4e8;text-decoration:none; }
.policy-contact-card a:hover { color:#fff;text-decoration:underline; }

/* Print btn */
.policy-print-btn {
    display:inline-flex;align-items:center;gap:7px;
    background:#fff;border:1.5px solid #b3d9f2;
    color:#0877b8;font-size:12px;font-weight:600;
    padding:7px 16px;border-radius:50px;cursor:pointer;
    transition:all .2s;text-decoration:none;
    margin-top:8px;
}
.policy-print-btn:hover { background:#e3f2fd;border-color:#1a99d6;color:#054f7a;text-decoration:none; }

@media(max-width:768px){
    .policy-hero h1 { font-size:26px; }
    .policy-sidebar { position:static;margin-bottom:24px; }
}
</style>

{{-- Hero --}}
<div class="policy-hero">
    <div class="container ph-inner">
        <div class="ph-badge"><i class="fa fa-shield"></i> Legal</div>
        <h1><i class="fa fa-lock" style="font-size:28px;margin-right:10px;opacity:.9;"></i>Privacy Policy</h1>
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
        <span style="color:#555;">Privacy Policy</span>
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
                            <li><a href="#section1"><span class="toc-num">1</span> Information We Collect</a></li>
                            <li><a href="#section2"><span class="toc-num">2</span> How We Use Your Info</a></li>
                            <li><a href="#section3"><span class="toc-num">3</span> Cookies &amp; Tracking</a></li>
                            <li><a href="#section4"><span class="toc-num">4</span> Third-Party Services</a></li>
                            <li><a href="#section5"><span class="toc-num">5</span> Data Protection</a></li>
                            <li><a href="#section6"><span class="toc-num">6</span> Your Rights</a></li>
                            <li><a href="#section7"><span class="toc-num">7</span> Policy Updates</a></li>
                            <li><a href="#section8"><span class="toc-num">8</span> Contact Us</a></li>
                        </ul>
                    </div>
                    <br>
                    <a href="javascript:window.print()" class="policy-print-btn"><i class="fa fa-print"></i> Print this page</a>
                </div>
            </div>

            {{-- Content --}}
            <div class="col-md-9">

                <div class="policy-intro-card">
                    <div class="pic-icon"><i class="fa fa-lock"></i></div>
                    <p>Your privacy matters to us. This policy explains how <strong>ProfessionalOnlineEducation.com</strong> collects, uses, and protects your information when you visit or use our website and services.</p>
                </div>

                <div id="section1" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-database"></i></div>
                        <h4>1. Information We Collect</h4>
                    </div>
                    <div class="pc-body">
                        <p>We may collect the following types of information when you use our website:</p>
                        <ul>
                            <li>Name, email address, and phone number</li>
                            <li>Account details (username, password)</li>
                            <li>Payment information — processed securely via trusted third-party providers</li>
                            <li>Website usage data (cookies, analytics, IP address, browser type)</li>
                        </ul>
                    </div>
                </div>

                <div id="section2" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-cogs"></i></div>
                        <h4>2. How We Use Your Information</h4>
                    </div>
                    <div class="pc-body">
                        <p>We use your data to:</p>
                        <ul>
                            <li>Provide access to courses and services</li>
                            <li>Improve our website and user experience</li>
                            <li>Send updates, receipts, or important account information</li>
                            <li>Respond to your inquiries and support requests</li>
                        </ul>
                        <div class="highlight-box"><i class="fa fa-ban"></i> We do <strong>not sell</strong> your personal data to any third parties.</div>
                    </div>
                </div>

                <div id="section3" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-cookie" style="font-size:15px;">🍪</i></div>
                        <h4>3. Cookies &amp; Tracking</h4>
                    </div>
                    <div class="pc-body">
                        <p>We use cookies to:</p>
                        <ul>
                            <li>Improve user experience</li>
                            <li>Track website performance</li>
                            <li>Understand user behavior and traffic patterns</li>
                        </ul>
                        <p>You can disable cookies in your browser settings at any time. For more details, see our <a href="{{ route('cookiesPolicy') }}" style="color:#0877b8;font-weight:600;">Cookies Policy</a>.</p>
                    </div>
                </div>

                <div id="section4" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-handshake-o"></i></div>
                        <h4>4. Third-Party Services</h4>
                    </div>
                    <div class="pc-body">
                        <p>We may use trusted third-party services such as payment gateways, analytics tools, and email platforms. These providers handle data in accordance with their own privacy policies. We ensure we only work with reputable and compliant service providers.</p>
                    </div>
                </div>

                <div id="section5" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-shield"></i></div>
                        <h4>5. Data Protection</h4>
                    </div>
                    <div class="pc-body">
                        <p>We take reasonable technical and organizational steps to protect your personal information from unauthorized access, loss, or disclosure. However, no system is 100% secure, and we cannot guarantee absolute security of data transmitted over the internet.</p>
                    </div>
                </div>

                <div id="section6" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-user-circle"></i></div>
                        <h4>6. Your Rights</h4>
                    </div>
                    <div class="pc-body">
                        <p>You have the right to:</p>
                        <ul>
                            <li>Request access to the personal data we hold about you</li>
                            <li>Ask for corrections to inaccurate or incomplete data</li>
                            <li>Request deletion of your data (where applicable under law)</li>
                            <li>Withdraw consent for marketing communications at any time</li>
                        </ul>
                        <p>To exercise any of these rights, please contact us using the details below.</p>
                    </div>
                </div>

                <div id="section7" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-refresh"></i></div>
                        <h4>7. Updates to This Policy</h4>
                    </div>
                    <div class="pc-body">
                        <p>We may update this Privacy Policy occasionally to reflect changes in our practices or legal requirements. Any changes will be posted on this page with a revised effective date. We encourage you to review this page periodically.</p>
                    </div>
                </div>

                <div id="section8" class="policy-contact-card">
                    <h4><i class="fa fa-envelope" style="margin-right:8px;"></i> Contact Us</h4>
                    <p style="color:rgba(255,255,255,.8);font-size:13.5px;margin-bottom:14px;">If you have any questions or concerns about this Privacy Policy, please reach out:</p>
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
// Active TOC link on scroll
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
