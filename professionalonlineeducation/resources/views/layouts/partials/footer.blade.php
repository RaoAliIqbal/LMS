<footer>
    <div class="container">
        <!-- <h2   >Get in touch with us</h2>
         <form action=""   >
             <ul>
                 <li><label><i class="fa fa-envelope"></i> Email address</label></li>
                 <li><input type="email" placeholder="Your Email Address"></li>
                 <li><button class="btn">Subscribe Now</button></li>
             </ul>
         </form>-->
        <div class="row">
            <div class="col-md-3">
                <a href="/">
                    <img src="{{ url('webAssets/images/logo-footer.png') }}" alt="" />
                </a>
                <ul class="social">
                    <li><label for="">Get Social</label></li>
                    <li><a href="https://www.facebook.com/CanhamCollege/" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="https://www.instagram.com/rosalind_canham/" target="_blank"><i class="fa fa-instagram"></i></a></li>
                    <li><a href="https://www.linkedin.com/company/canham-college/" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                </ul>
            </div>
            <div class="col-md-5">
                <h4>Quick Links</h4>
                <ul class="footmenu">
                    <li><a href="{{ route('coursesPage',['category_id'=>'2']) }}">Chiropractic Assistant Initial Training</a></li>
                    <li><a href="{{ route('coursesPage',['category_id'=>'3']) }}">Chiropractor CE</a></li>
                    <li><a href="{{ route('coursesPage',['category_id'=>'4']) }}">Massage Therapy CE</a></li>
                    <li><a href="{{ route('coursesPage',['category_id'=>'6']) }}">Behavioral Health</a></li>
                    <li><a href="{{ route('coursesPage',['category_id'=>'7']) }}">Certified Nursing Assistant</a></li>
                    <li><a href="{{ route('coursesPage') }}">Peer Support Certification</a></li>
                    <li><a href="{{ route('coursesPage') }}">Catalog</a></li>
                    <li><a href="{{ route('eventsPage') }}">Live Events</a></li>
                    <li><a href="javascript:;" class="chat">Support</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h4>Policies &amp; Legal</h4>
                <ul class="footmenu">
                    <li><a href="{{ route('refundPolicy') }}">Refund or Tuition Waiver Policy</a></li>
                    <li><a href="{{ route('privacyPolicy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('termsConditions') }}">Terms &amp; Conditions</a></li>
                    <li><a href="{{ route('cookiesPolicy') }}">Cookies Policy</a></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- ── Bottom bar ─────────────────────────────────────────── --}}
    <div class="footer-bottom">

        {{-- License disclaimer --}}
        <div class="container">
            <p class="footer-disclaimer">
                <strong>License Renewal Notice:</strong>
                While applications relating to continuing education credit hours for license renewal in selected states have been executed for these programs,
                it remains attendees' responsibility to contact the state board(s) from which they seek continuing education credits to ensure said board(s)
                approve both venue and content as they relate to any seminar, course, webinar, or online presentation.
            </p>
               <p class="footer-disclaimer footer_new_sec">
                <strong style="font-size:15px">Professional Online Education | Founded in 2002 by Dr. Rosalind Canham, DC | Chiropractic Assistant Certification, Continuing Education & Leadership Training</strong>
       
            </p>
        </div>

        {{-- Contact strip --}}
        <div class="footer-contact-strip">
            <div class="container">
                <div class="footer-contact-inner">
                    <span class="footer-contact-item">
                        <i class="fa fa-building"></i>
                        <strong>Canham College, Inc.</strong>
                        <span class="footer-sep">&mdash;</span>
                        dba <a href="https://www.ProfessionalOnlineEducation.com">ProfessionalOnlineEducation.com</a>
                    </span>
                    <span class="footer-pipe">|</span>
                    <span class="footer-contact-item">
                        <i class="fa fa-map-marker"></i>
                        2934 E Avalon Drive, Phoenix, AZ 85016
                    </span>
                    <span class="footer-pipe">|</span>
                    <span class="footer-contact-item">
                        <i class="fa fa-phone"></i>
                        Toll Free: <a href="tel:18889003382">1-888-900-3382</a>
                    </span>
                    <span class="footer-pipe">|</span>
                    <span class="footer-contact-item">
                        <i class="fa fa-envelope"></i>
                        <a href="mailto:mgr@professionalonlineeducation.com">mgr@professionalonlineeducation.com</a>
                    </span>
                    <span class="footer-pipe">|</span>
                    <span class="footer-contact-item">
                        <i class="fa fa-clock-o"></i>
                        Mon &ndash; Fri, 8:00 AM &ndash; 5:00 PM
                    </span>
                </div>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="footer-copy">
            <p>&copy; {{ now()->year }} Canham College, Inc. All rights reserved.</p>
        </div>

    </div>
</footer>

<style>
p.footer-disclaimer.footer_new_sec {
    position: relative;
}
p.footer-disclaimer.footer_new_sec:before {
    content: "";
    background: #2689c4;
    position: absolute;
    width: 100%;
    height: 1px;
    top: -5px;
}
/* ── Footer bottom bar ────────────────────────────────── */
.footer-bottom {
    border-top: 1px solid rgba(255,255,255,.15);
    padding: 20px 0 0;
    color: #fff;
}
.footer-disclaimer {
    font-size: 11.5px;
    color: #fff;
    text-align: center;
    line-height: 1.75;
    margin: 0 0 14px;
}
.footer-disclaimer strong {
    color: #fff;
}
.footer-contact-strip {
    border-top: 1px solid rgba(255,255,255,.15);
    border-bottom: 1px solid rgba(255,255,255,.15);
    padding: 11px 0;
}
.footer-contact-inner {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 6px 24px;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
}
.footer-contact-item {
    font-size: 12.5px;
    color: #fff;
    white-space: nowrap;
    text-align: center;
}
.footer-contact-item i {
    margin-right: 5px;
    color: #fff;
}
.footer-contact-item strong {
    color: #fff;
}
.footer-contact-item a {
    color: #fff;
    text-decoration: none;
    font-weight: 600;
}
.footer-contact-item a:hover {
    text-decoration: underline;
    color: #fff;
}
.footer-sep {
    color: rgba(255,255,255,.5);
    margin: 0 4px;
}
.footer-pipe {
    color: rgba(255,255,255,.4);
    font-size: 12px;
}
.footer-copy {
    padding: 11px 0 13px;
    text-align: center;
}
.footer-copy p {
    margin: 0;
    font-size: 12px;
    color: #fff;
}
@media (max-width: 991px) {
    .footer-contact-strip {
        display: block;
        padding: 14px 12px;
    }
    .footer-contact-inner {
        width: 100%;
        max-width: 100%;
        gap: 10px 0;
        flex-direction: column;
        align-items: center;
    }
    .footer-pipe { display: none; }
    .footer-contact-item {
        white-space: normal;
        line-height: 1.5;
        width: 100%;
        max-width: 100%;
        word-break: break-word;
    }
    .footer-disclaimer {
        font-size: 11px;
        padding: 0 8px;
    }
    .footer-bottom {
        padding-bottom: 72px;
    }
}
@media (max-width: 600px) {
    .footer-pipe { display: none; }
    .footer-contact-inner { gap: 8px 0; flex-direction: column; }
}
</style>
<script>
    AOS.init();
</script>