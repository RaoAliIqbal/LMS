@extends('layouts.web')

@section('title','Unlock Your Potential | Professional Online Education')
@section('keywords','Professional Online Education')
@section('description','Looking to advance your career in chiropractic? Don’t worry Professional Online Education program offers comprehensive training to help you reach your goals.')

@section('content')

<section class="mainbanner main-text1" data-aos="fade-up">
    <img src="{{ url('webAssets/images/banner-homepage.jpg') }}" alt="">
    <div class="caption">
        <div class="container">
            <div class="col-md-7">
                <h2>We Offer Quality</h2>
                <h6>Chiropractic Assistant Training,
                    Chiropractic and
                    Professional Development
                    Courses</h6>

                @guest
                    <a href="{{ route('register') }}" class="btn pink">Enroll now for FREE!</a>
                @else
                    {{-- Logged-in user --}}
                    <a href="{{ url('/user/dashboard') }}" class="btn pink">Go to Dashboard</a>
                @endguest

            </div>
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{-- NEW: Leader Intro Section (placed below the hero banner)     --}}
{{-- ============================================================ --}}
<section class="leader-intro" data-aos="fade-up">
    <div class="container">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <h2 class="uppercase leader-intro-heading">
                    Leader in Chiropractic Assistant Certification, Continuing Education, and Team Development.
                </h2>
                <p class="leader-intro-tagline">&ldquo;Built From Experience. Driven by Excellence.&rdquo;</p>

                <p class="leader-intro-copy">
                    Founded in 2002 by <strong>Dr. Rosalind Canham, DC</strong>, Professional Online Education was
                    created from real-world chiropractic experience&mdash;not theory. Beginning her career as a
                    Chiropractic Assistant, Dr. Canham experienced firsthand the challenges of learning without
                    formal training. Inspired to raise the standard of Chiropractic Assistant education, she became
                    a Doctor of Chiropractic, built and led successful chiropractic practices, developed
                    high-performing teams, and created a comprehensive education pathway for today&rsquo;s
                    chiropractic professionals.
                </p>

                <p class="leader-intro-copy">
                    Today, Professional Online Education is a nationally recognized leader in
                    <strong>Chiropractic Assistant certification, continuing education, and leadership
                    training</strong>. Backed by nearly 30 years of chiropractic team development experience, our
                    programs have helped educate more than <strong>12,000 students</strong> through state-specific
                    and national training designed to build confident, skilled, and successful chiropractic teams.
                </p>

                <div class="leader-cta-group">
                    <a href="{{ route('coursesPage',['category_id'=>'2']) }}" class="leader-cta-btn">Explore CA Certification Programs</a>
                    <a href="{{ route('register') }}" class="leader-cta-btn">Start Your Training Today</a>

                    @guest
                        <a href="{{ route('register') }}" class="leader-cta-btn">Enroll now for FREE!</a>
                    @else
                        {{-- Logged-in user --}}
                        <a href="{{ url('/user/dashboard') }}" class="leader-cta-btn">Go to Dashboard</a>
                    @endguest
                </div>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
</section>

<style>
    /* ── Leader Intro Section ─────────────────────────────── */
    .leader-intro {
        background: #ffffff;
        padding: 55px 0 45px;
        font-family: inherit;
    }
    .leader-intro-heading {
        text-align: center;
        color: #1a2e44;
        font-weight: 800;
        font-size: 26px;
        line-height: 1.45;
        margin-bottom: 14px;
        font-family: inherit;
    }
    .leader-intro-tagline {
        text-align: center;
        font-style: italic;
        font-weight: 700;
        color: #1379ba;
        font-size: 18px;
        margin-bottom: 26px;
        font-family: inherit;
    }
    .leader-intro-copy {
    font-style: normal;
    color: #000000;
    font-size: 15px;
    line-height: 1.8;
    max-width: 900px;
    margin: 0 auto 18px;
    text-align: center;
    font-family: 'Mulish', sans-serif;
}
    .leader-cta-group {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        justify-content: center;
        align-items: center;
        margin-top: 26px;
    }
    .leader-cta-btn {
        display: inline-block;
        padding: 12px 26px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 14px;
        letter-spacing: .3px;
        text-decoration: none !important;
        background: #ffffff;
        color: #1379ba;
        border: 2px solid #1379ba;
        transition: all .25s ease;
        font-family: inherit;
    }
    .leader-cta-btn:hover {
        background: #1379ba;
        color: #ffffff;
        text-decoration: none;
    }
    @media (max-width: 575px) {
        .leader-cta-group { flex-direction: column; }
        .leader-cta-group .leader-cta-btn,
        .leader-cta-group .btn.pink { width: 100%; text-align: center; }
    }
</style>

    <section class="about center about-text1">
        <div class="container">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10 about-text" data-aos="fade-up">
                    <h2 class="uppercase">INNOVATIVE, CUSTOMER FOCUSED COURSES <br>
                        FROM EXPERIENCED INSTRUCTORS</h2>
                    <p>Canham College, Inc. has proudly provided quality online educational courses since 2007.  Trust our experience with over 246,000 hours of online learning completed.  Our
                        affordable courses have been chosen by thousands of students and employers to meet their training and continuing education needs.  </p>
                </div>
            </div>
        </div>
    </section>

    @if(count($courses))
        <section class="choosecourse" style="background:#f0f6fc; padding:60px 0;">
            <div class="container">

                {{-- Section header --}}
                <div style="text-align:center; margin-bottom:40px;" data-aos="fade-up">
                    <h2 style="color:#1a2e44; font-weight:800; margin-bottom:10px;">Choose a Profession</h2>
                    <p style="color:#64748b; font-size:15px; margin:0;">Select your field below to explore relevant courses</p>
                    <div style="width:60px; height:4px; background:#1379ba; border-radius:4px; margin:14px auto 0;"></div>
                </div>

                {{-- Profession Cards --}}
                <div class="prof-grid" data-aos="fade-up">

                    <a href="{{ route('coursesPage',['category_id'=>'2']) }}" class="prof-card">
                        <div class="prof-img-wrap">
                            <img src="{{ url('webAssets/images/Chiropractic-Assistant-Initial-Training.jpg') }}" alt="Chiropractic Assistant Initial Training">
                            <div class="prof-overlay"><i class="fa fa-arrow-right"></i></div>
                        </div>
                        <div class="prof-label">Chiropractic Assistant Initial Training</div>
                    </a>

                    <a href="{{ route('coursesPage',['category_id'=>'3']) }}" class="prof-card">
                        <div class="prof-img-wrap">
                            <img src="{{ url('webAssets/images/Chiropractor-CE-new.jpg') }}" alt="Chiropractor CE">
                            <div class="prof-overlay"><i class="fa fa-arrow-right"></i></div>
                        </div>
                        <div class="prof-label">Chiropractor CE</div>
                    </a>

                    <a href="{{ route('coursesPage',['category_id'=>'4']) }}" class="prof-card">
                        <div class="prof-img-wrap">
                            <img src="{{ url('webAssets/images/Massage-Therapy-CE.jpg') }}" alt="Massage Therapy CE">
                            <div class="prof-overlay"><i class="fa fa-arrow-right"></i></div>
                        </div>
                        <div class="prof-label">Massage Therapy CE</div>
                    </a>

                    <a href="{{ route('coursesPage',['category_id'=>'5']) }}" class="prof-card">
                        <div class="prof-img-wrap">
                            <img src="{{ url('webAssets/images/General-Public.jpg') }}" alt="CPR">
                            <div class="prof-overlay"><i class="fa fa-arrow-right"></i></div>
                        </div>
                        <div class="prof-label">CPR</div>
                    </a>

                    <a href="{{ route('eventsPage') }}" class="prof-card">
                        <div class="prof-img-wrap">
                            <img src="{{ url('webAssets/images/Live-Events.jpg') }}" alt="Live Events">
                            <div class="prof-overlay"><i class="fa fa-arrow-right"></i></div>
                        </div>
                        <div class="prof-label">Live Events</div>
                    </a>

                    <a href="{{ route('coursesPage',['category_id'=>'1']) }}" class="prof-card">
                        <div class="prof-img-wrap">
                            <img src="{{ url('webAssets/images/Chiropractic-Assistant.jpg') }}" alt="Chiropractic Assistant CE">
                            <div class="prof-overlay"><i class="fa fa-arrow-right"></i></div>
                        </div>
                        <div class="prof-label">Chiropractic Assistant CE</div>
                    </a>

                    <a href="{{ route('coursesPage',['category_id'=>'6']) }}" class="prof-card">
                        <div class="prof-img-wrap">
                            <img src="{{ url('webAssets/images/Behavioral-Health.jpg') }}" alt="Behavioral Health">
                            <div class="prof-overlay"><i class="fa fa-arrow-right"></i></div>
                        </div>
                        <div class="prof-label">Behavioral Health</div>
                    </a>

                    <a href="{{ route('coursesPage',['category_id'=>'7']) }}" class="prof-card">
                        <div class="prof-img-wrap">
                            <img src="{{ url('webAssets/images/Certified-Nursing-Assistant.jpg') }}" alt="Certified Nursing Assistant">
                            <div class="prof-overlay"><i class="fa fa-arrow-right"></i></div>
                        </div>
                        <div class="prof-label">Certified Nursing Assistant</div>
                    </a>

                    <a href="{{ route('coursesPage') }}" class="prof-card">
                        <div class="prof-img-wrap">
                            <img src="{{ url('webAssets/images/Peer-Support-Certification.jpg') }}" alt="Peer Support Certification">
                            <div class="prof-overlay"><i class="fa fa-arrow-right"></i></div>
                        </div>
                        <div class="prof-label">Peer Support Certification</div>
                    </a>

                    <a href="{{ route('coursesPage') }}" class="prof-card">
                        <div class="prof-img-wrap">
                            <img src="{{ url('webAssets/images/Certified-Caregiver.jpg') }}" alt="Certified Caregiver">
                            <div class="prof-overlay"><i class="fa fa-arrow-right"></i></div>
                        </div>
                        <div class="prof-label">Certified Caregiver</div>
                    </a>

                </div>{{-- /.prof-grid --}}

                <style>
                
section.choose a.btn.pink {
    display: table;
    margin: 0 auto;
}
                .prof-grid {
                    display: grid;
                    grid-template-columns: repeat(5, 1fr);
                    gap: 20px;
                }
                .prof-card {
                    text-decoration: none !important;
                    background: #fff;
                    border-radius: 14px;
                    overflow: hidden;
                    box-shadow: 0 3px 14px rgba(0,0,0,.08);
                    transition: transform .25s ease, box-shadow .25s ease;
                    display: flex;
                    flex-direction: column;
                }
                .prof-card:hover {
                    transform: translateY(-6px);
                    box-shadow: 0 10px 30px rgba(19,121,186,.2);
                    text-decoration: none !important;
                }
                .prof-img-wrap {
                    position: relative;
                    width: 100%;
                    padding-top: 68%;
                    overflow: hidden;
                }
                .prof-img-wrap img {
                    position: absolute;
                    inset: 0;
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform .35s ease;
                }
                .prof-card:hover .prof-img-wrap img {
                    transform: scale(1.07);
                }
                .prof-overlay {
                    position: absolute;
                    inset: 0;
                    background: rgba(19,121,186,.45);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    opacity: 1;
                    transition: background .25s ease;
                }
                .prof-card:hover .prof-overlay {
                    background: rgba(19,121,186,.65);
                }
                .prof-overlay i {
                    color: #fff;
                    font-size: 22px;
                    background: rgba(255,255,255,.2);
                    border: 2px solid #fff;
                    border-radius: 50%;
                    width: 44px;
                    height: 44px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    opacity: 0;
                    transition: opacity .25s ease;
                }
                .prof-card:hover .prof-overlay i { opacity: 1; }
                .prof-label {
                    padding: 12px 14px 14px;
                    font-size: 13px;
                    font-weight: 700;
                    color: #1a2e44;
                    text-align: center;
                    line-height: 1.4;
                    text-transform: uppercase;
                    letter-spacing: .3px;
                    border-top: 3px solid transparent;
                    transition: border-color .25s, color .25s;
                    flex: 1;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .prof-card:hover .prof-label {
                    border-top-color: #1379ba;
                    color: #1379ba;
                }
                @media (max-width: 991px) {
                    .prof-grid { grid-template-columns: repeat(3, 1fr); }
                }
                @media (max-width: 575px) {
                    .prof-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
                }
                </style>

                    </div>
                </div>


                {{--            <div class="row">--}}
                {{--                @foreach($courses as $course)--}}
                {{--                    <div class="col-md-4" data-aos="fade-left">--}}
                {{--                        <div class="coursebox">--}}
                {{--                            <a href="{{ route('courseDetailsPage',$course->id) }}">--}}
                {{--                                <img src="{{ $course->image != '' ? url($course->image):'' }}" alt="" />--}}
                {{--                                <strong>{{ $course->title }}</strong>--}}
                {{--                            </a>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                @endforeach--}}
                {{--            </div>--}}
            </div>
        </section>
    @endif
    <section class="choose">
        <div class="container">
            <h2>Why Choose Professional Online Education?</h2>

            <p class="why-choose-intro">
                Professional Online Education is a nationally recognized provider of Chiropractic Assistant
                certification, continuing education, and leadership training. Founded by Dr. Rosalind Canham, DC,
                our programs are built on nearly 40 years of chiropractic team development experience.
            </p>

            <ul class="why-choose-checklist">
                <li><i class="fa fa-check"></i> 12,000+ students trained</li>
                <li><i class="fa fa-check"></i> State-specific and national programs available</li>
                <li><i class="fa fa-check"></i> FCLB PACE-approved National Program</li>
                <li><i class="fa fa-check"></i> Serving Chiropractic Assistants and Doctors of Chiropractic</li>
            </ul>

            <div class="row">
                <div class="col-md-3" data-aos="fade-left">
                    <div class="choosebox">
                        <h4>Convenience</h4>
                        <ul>
                            <li>Start Immediately</li>
                            <li>Accessible anywhere with internet access</li>
                            <li>View course on computer, tablet, or cell phone </li>
                            <li>Print Certificate upon completion</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up">
                    <div class="choosebox">
                        <h4>Flexibility</h4>
                        <ul>
                            <li>Available 24/7</li>
                            <li>Self-Paced</li>
                            <li>Easy to use</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up">
                    <div class="choosebox">
                        <h4>Saves</h4>
                        <ul>
                            <li>Time-log in anywhere anytime</li>
                            <li>Money-No travel expenses</li>
                            <li>Affordable, low cost courses</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-right">
                    <div class="choosebox">
                        <h4>Trust</h4>
                        <ul>
                            <li> Completion rate of 97% </li>
                            <li> Training students since 2002 </li>
                            <li> Over 10,000 professionals trained </li>
                            <li> Security Compliance </li>
                        </ul>
                    </div>
                </div>
            </div>
           @guest
                    <a href="{{ route('register') }}" class="btn pink">Enroll now for FREE!</a>
                @else
                    {{-- Logged-in user --}}
                    <a href="{{ url('/user/dashboard') }}" class="btn pink">Go to Dashboard</a>
                @endguest
        </div>
    </section>

    <style>
    /* ── Why Choose Us intro + checklist ──────────────────── */
    /* .choose section has a dark navy background, so these need light text for contrast */
    .why-choose-intro {
        max-width: 820px;
        margin: 0 auto 22px;
        text-align: center;
        color: rgba(255,255,255,.85);
        font-size: 15px;
        line-height: 1.8;
        font-family: inherit;
    }
    .why-choose-checklist {
        list-style: none;
        max-width: 760px;
        margin: 0 auto 40px;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px 30px;
    }
    .why-choose-checklist li {
        color: #ffffff;
        font-weight: 600;
        font-size: 14.5px;
        font-family: inherit;
    }
    .why-choose-checklist li i.fa-check {
        color: #4fc3f7;
        margin-right: 8px;
    }
    </style>

    <section class="approved center" style="padding: 60px 0; background: #f8fafc;">
        <div class="container" data-aos="fade-up">

            {{-- Section heading --}}
            <h2 class="uppercase" style="margin-bottom:10px;">We are Approved!</h2>
            <p style="max-width:820px; margin:0 auto 30px; color:#555; font-size:15px; line-height:1.8;">
                Canham College Inc. is approved by the Federation of Chiropractic Licensing Boards as a
                <strong>PACE approved provider</strong> — ensuring our CE courses promote quality, reliable,
                scientifically sound clinical protocols and educational programs.
            </p>

            {{-- PACE banner --}}
            <div class="pace-banner" data-aos="fade-up">
                <div class="pace-logo">
                    <img src="{{ url('webAssets/images/mini-logo1.png') }}" alt="PACE Approved Logo" />
                </div>
                <div class="pace-content">
                    <div class="pace-title">
                        <span class="pace-check"><i class="fa fa-check-circle"></i></span>
                        PACE Approved Provider
                    </div>
                    <p class="pace-desc">
                        Approved for all <strong>Chiropractic CE</strong> and <strong>Chiropractic Assistant Training</strong> courses in the following states:
                    </p>
                    <div class="pace-states">
                        @isset($paceStatePills)
                            @foreach($paceStatePills as $pill)
                                @if(!empty($pill['href']))
                                    <a href="{{ $pill['href'] }}" class="pace-state-pill" title="View courses for {{ $pill['code'] }}">{{ $pill['code'] }}</a>
                                @else
                                    <span class="pace-state-pill pace-state-pill--inactive" title="No matching state record">{{ $pill['code'] }}</span>
                                @endif
                            @endforeach
                        @else
                            @foreach(['AL','AK','AR','AZ','CO','DE','IA','ID','IL','KS','KY','MA','MD','ME','MN','MT','NE','ND','OH','OR','PA','RI','SD','WY'] as $st)
                                <span class="pace-state-pill">{{ $st }}</span>
                            @endforeach
                        @endisset
                    </div>
                </div>
            </div>

            {{-- State Board Cards --}}
            <h4 style="margin-bottom:24px; color:#1a2e44; font-weight:700; font-size:18px; letter-spacing:.3px;">
                Recognized &amp; Approved State Boards
            </h4>
            <div class="sb-grid">

                <a href="https://chiroboard.az.gov/chiropractic-assistants" target="_blank" class="sb-card" data-aos="fade-up">
                    <div class="sb-badge">AZ</div>
                    <div class="sb-body">
                        <div class="sb-name">Arizona Board of Chiropractic Examiners</div>
                        <div class="sb-sub">Approved for Chiropractic Assistant Training</div>
                    </div>
                    <div class="sb-arrow"><i class="fa fa-external-link"></i></div>
                </a>

                <a href="https://www.rld.nm.gov/boards-and-commissions/individual-boards-and-commissions/chiropractic-board/instructors-training-requirements-and-continuing-education/" target="_blank" class="sb-card" data-aos="fade-up">
                    <div class="sb-badge">NM</div>
                    <div class="sb-body">
                        <div class="sb-name">New Mexico Chiropractic Board</div>
                        <div class="sb-sub">Approved provider</div>
                    </div>
                    <div class="sb-arrow"><i class="fa fa-external-link"></i></div>
                </a>

                <a href="https://sos.ga.gov/georgia-board-chiropractic-examiners" target="_blank" class="sb-card" data-aos="fade-up">
                    <div class="sb-badge">GA</div>
                    <div class="sb-body">
                        <div class="sb-name">Georgia Board of Chiropractic Examiners</div>
                        <div class="sb-sub">Approved provider</div>
                    </div>
                    <div class="sb-arrow"><i class="fa fa-external-link"></i></div>
                </a>

                <a href="https://www.njconsumeraffairs.gov/chi" target="_blank" class="sb-card" data-aos="fade-up">
                    <div class="sb-badge">NJ</div>
                    <div class="sb-body">
                        <div class="sb-name">New Jersey State Board of Chiropractic Examiners</div>
                        <div class="sb-sub">Approved provider</div>
                    </div>
                    <div class="sb-arrow"><i class="fa fa-external-link"></i></div>
                </a>

                <a href="https://chiro.alabama.gov/" target="_blank" class="sb-card" data-aos="fade-up">
                    <div class="sb-badge">AL</div>
                    <div class="sb-body">
                        <div class="sb-name">Alabama State Board of Chiropractic Examiners</div>
                        <div class="sb-sub">Approved provider</div>
                    </div>
                    <div class="sb-arrow"><i class="fa fa-external-link"></i></div>
                </a>

                <a href="https://www.chiro.ca.gov/licensees/ce.shtml" target="_blank" class="sb-card" data-aos="fade-up">
                    <div class="sb-badge">CA</div>
                    <div class="sb-body">
                        <div class="sb-name">California State Board of Chiropractic Examiners</div>
                        <div class="sb-sub">Approved provider</div>
                    </div>
                    <div class="sb-arrow"><i class="fa fa-external-link"></i></div>
                </a>

                <a href="https://chirobd.nv.gov/" target="_blank" class="sb-card" data-aos="fade-up">
                    <div class="sb-badge">NV</div>
                    <div class="sb-body">
                        <div class="sb-name">Chiropractic Physicians&rsquo; Board of Nevada</div>
                        <div class="sb-sub">Approved provider</div>
                    </div>
                    <div class="sb-arrow"><i class="fa fa-external-link"></i></div>
                </a>

                <a href="https://oklahoma.gov/chiropractic/certified-chiropractic-assistant/approved-courses-for-certified-chiropractic-assistants.html" target="_blank" class="sb-card" data-aos="fade-up">
                    <div class="sb-badge">OK</div>
                    <div class="sb-body">
                        <div class="sb-name">Oklahoma Board of Chiropractic Examiners</div>
                        <div class="sb-sub">Approved provider</div>
                    </div>
                    <div class="sb-arrow"><i class="fa fa-external-link"></i></div>
                </a>

                <a href="https://cebroker.com/" target="_blank" class="sb-card" data-aos="fade-up">
                    <div class="sb-badge">CE</div>
                    <div class="sb-body">
                        <div class="sb-name">CE Broker</div>
                        <div class="sb-sub">Approved in GA, NC, SC &amp; TN</div>
                    </div>
                    <div class="sb-arrow"><i class="fa fa-external-link"></i></div>
                </a>

            </div>{{-- /.sb-grid --}}
        </div>
    </section>

    <style>
section.about.center.about-text1 {
    margin-top: -100px;
}
    /* ── PACE Banner ───────────────────────────────────── */
    .pace-banner {
        display: flex;
        align-items: center;
        gap: 28px;
        background: linear-gradient(135deg, #0e5a96 0%, #1379ba 100%);
        border-radius: 16px;
        padding: 28px 36px;
        margin: 0 auto 44px;
        max-width: 900px;
        box-shadow: 0 8px 28px rgba(19,121,186,.28);
    }
    .pace-logo img {
        height: 90px;
        width: auto;
        flex-shrink: 0;
        filter: drop-shadow(0 2px 8px rgba(0,0,0,.3));
    }
    .pace-content { flex: 1; text-align: left; }
    .pace-title {
        font-size: 20px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
        letter-spacing: .3px;
    }
    .pace-check { color: #fff; font-size: 22px; }
    .pace-desc {
        font-size: 13.5px;
        color: rgba(255,255,255,.78);
        margin: 0 0 14px;
        line-height: 1.6;
    }
    .pace-desc strong { color: #fff; }
    .pace-states {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .pace-state-pill {
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.25);
        color: #fff;
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        letter-spacing: .4px;
        transition: background .2s;
        text-decoration: none;
        display: inline-block;
    }
    .pace-state-pill:hover {
        background: rgba(255,255,255,.22);
        color: #fff;
        text-decoration: none;
    }
    .pace-state-pill--inactive {
        cursor: default;
        opacity: 0.65;
    }
    @media (max-width: 767px) {
        .pace-banner { flex-direction: column; text-align: center; padding: 24px 20px; }
        .pace-title { justify-content: center; }
        .pace-states { justify-content: center; }
    }

    /* ── State Board Cards ─────────────────────────────── */
    .sb-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-top: 8px;
    }
    .sb-card {
        display: flex;
        align-items: center;
        gap: 14px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 18px;
        text-decoration: none !important;
        color: #1a2e44 !important;
        transition: all .22s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,.05);
    }
    .sb-card:hover {
        border-color: #1379ba;
        box-shadow: 0 6px 20px rgba(19,121,186,.15);
        transform: translateY(-3px);
        color: #1a2e44 !important;
        text-decoration: none !important;
    }
    .sb-badge {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: #1379ba;
        color: #fff;
        font-weight: 700;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        letter-spacing: .5px;
    }
    .sb-body { flex: 1; text-align: left; }
    .sb-name {
        font-size: 13.5px;
        font-weight: 600;
        color: #1a2e44;
        line-height: 1.4;
        margin-bottom: 2px;
    }
    .sb-sub {
        font-size: 11.5px;
        color: #64748b;
    }
    .sb-arrow {
        flex-shrink: 0;
        color: #94a3b8;
        font-size: 13px;
        transition: color .2s;
    }
    .sb-card:hover .sb-arrow { color: #1379ba; }
    @media (max-width: 991px) {
        .sb-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 575px) {
        .sb-grid { grid-template-columns: 1fr; }
    }
    </style>
    <section class="faqs center" id="faq" style="display: none">
        <div class="container"  data-aos="fade-up">
            <h2 class="uppercase">FAQ's</h2>
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                                How do I enroll?</a>
                        </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse in">
                        <div class="panel-body">Enrollment with our school requires your legal name, profession, and email address. It is FREE.</div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                                How do I register for a course?</a>
                        </h4>
                    </div>
                    <div id="collapse2" class="panel-collapse collapse">
                        <div class="panel-body">Choose your profession, choose your state and your course options will appear.  Click on desired course adding it to your shopping cart, register, create a password and pay.</div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                                What methods of payment do you accept?</a>
                        </h4>
                    </div>
                    <div id="collapse3" class="panel-collapse collapse">
                        <div class="panel-body">Visa, Mastercard, and American Express </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">
                                How does your website ensure my personal information and credit card payment is secure?</a>
                        </h4>
                    </div>
                    <div id="collapse4" class="panel-collapse collapse">
                        <div class="panel-body">We take every precaution available to ensure that your personal information is protected.  We do not store credit card information on our website.  We also use “Secured Sockets Layer” (SSL) technology (signified by the small lock icon in your browser).  All payments are processed through Authorize.net ecommerce platform visa trusted site.   </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">
                                When can I start the course?</a>
                        </h4>
                    </div>
                    <div id="collapse5" class="panel-collapse collapse">
                        <div class="panel-body">The course can be started immediately.    </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse6">
                                How long do I have to finish the course?</a>
                        </h4>
                    </div>
                    <div id="collapse6" class="panel-collapse collapse">
                        <div class="panel-body">Students must complete a course within 6 months of initial registration.     </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse7">
                                How do I update my email?</a>
                        </h4>
                    </div>
                    <div id="collapse7" class="panel-collapse collapse">
                        <div class="panel-body">Login to your student dashboard.  Click on profile and select update email, enter new email address.      </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse8">
                                My name is spelled incorrectly, how do I update it?</a>
                        </h4>
                    </div>
                    <div id="collapse8" class="panel-collapse collapse">
                        <div class="panel-body">Log in to your student dashboard, open your profile, and update your first, middle, and last name in the name fields. If your account name must match an official document (for example your license or certificate) and you need help correcting it, use the Support chat or call us toll free at <a href="tel:+18889003382">1-888-900-3382</a> and our team will assist you.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- NEW: Chiropractic Assistant Training FAQ (accordion)          --}}
    {{-- Fully scoped under .cat-faq — unique IDs (catFaq*) so it      --}}
    {{-- cannot collide with the #accordion / #collapse1-8 block       --}}
    {{-- above, or with any other section's markup/CSS on this page.   --}}
    {{-- ============================================================ --}}
    <section class="cat-faq" id="cat-faq" data-aos="fade-up">
        <div class="cat-faq__header">
            <div class="container">
                <h2 class="cat-faq__title">Chiropractic Assistant Training Frequently Asked Questions</h2>
                <p class="cat-faq__subtitle">Answers to the questions we hear most from future Chiropractic Assistants.</p>
            </div>
        </div>

        <div class="container">
            <div class="cat-faq__accordion" id="catFaqAccordion">

                <div class="cat-faq__item">
                    <button class="cat-faq__question" type="button" data-toggle="collapse" data-target="#catFaq1" aria-expanded="false" aria-controls="catFaq1">
                        <span>How do I become a Certified Chiropractic Assistant?</span>
                        <i class="fa fa-chevron-down cat-faq__icon" aria-hidden="true"></i>
                    </button>
                    <div id="catFaq1" class="collapse" data-parent="#catFaqAccordion">
                        <div class="cat-faq__answer">
                            <p>To become a Certified Chiropractic Assistant, you must complete a qualified Chiropractic Assistant training program that provides the knowledge and practical skills needed to support a chiropractic practice. In many states, this training must be approved by the state&rsquo;s Chiropractic Board to meet certification or registration requirements.</p>
                            <p>Professional Online Education offers comprehensive, state-specific, and national Chiropractic Assistant training that covers chiropractic principles, patient care, communication, documentation, office procedures, compliance, professionalism, and leadership&mdash;preparing students to become confident, competent members of the chiropractic healthcare team.</p>
                            <p>After completing your coursework and required assessments, students may need to complete registration requirements through their state chiropractic board. Students completing the National Chiropractic Assistant Program may also pursue the Certified Clinical Chiropractic Assistant (CCCA) credential through the Federation of Chiropractic Licensing Boards (FCLB) by completing the required registration process and national examination.</p>
                        </div>
                    </div>
                </div>

                <div class="cat-faq__item">
                    <button class="cat-faq__question" type="button" data-toggle="collapse" data-target="#catFaq2" aria-expanded="false" aria-controls="catFaq2">
                        <span>Is Chiropractic Assistant training required in my state?</span>
                        <i class="fa fa-chevron-down cat-faq__icon" aria-hidden="true"></i>
                    </button>
                    <div id="catFaq2" class="collapse" data-parent="#catFaqAccordion">
                        <div class="cat-faq__answer">
                            <p>Chiropractic Assistant requirements vary by state. Some states require registration, certification, approved education, or specific training requirements before a Chiropractic Assistant can perform certain duties in a chiropractic office.</p>
                            <p>Professional Online Education provides state-specific Chiropractic Assistant training designed to help students understand and meet the requirements applicable in their state. Students should verify current requirements with their individual state chiropractic board.</p>
                            <p>To determine the requirements for your state, visit our <a href="{{ url('/states') }}">State Requirements page</a> and select your state. You&rsquo;ll find information about your state&rsquo;s Chiropractic Assistant training, registration, certification, and board approval requirements, along with the courses available for your location.</p>
                        </div>
                    </div>
                </div>

                <div class="cat-faq__item">
                    <button class="cat-faq__question" type="button" data-toggle="collapse" data-target="#catFaq3" aria-expanded="false" aria-controls="catFaq3">
                        <span>What is a Certified Clinical Chiropractic Assistant (CCCA)?</span>
                        <i class="fa fa-chevron-down cat-faq__icon" aria-hidden="true"></i>
                    </button>
                    <div id="catFaq3" class="collapse" data-parent="#catFaqAccordion">
                        <div class="cat-faq__answer">
                            <p>A Certified Clinical Chiropractic Assistant (CCCA) is a nationally recognized credential for Chiropractic Assistants who have completed approved education requirements and successfully passed the national examination administered through the Federation of Chiropractic Licensing Boards (FCLB).</p>
                            <p>The CCCA credential demonstrates a commitment to professional education, clinical knowledge, and excellence in supporting chiropractic physicians and patients.</p>
                        </div>
                    </div>
                </div>

                <div class="cat-faq__item">
                    <button class="cat-faq__question" type="button" data-toggle="collapse" data-target="#catFaq4" aria-expanded="false" aria-controls="catFaq4">
                        <span>How long does Chiropractic Assistant certification take?</span>
                        <i class="fa fa-chevron-down cat-faq__icon" aria-hidden="true"></i>
                    </button>
                    <div id="catFaq4" class="collapse" data-parent="#catFaqAccordion">
                        <div class="cat-faq__answer">
                            <p>The time required to complete Chiropractic Assistant certification varies by state and program requirements. Most programs require 20 to 40 hours of online coursework, depending on your state&rsquo;s regulations. Professional Online Education offers flexible, self-paced training that allows students to complete their coursework while balancing work, family, and personal responsibilities.</p>
                            <p>Students can progress through the program at their own pace, gaining the knowledge, practical skills, and confidence needed to become successful Chiropractic Assistants and valuable members of the chiropractic healthcare team.</p>
                        </div>
                    </div>
                </div>

                <div class="cat-faq__item">
                    <button class="cat-faq__question" type="button" data-toggle="collapse" data-target="#catFaq5" aria-expanded="false" aria-controls="catFaq5">
                        <span>How much does it cost to become a Chiropractic Assistant?</span>
                        <i class="fa fa-chevron-down cat-faq__icon" aria-hidden="true"></i>
                    </button>
                    <div id="catFaq5" class="collapse" data-parent="#catFaqAccordion">
                        <div class="cat-faq__answer">
                            <p>The cost of becoming a Chiropractic Assistant depends on your state&rsquo;s requirements and the training program you choose. Professional Online Education is committed to providing affordable, high-quality training that prepares Chiropractic Assistants for long-term career success. Chiropractic Assistant training programs range from <strong>$149 to $399</strong>, depending on your state&rsquo;s education requirements and the program selected.</p>
                            <p>Students enrolling in the National Chiropractic Assistant Program should also plan for additional third-party expenses, which may include:</p>
                            <ul class="cat-faq__list">
                                <li><strong>FCLB national examination fee:</strong> Approximately $150&ndash;$200</li>
                                <li><strong>Fingerprinting and background check</strong> (if required): Approximately $20&ndash;$50</li>
                                <li><strong>State registration or certification fees:</strong> These vary by state. Students should check with their state chiropractic board to determine any applicable registration, certification, or licensing fees.</li>
                            </ul>
                            <p>To view your state&rsquo;s training requirements and course tuition, visit our <a href="{{ url('/states') }}">State Requirements page</a> and select your state. Professional Online Education also recommends verifying all registration and certification requirements directly with your state chiropractic board, as requirements and fees may change.</p>
                            <p class="cat-faq__note"><em>Please note: Fees charged by the Federation of Chiropractic Licensing Boards (FCLB), state chiropractic boards, and fingerprinting agencies are established by those organizations and are subject to change.</em></p>
                        </div>
                    </div>
                </div>

                <div class="cat-faq__item">
                    <button class="cat-faq__question" type="button" data-toggle="collapse" data-target="#catFaq6" aria-expanded="false" aria-controls="catFaq6">
                        <span>What is the difference between a Chiropractic Assistant and a Certified Clinical Chiropractic Assistant (CCCA)?</span>
                        <i class="fa fa-chevron-down cat-faq__icon" aria-hidden="true"></i>
                    </button>
                    <div id="catFaq6" class="collapse" data-parent="#catFaqAccordion">
                        <div class="cat-faq__answer">
                            <p>A Chiropractic Assistant (CA) is a trained professional who supports the chiropractor by assisting with patient care, office procedures, documentation, communication, and other clinical and administrative responsibilities. State requirements for becoming a Chiropractic Assistant vary and may include board-approved education, registration, or certification.</p>
                            <p>A Certified Clinical Chiropractic Assistant (CCCA) is a nationally recognized credential awarded through the Federation of Chiropractic Licensing Boards (FCLB). To earn the CCCA designation, students must complete an approved National Chiropractic Assistant Program, register with the FCLB, and successfully pass the online national examination.</p>
                        </div>
                    </div>
                </div>

                <div class="cat-faq__item">
                    <button class="cat-faq__question" type="button" data-toggle="collapse" data-target="#catFaq7" aria-expanded="false" aria-controls="catFaq7">
                        <span>What is the best way to become a successful Chiropractic Assistant?</span>
                        <i class="fa fa-chevron-down cat-faq__icon" aria-hidden="true"></i>
                    </button>
                    <div id="catFaq7" class="collapse" data-parent="#catFaqAccordion">
                        <div class="cat-faq__answer">
                            <p>The best way to build a successful career as a Chiropractic Assistant is to begin with quality education, continue learning throughout your career, and develop both technical and leadership skills.</p>
                            <p>Professional Online Education prepares students with the knowledge, confidence, and practical skills needed to support doctors, deliver an exceptional patient experience, and become valuable members of high-performing chiropractic teams. We also offer continuing education and leadership training to help Chiropractic Assistants continue growing professionally throughout their careers.</p>
                        </div>
                    </div>
                </div>

                <div class="cat-faq__item">
                    <button class="cat-faq__question" type="button" data-toggle="collapse" data-target="#catFaq8" aria-expanded="false" aria-controls="catFaq8">
                        <span>Is the Chiropractic Assistant program online?</span>
                        <i class="fa fa-chevron-down cat-faq__icon" aria-hidden="true"></i>
                    </button>
                    <div id="catFaq8" class="collapse" data-parent="#catFaqAccordion">
                        <div class="cat-faq__answer">
                            <p>Yes. Professional Online Education offers online Chiropractic Assistant training, allowing students to complete their coursework from anywhere with internet access. The flexible format allows students to learn at their own pace while receiving education designed specifically for Chiropractic Assistants and chiropractic teams.</p>
                        </div>
                    </div>
                </div>

                <div class="cat-faq__item">
                    <button class="cat-faq__question" type="button" data-toggle="collapse" data-target="#catFaq9" aria-expanded="false" aria-controls="catFaq9">
                        <span>Is the Initial CA training offered in person?</span>
                        <i class="fa fa-chevron-down cat-faq__icon" aria-hidden="true"></i>
                    </button>
                    <div id="catFaq9" class="collapse" data-parent="#catFaqAccordion">
                        <div class="cat-faq__answer">
                            <p>Yes. At this time, Professional Online Education offers a Live Chiropractic Assistant Initial Training program in Arizona only.</p>
                            <p>The live program consists of four full-day seminars, held on Saturday and Sunday over two weekends, with the weekends typically scheduled for one month apart. This format allows students time to review course material and prepare between seminar weekends.</p>
                            <p>Students interested in the Arizona Live Initial Chiropractic Assistant Training program can view upcoming seminar dates and register by visiting the <a href="{{ route('eventsPage') }}">Events page</a>.</p>
                            <p class="cat-faq__note"><em>Space is limited, and live seminars often sell out quickly. Early registration is highly recommended to reserve your seat.</em></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <style>
    
@media only screen and (max-width : 767px) {
    .why-choose-checklist li {
    font-size: 12px !important;
}
    section.choose h2 {
    text-align: left;
}
.why-choose-intro {
    text-align: left;
}
.why-choose-checklist {
    text-align: left !important;
    max-width: 100% !important;
    display: flex;
    justify-content: unset;
}
}
    /* ── Chiropractic Assistant Training FAQ (accordion) ─────────────────
       Everything below is namespaced under .cat-faq so it can never
       override or be overridden by .faqs / #accordion / .choose / .approved
       or any other section's rules on this page.                        */
    .cat-faq {
        background: #f8fafc;
        padding-bottom: 60px;
        font-family: 'Mulish', sans-serif;
    }
    .cat-faq__header {
        background: linear-gradient(135deg, #1a73e8 0%, #0d5bbf 100%);
        padding: 50px 0 42px;
        margin-bottom: 40px;
    }
    .cat-faq__title {
        color: #ffffff;
        font-family: 'Mulish', sans-serif;
        font-weight: 800;
        font-size: 30px;
        line-height: 1.35;
        text-align: center;
        margin: 0 auto 12px;
        max-width: 820px;
    }
    .cat-faq__subtitle {
        color: rgba(255,255,255,.85);
        font-family: 'Mulish', sans-serif;
        font-size: 15px;
        text-align: center;
        margin: 0;
    }
    .cat-faq__accordion {
        max-width: 860px;
        margin: 0 auto;
    }
    .cat-faq__item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        margin-bottom: 14px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(13,91,191,.05);
        transition: box-shadow .2s ease;
    }
    .cat-faq__item:hover {
        box-shadow: 0 4px 16px rgba(13,91,191,.12);
    }
    .cat-faq__question {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        background: #ffffff;
        border: none;
        text-align: left;
        cursor: pointer;
        padding: 18px 22px;
        font-family: 'Mulish', sans-serif;
        font-weight: 700;
        font-size: 15.5px;
        color: #0d2340;
        transition: color .2s ease, background .2s ease;
    }
    .cat-faq__question:hover,
    .cat-faq__question:focus {
        color: #1a73e8;
        outline: none;
    }
    .cat-faq__question[aria-expanded="true"] {
        color: #ffffff;
        background: linear-gradient(135deg, #1a73e8 0%, #0d5bbf 100%);
    }
    .cat-faq__icon {
        flex-shrink: 0;
        font-size: 13px;
        color: #1a73e8;
        transition: transform .25s ease, color .2s ease;
    }
    .cat-faq__question[aria-expanded="true"] .cat-faq__icon {
        color: #ffffff;
        transform: rotate(180deg);
    }
    .cat-faq__answer {
        padding: 20px 22px 22px;
        border-top: 1px solid #eef2f7;
    }
  .cat-faq__answer p {
    font-family: 'Mulish', sans-serif;
    color: #000000;
    font-size: 16px;
    line-height: 1.8;
    margin: 0 0 14px;
    font-weight: 500;
}
    .cat-faq__answer p:last-child {
        margin-bottom: 0;
    }
    .cat-faq__answer a {
        color: #1a73e8;
        font-weight: 700;
        text-decoration: underline;
    }
    .cat-faq__answer strong {
        color: #0d2340;
    }
    .cat-faq__list {
        margin: 0 0 14px;
        padding-left: 20px;
    }
    .cat-faq__list li {
        font-family: 'Mulish', sans-serif;
        color: #4a5568;
        font-size: 14.5px;
        line-height: 1.8;
        margin-bottom: 6px;
    }
    .cat-faq__note {
        font-size: 13px !important;
        color: #94a3b8 !important;
    }
    @media (max-width: 575px) {
        .cat-faq__title { font-size: 23px; }
        .cat-faq__question { font-size: 14.5px; padding: 15px 16px; }
        .cat-faq__answer { padding: 16px 16px 18px; }
    }
    </style>

    <script>
    // Scoped strictly to #cat-faq — only rotates the chevron icon; the
    // actual expand/collapse is handled by Bootstrap's existing
    // data-toggle="collapse" behavior already used elsewhere on this page.
    document.addEventListener('DOMContentLoaded', function () {
        var catFaqRoot = document.getElementById('cat-faq');
        if (!catFaqRoot) { return; }

        var buttons = catFaqRoot.querySelectorAll('.cat-faq__question');
        buttons.forEach(function (btn) {
            var targetSelector = btn.getAttribute('data-target');
            var target = targetSelector ? document.querySelector(targetSelector) : null;
            if (!target) { return; }

            target.addEventListener('shown.bs.collapse', function () {
                btn.setAttribute('aria-expanded', 'true');
            });
            target.addEventListener('hidden.bs.collapse', function () {
                btn.setAttribute('aria-expanded', 'false');
            });
        });
    });
    </script>

    <section class="reviews">
        <div class="container">
            <h2 class="center">Recent Reviews</h2>
            <div class="row">
                <div class="col-md-3 aos-init"  data-aos="fade-right">
                    <div class="revbox">
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Dr. Mike, Scottsdale, AZ</strong>
                                <ul class="rating">
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                </ul>
                                <p>Loved this course!  The site was easy to use, I was able to login from my computer or cell phone on my  lunch break.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3" data-aos="fade-left">
                    <div class="revbox">
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Renata,<br> Las Vegas, NV</strong>
                                <ul class="rating">
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                </ul>
                                <p>The exercises were fun to do and made me feel more confident.  I am excited to start using the things I learned.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 aos-init"  data-aos="fade-right">
                    <div class="revbox">
                        <div class="row">

                            <div class="col-md-12">
                                <strong>Evelyn,<br> Phoenix, AZ</strong>
                                <ul class="rating">
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                </ul>
                                <p>Dr Canham is an excellent speaker with great stories.  I was so glad I opted for the live 3-Day seminar.  Time just flew by and I learned so much, and was able to register with the board on Monday!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 aos-init"  data-aos="fade-right">
                    <div class="revbox">
                        <div class="row">

                            <div class="col-md-12">
                                <strong>Rachel,<br> Rapid City, SD</strong>
                                <ul class="rating">
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                </ul>
                                <p>Course was excellent, website was easy to use.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection