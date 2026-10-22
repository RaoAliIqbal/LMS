@extends('layouts.web')
@section('title','Refund & Tuition Waiver Policy | Professional Online Education')
@section('keywords','Refund Policy, Tuition Waiver, Professional Online Education, Canham College, cancellation, withdrawal')
@section('description','Read the Refund and Tuition Waiver Policy for Professional Online Education (Canham College). Understand your rights, the 48-hour guarantee, and withdrawal procedures.')
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
.policy-card .pc-body p { font-size:14px;line-height:1.8;color:#444;margin-bottom:12px; }
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
.policy-card .pc-body a { color:#0877b8;font-weight:600; }
.policy-card .pc-body a:hover { color:#054f7a; }

/* Highlight boxes */
.highlight-box-blue {
    background:#e8f4fc;border-left:4px solid #0877b8;
    border-radius:0 8px 8px 0;
    padding:14px 18px;margin:14px 0;
    font-size:13.5px;color:#054f7a;font-weight:600;
    line-height:1.7;
}
.highlight-box-amber {
    background:#fff8e1;border-left:4px solid #ffa000;
    border-radius:0 8px 8px 0;
    padding:14px 18px;margin:14px 0;
    font-size:13.5px;color:#6d4c00;font-weight:500;
    line-height:1.7;
}

/* Withdrawal form card */
.withdrawal-form-card {
    background:#f4f9fd;border:2px dashed #b3d9f2;
    border-radius:14px;padding:24px 28px;margin:18px 0;
}
.withdrawal-form-card h5 {
    font-size:14px;font-weight:700;color:#054f7a;
    margin:0 0 14px;display:flex;align-items:center;gap:8px;
}
.withdrawal-form-card .wf-row {
    display:flex;gap:10px;align-items:flex-start;
    margin-bottom:10px;font-size:13.5px;color:#444;line-height:1.7;
}
.withdrawal-form-card .wf-label {
    font-weight:700;color:#0877b8;min-width:110px;flex-shrink:0;
}
.withdrawal-form-card .wf-divider {
    border:none;border-top:1px solid #c2e0f4;margin:14px 0;
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
    .withdrawal-form-card .wf-row { flex-direction:column;gap:2px; }
}
</style>

{{-- Hero --}}
<div class="policy-hero">
    <div class="container ph-inner">
        <div class="ph-badge"><i class="fa fa-shield"></i> Legal</div>
        <h1><i class="fa fa-rotate-left" style="font-size:28px;margin-right:10px;opacity:.9;"></i>Refund &amp; Tuition Waiver Policy</h1>
        <div class="ph-meta">
            <span><i class="fa fa-calendar"></i> Last Updated: April 30, 2026</span>
            <span><i class="fa fa-building"></i> Canham College, Inc.</span>
        </div>
    </div>
</div>

{{-- Breadcrumb --}}
<div class="policy-breadcrumb">
    <div class="container">
        <a href="{{ route('home') }}">Home</a>
        <span class="sep">/</span>
        <span style="color:#555;">Refund &amp; Tuition Waiver Policy</span>
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
                            <li><a href="#section1"><span class="toc-num">1</span> Our Commitment</a></li>
                            <li><a href="#section2"><span class="toc-num">2</span> 48-Hour Guarantee</a></li>
                            <li><a href="#section3"><span class="toc-num">3</span> Tuition Waiver / Credit</a></li>
                            <li><a href="#section4"><span class="toc-num">4</span> Withdrawal Form</a></li>
                            <li><a href="#section5"><span class="toc-num">5</span> EU Residents</a></li>
                            <li><a href="#section6"><span class="toc-num">6</span> Contact Us</a></li>
                        </ul>
                    </div>
                    <br>
                    <a href="javascript:window.print()" class="policy-print-btn"><i class="fa fa-print"></i> Print this page</a>
                </div>
            </div>

            {{-- Content --}}
            <div class="col-md-9">

                {{-- Intro --}}
                <div class="policy-intro-card">
                    <div class="pic-icon"><i class="fa fa-shield"></i></div>
                    <p>Canham College's commitment is to provide high-quality educational content. If you find that our service does not meet your expectations, we offer a transparent refund process designed to be fair to both our users and our course creators.</p>
                </div>

                {{-- Section 1 --}}
                <div id="section1" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-heart"></i></div>
                        <h4>1. Our Commitment</h4>
                    </div>
                    <div class="pc-body">
                        <p>At Canham College, we are dedicated to delivering high-quality, accessible online education. We understand that choosing the right course is important, and we want you to feel confident in every purchase you make on our platform.</p>
                        <p>Our refund process is transparent and designed to be fair to both students and course creators. We strive for 100% satisfaction and handle every request with care and professionalism.</p>
                    </div>
                </div>

                {{-- Section 2 --}}
                <div id="section2" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-clock-o"></i></div>
                        <h4>2. The 48-Hour Guarantee</h4>
                    </div>
                    <div class="pc-body">
                        <div class="highlight-box-blue">
                            <i class="fa fa-check-circle" style="margin-right:7px;"></i>
                            We provide a <strong>full refund</strong> for any course purchased if the request is made within <strong>48 hours</strong> of the transaction, and you have not completed more than <strong>one (1) lesson</strong> in any module.
                        </div>
                        <p>To initiate a refund within the first 48 hours, simply send a brief email to <a href="mailto:mgr@professionalonlineeducation.com">mgr@professionalonlineeducation.com</a>.</p>
                        <p>While we strive for 100% satisfaction, we reserve the right to decline refund requests unless our guaranteed conditions are fulfilled or if mandated by law.</p>
                        <div class="highlight-box-amber">
                            <i class="fa fa-exclamation-triangle" style="margin-right:7px;"></i>
                            <strong>Please note:</strong> Refund eligibility requires that no more than one (1) lesson has been completed in any module at the time of the request.
                        </div>
                    </div>
                </div>

                {{-- Section 3 --}}
                <div id="section3" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-exchange"></i></div>
                        <h4>3. Non-Usage Tuition Waiver / Credit</h4>
                    </div>
                    <div class="pc-body">
                        <p>If you have purchased a course but have <strong>not yet interacted with the product</strong> — defined as zero progress in your chosen course and no activity beyond the initial onboarding — you are eligible for a <strong>full credit</strong> to be applied to another course or student within <strong>183 days</strong> of the date of purchase.</p>
                        <p>To initiate a tuition waiver in the amount of your original purchase, you must notify Canham College, Inc. in writing that you would like to exercise your right to withdraw, and give written notice by email within <strong>183 days</strong> of the date of purchase.</p>
                        <ul>
                            <li>Complete the Withdrawal Form below</li>
                            <li>Email Canham College, Inc. at <a href="mailto:mgr@professionalonlineeducation.com">mgr@professionalonlineeducation.com</a></li>
                            <li>Your notice must include an unequivocal statement of your decision to withdraw</li>
                            <li>To meet the withdrawal deadline, your communication must be sent <strong>before</strong> the withdrawal period expires</li>
                        </ul>
                    </div>
                </div>

                {{-- Section 4 — Withdrawal Form --}}
                <div id="section4" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-file-text-o"></i></div>
                        <h4>4. Withdrawal Form</h4>
                    </div>
                    <div class="pc-body">
                        <p>You may use the withdrawal form below to notify us of your decision. Submission of this form is <strong>not obligatory</strong> — a written email to the address below is sufficient.</p>

                        <div class="withdrawal-form-card">
                            <h5><i class="fa fa-building" style="color:#0877b8;"></i> Canham College, Inc. — Withdrawal Form</h5>
                            <hr class="wf-divider">
                            <div class="wf-row">
                                <span class="wf-label">To:</span>
                                <span><a href="mailto:mgr@professionalonlineeducation.com">mgr@professionalonlineeducation.com</a></span>
                            </div>
                            <div class="wf-row">
                                <span class="wf-label">Statement:</span>
                                <span><em>"I hereby give notice that I withdraw from my contract of the following service:"</em></span>
                            </div>
                            <hr class="wf-divider">
                            <div class="wf-row"><span class="wf-label">Received on:</span><span>________________</span></div>
                            <div class="wf-row"><span class="wf-label">Name:</span><span>________________</span></div>
                            <div class="wf-row"><span class="wf-label">Address:</span><span>________________</span></div>
                            <div class="wf-row">
                                <span class="wf-label">Signature:</span>
                                <span>________________ <em style="font-size:12px;color:#888;">(required only if sent by post mail)</em></span>
                            </div>
                            <div class="wf-row"><span class="wf-label">Date:</span><span>________________</span></div>
                        </div>

                        <p>To initiate a refund, simply send a brief email to <a href="mailto:mgr@professionalonlineeducation.com">mgr@professionalonlineeducation.com</a>.</p>
                    </div>
                </div>

                {{-- Section 5 — EU --}}
                <div id="section5" class="policy-card">
                    <div class="pc-head">
                        <div class="pc-icon"><i class="fa fa-globe"></i></div>
                        <h4>5. Note for EU Residents</h4>
                    </div>
                    <div class="pc-body">
                        <p>If you are a consumer based in the <strong>EEA or Switzerland</strong>, you have an automatic legal right to withdraw from contracts for purchases of Services.</p>
                        <div class="highlight-box-amber">
                            <i class="fa fa-info-circle" style="margin-right:7px;"></i>
                            However, when you make a purchase of a <strong>single item of digital content</strong> (such as a video recording or a PDF file), you expressly agree that such content is made available to you immediately. You therefore <strong>lose your right of withdrawal</strong> and will not be eligible for a refund.
                        </div>
                    </div>
                </div>

                {{-- Contact --}}
                <div id="section6" class="policy-contact-card">
                    <h4><i class="fa fa-envelope" style="margin-right:8px;"></i> Contact Us</h4>
                    <p style="color:rgba(255,255,255,.8);font-size:13.5px;margin-bottom:14px;">To initiate a refund or tuition waiver, or if you have any questions about this policy:</p>
                    <div class="cc-item"><i class="fa fa-building"></i> <strong>Canham College, Inc.</strong> dba ProfessionalOnlineEducation.com</div>
                    <div class="cc-item"><i class="fa fa-map-marker"></i> 2934 E Avalon Drive, Phoenix, AZ 85016</div>
                    <div class="cc-item"><i class="fa fa-phone"></i> Toll Free: <a href="tel:18889003382">1-888-900-3382</a></div>
                    <div class="cc-item"><i class="fa fa-envelope"></i> <a href="mailto:mgr@professionalonlineeducation.com" style="color:#90c4e8;text-decoration:underline;">mgr@professionalonlineeducation.com</a></div>
                    <div class="cc-item"><i class="fa fa-clock-o"></i> Monday &ndash; Friday, 8:00 AM &ndash; 5:00 PM</div>
                </div>

            </div>{{-- end col-md-9 --}}
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
