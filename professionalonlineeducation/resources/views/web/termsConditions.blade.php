@extends('layouts.web')
@section('title','Terms & Conditions | Professional Online Education')
@section('keywords','Terms and Conditions, Professional Online Education, Canham College, terms of use, legal')
@section('description','Read the Terms and Conditions for using the Professional Online Education website and its courses and services.')
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
.policy-sidebar { position:sticky;top:90px; }
.policy-toc {
    background:#fff;border-radius:14px;
    border:1px solid #e3f2fd;overflow:hidden;
    box-shadow:0 2px 16px rgba(8,119,184,.08);
}
.policy-toc .toc-head {
    background:linear-gradient(135deg,#054f7a,#1a99d6);
    color:#fff;padding:14px 18px;
    font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;
    display:flex;align-items:center;gap:8px;
}
.policy-toc ul { padding:10px 0;margin:0; }
.policy-toc ul li { list-style:none!important;padding:0;margin:0; }
.policy-toc ul li a {
    display:flex;align-items:center;gap:9px;
    padding:9px 18px;font-size:12.5px;color:#444;font-weight:500;
    text-decoration:none;border-left:3px solid transparent;transition:all .2s;
}
.policy-toc ul li a:hover,
.policy-toc ul li a.active {
    background:#e8f4fc;color:#054f7a;border-left-color:#1a99d6;font-weight:600;
}
.policy-toc ul li a .toc-num {
    width:20px;height:20px;border-radius:50%;
    background:#e3f2fd;color:#0877b8;font-size:10px;font-weight:700;
    display:flex;align-items:center;justify-content:center;flex-shrink:0;
}
.policy-toc ul li a:hover .toc-num,
.policy-toc ul li a.active .toc-num { background:#0877b8;color:#fff; }

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
.policy-card .pc-body .highlight-box {
    background:#fff8e1;border-left:4px solid #ffa000;
    border-radius:0 8px 8px 0;padding:12px 16px;margin:12px 0;
    font-size:13.5px;color:#e65100;font-weight:600;
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
}
</style>

{{-- Hero --}}
<div class="policy-hero">
    <div class="container ph-inner">
        <div class="ph-badge"><i class="fa fa-gavel"></i> Legal</div>
        <h1><i class="fa fa-file-text" style="font-size:28px;margin-right:10px;opacity:.9;"></i>Terms &amp; Conditions</h1>
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
        <span style="color:#555;">Terms &amp; Conditions</span>
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
                            <li><a href="#section1"><span class="toc-num">1</span> Use of Website</a></li>
                            <li><a href="#section2"><span class="toc-num">2</span> Accounts &amp; Access</a></li>
                            <li><a href="#section3"><span class="toc-num">3</span> Courses &amp; Content</a></li>
                            <li><a href="#section4"><span class="toc-num">4</span> Payments &amp; Refunds</a></li>
                            <li><a href="#section5"><span class="toc-num">5</span> Intellectual Property</a></li>
                            <li><a href="#section6"><span class="toc-num">6</span> Limitation of Liability</a></li>
                            <li><a href="#section7"><span class="toc-num">7</span> Changes to Terms</a></li>
                            <li><a href="#section8"><span class="toc-num">8</span> Contact</a></li>
                        </ul>
                    </div>
                    <br>
                    <a href="javascript:window.print()" class="policy-print-btn"><i class="fa fa-print"></i> Print this page</a>
                </div>
            </div>

            {{-- Content --}}
            <div class="col-md-9">

                <div class="policy-intro-card">
                    <div class="pic-icon"><i class="fa fa-file-text"></i></div>
                    <p>Welcome to <strong>Professional Online Education</strong>. By using our website, you agree to follow these terms. If you do not agree, please stop using the site.</p>
                </div>

                <div id="section1" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-globe"></i></div>
                        <h4>1. Use of Website</h4>
                    </div>
                    <div class="pc-body">
                        <p>This website is intended to provide educational content, resources, and services. You agree to use it responsibly and not for any illegal or harmful activity.</p>
                        <p>You must not:</p>
                        <ul>
                            <li>Misuse or attempt to hack the website</li>
                            <li>Copy or redistribute content without permission</li>
                            <li>Use the site in a way that disrupts others</li>
                        </ul>
                    </div>
                </div>

                <div id="section2" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-user-circle"></i></div>
                        <h4>2. Accounts &amp; Access</h4>
                    </div>
                    <div class="pc-body">
                        <p>Some features may require you to create an account. You are responsible for:</p>
                        <ul>
                            <li>Keeping your login details secure</li>
                            <li>All activity that occurs under your account</li>
                        </ul>
                        <p>We reserve the right to suspend or remove accounts if misuse is detected.</p>
                    </div>
                </div>

                <div id="section3" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-graduation-cap"></i></div>
                        <h4>3. Courses &amp; Content</h4>
                    </div>
                    <div class="pc-body">
                        <p>All courses, materials, and content are for <strong>personal learning only</strong>.</p>
                        <p>You may not:</p>
                        <ul>
                            <li>Resell or share paid content</li>
                            <li>Download and distribute course materials</li>
                            <li>Use content for commercial purposes without explicit written permission</li>
                        </ul>
                    </div>
                </div>

                <div id="section4" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-credit-card"></i></div>
                        <h4>4. Payments &amp; Refunds</h4>
                    </div>
                    <div class="pc-body">
                        <p>If you purchase any course or service:</p>
                        <ul>
                            <li>Payments must be made in full before access is granted</li>
                            <li>We reserve the right to change pricing at any time</li>
                        </ul>
                        <div class="highlight-box"><i class="fa fa-info-circle"></i> Refunds are handled per our <a href="{{ route('refundPolicy') }}" style="color:#e65100;">Refund &amp; Tuition Waiver Policy</a>.</div>
                    </div>
                </div>

                <div id="section5" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-copyright"></i></div>
                        <h4>5. Intellectual Property</h4>
                    </div>
                    <div class="pc-body">
                        <p>All content on this website — including videos, text, graphics, and course materials — belongs to <strong>Professional Online Education (Canham College, Inc.)</strong> unless stated otherwise. Unauthorized use, reproduction, or distribution may lead to legal action.</p>
                    </div>
                </div>

                <div id="section6" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-exclamation-triangle"></i></div>
                        <h4>6. Limitation of Liability</h4>
                    </div>
                    <div class="pc-body">
                        <p>We do our best to provide accurate and helpful content, but we do not guarantee:</p>
                        <ul>
                            <li>Complete accuracy of all information</li>
                            <li>Specific results from completing courses</li>
                        </ul>
                        <p>You use this website at your own risk. We are not liable for any direct, indirect, or consequential losses arising from use of our services.</p>
                    </div>
                </div>

                <div id="section7" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-refresh"></i></div>
                        <h4>7. Changes to Terms</h4>
                    </div>
                    <div class="pc-body">
                        <p>We may update these Terms &amp; Conditions from time to time. Continued use of the website after any changes are posted constitutes your acceptance of the revised terms.</p>
                    </div>
                </div>

                <div id="section8" class="policy-contact-card">
                    <h4><i class="fa fa-envelope" style="margin-right:8px;"></i> Contact Us</h4>
                    <p style="color:rgba(255,255,255,.8);font-size:13.5px;margin-bottom:14px;">If you have any questions about these Terms &amp; Conditions, please contact us:</p>
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
