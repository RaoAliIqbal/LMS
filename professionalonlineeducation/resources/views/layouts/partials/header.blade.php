{{-- Google Translate hidden init element --}}
<div id="google_translate_element" style="display:none;"></div>

<style>
ul.navbar-nav.navbar li a {
    font-size: 15px;
    font-weight: bold;
    color: #000000;
    padding: 12px 11px !important;
    /* text-transform: capitalize !important; */
    border-radius: 7px;
    text-transform: unset !important;
}
    /* ── Hide Google toolbar ── */
    body { top: 0 !important; }
    .goog-te-banner-frame, .skiptranslate { display: none !important; }
    iframe.goog-te-menu-frame { box-shadow: none !important; }

    /* ── Language Selector Wrapper ── */
    .lang-selector {
        position: relative;
        display: inline-flex;
        align-items: center;
        margin-left: 10px;
        vertical-align: middle;
    }

    /* ── Trigger Button ── */
    .lang-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        background: linear-gradient(135deg, #1a6fc4 0%, #2196f3 100%);
        border: none;
        border-radius: 22px;
        padding: 7px 14px 7px 10px;
        font-size: 13px;
        font-weight: 600;
        color: #fff;
        white-space: nowrap;
        box-shadow: 0 2px 10px rgba(33,150,243,0.35);
        transition: all 0.2s ease;
        user-select: none;
        letter-spacing: 0.3px;
    }
    .lang-btn:hover {
        background: linear-gradient(135deg, #1565c0 0%, #1976d2 100%);
        box-shadow: 0 4px 16px rgba(33,150,243,0.45);
        transform: translateY(-1px);
    }
    .lang-btn img {
        border-radius: 3px;
        flex-shrink: 0;
        display: inline-block;
        vertical-align: middle;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .lang-btn .lb-name { max-width: 80px; overflow: hidden; text-overflow: ellipsis; }
    .lang-btn .lb-arrow {
        font-size: 9px;
        opacity: 0.85;
        transition: transform 0.25s cubic-bezier(.4,0,.2,1);
        margin-left: 1px;
    }
    .lang-selector.open .lb-arrow { transform: rotate(180deg); }

    /* ── Dropdown Panel ── */
    .lang-menu {
        display: none;
        position: absolute;
        right: 0;
        top: calc(100% + 10px);
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 12px 40px rgba(0,0,0,0.16), 0 2px 8px rgba(0,0,0,0.08);
        min-width: 210px;
        z-index: 999999;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.06);
        animation: langDrop 0.2s cubic-bezier(.4,0,.2,1);
    }
    @keyframes langDrop {
        from { opacity: 0; transform: translateY(-8px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .lang-selector.open .lang-menu { display: block; }

    /* Dropdown header */
    .lang-menu-header {
        background: linear-gradient(135deg, #1a6fc4, #42a5f5);
        padding: 10px 16px;
        font-size: 11px;
        font-weight: 700;
        color: rgba(255,255,255,0.9);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Each language item */
    .lang-item {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 10px 16px;
        cursor: pointer;
        transition: background 0.15s;
        border-bottom: 1px solid #f5f5f5;
        position: relative;
    }
    .lang-item:last-child { border-bottom: none; }
    .lang-item:hover { background: #f0f7ff; }
    .lang-item.active { background: #e3f2fd; }

    .lang-item .li-flag {
        width: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .lang-item .li-flag img {
        border-radius: 5px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.15);
        display: block;
    }
    .lang-item .li-info { flex: 1; }
    .lang-item .li-name {
        font-size: 13px;
        font-weight: 600;
        color: #222;
        display: block;
        line-height: 1.3;
    }
    .lang-item .li-code {
        font-size: 10px;
        color: #999;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .lang-item .li-check {
        color: #1976d2;
        font-size: 14px;
        font-weight: 700;
        display: none;
    }
    .lang-item.active .li-check { display: block; }
    .lang-item.active .li-name  { color: #1565c0; }

    /* ── Mobile hamburger ── */
    .mob-toggle {
        display: none;
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: #6ba546;
        border: none;
        border-radius: 8px;
        width: 42px;
        height: 42px;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 1001;
        flex-direction: column;
        gap: 5px;
        padding: 10px;
    }
    .mob-toggle span {
        display: block;
        width: 100%;
        height: 2px;
        background: #fff;
        border-radius: 2px;
        transition: all 0.3s ease;
    }
    .mob-toggle.active span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
    .mob-toggle.active span:nth-child(2) { opacity: 0; }
    .mob-toggle.active span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

    /* ── Mobile nav panel ── */
    @media (max-width: 991px) {
        .head { position: relative; }
        .head .container { width: 100% !important; padding: 0 15px; }
        .head .row { display: flex; align-items: center; flex-wrap: nowrap; position: relative; }

        .mob-toggle { display: flex; }

        ul.topinfo { display: none !important; }

        /* Hide desktop navbar, replace with mobile drawer */
        ul.navbar-nav.navbar {
            display: none !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            margin-top: 70px !important;
            background: #fff !important;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18) !important;
            z-index: 9998 !important;
            flex-direction: column !important;
            align-items: stretch !important;
            padding: 6px 0 20px !important;
            border-top: 3px solid #6ba546 !important;
            flex-wrap: wrap !important;
            overflow-y: auto !important;
            height: fit-content !important;
            bottom: auto !important;
        }
        ul.navbar-nav.navbar.mob-open {
            display: flex !important;
            margin: 0 auto;
            width: 100% !important;
        }
        ul.navbar-nav.navbar li {
            width: 100% !important;
            display: block !important;
        }
        ul.navbar-nav.navbar li {
            width: 100% !important;
            text-align: center !important;
        }
        ul.navbar-nav.navbar li a {
            display: block !important;
            padding: 13px 24px !important;
            font-size: 15px !important;
            font-weight: 600 !important;
            color: #333 !important;
            border-bottom: 1px solid #f0f0f0 !important;
            border-radius: 0 !important;
            text-align: center !important;
        }
        ul.navbar-nav.navbar li a:hover,
        ul.navbar-nav.navbar li a.active {
            background: #f1f8e9 !important;
            color: #6ba546 !important;
        }
        ul.navbar-nav.navbar li a.btn {
            margin: 8px 20px 0 !important;
            border-radius: 8px !important;
            text-align: center !important;
            display: block !important;
            padding: 11px 20px !important;
            border-bottom: none !important;
            width: calc(100% - 40px) !important;
        }
        /* Language selector in mobile menu — full width */
        ul.navbar-nav.navbar li .lang-selector {
            margin: 10px 20px 0;
            width: calc(100% - 40px);
            display: flex;
            justify-content: center;
        }
        ul.navbar-nav.navbar li .lang-btn {
            width: 100%;
            border-radius: 8px;
            justify-content: center;
        }
        ul.navbar-nav.navbar li .lang-menu {
            position: fixed;
            left: 15px;
            right: 15px;
            top: auto;
            min-width: unset;
        }
    }
</style>

<div class="head">
    <div class="container">
        <div class="row" style="align-items:center;">
            <div class="col-md-3 col-xs-8">
                <a href="/" class="logo">
                    <img src="{{ url('webAssets/images/logo.png') }}" alt="">
                </a>
            </div>
            <div class="col-md-9 col-xs-4" style="position:relative;">

                {{-- Mobile hamburger button --}}
                <button class="mob-toggle" id="mobToggle" onclick="toggleMobNav()" aria-label="Toggle menu">
                    <span></span><span></span><span></span>
                </button>

                <ul class="topinfo pull-right">
                    <li><i class="fa fa-map-marker" style="margin-right:4px;"></i> 2934 E Avalon Drive, Phoenix, AZ 85016</li>
                    <li><a href="tel:18889003382"><i class="fa fa-phone" style="margin-right:4px;"></i> Toll Free 1-888-900-3382</a></li>
                </ul>

                <ul class="navbar-nav navbar pull-right" id="mainNavbar" style="align-items:center; display:flex; flex-wrap:nowrap;">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('coursesPage') }}" class="{{ request()->routeIs('coursesPage') ? 'active' : '' }}">Courses</a></li>
                    <li><a href="{{ route('states') }}" class="{{ request()->routeIs('states') ? 'active' : '' }}">States</a></li>
                    <li><a href="{{ route('eventsPages') }}" class="{{ request()->routeIs('eventsPage') ? 'active' : '' }}">Live Events</a></li>
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="javascript:;" class="chat">Support</a></li>
                    @guest
                        <li><a href="{{ route('register') }}" class="btn">Enroll</a></li>
                        <li><a href="{{ route('login') }}" class="btn green">Login</a></li>
                    @endguest
                    @auth
                        <li><a href="{{ route('logout') }}" class="btn red">Logout</a></li>
                        @role('User')
                            <li><a href="{{ route('client_profile') }}" class="btn green">Dashboard</a></li>
                        @endrole
                        @role('Admin')
                            <li><a href="{{ route('admin_dashboard') }}" class="btn green">Dashboard</a></li>
                        @endrole
                    @endauth

                    {{-- ── Language Selector ── --}}
                    <li style="list-style:none; display:inline-flex; align-items:center;">
                        <div class="lang-selector" id="langSelector">

                            {{-- Trigger button --}}
                            <div class="lang-btn" id="langBtn" onclick="toggleLangMenu(event)">
                                <img id="lbFlag" src="https://flagcdn.com/20x15/us.png" width="20" height="15" style="border-radius:3px; object-fit:cover;" alt="flag">
                                <span class="lb-name" id="lbName">English</span>
                                <span class="lb-arrow">&#9660;</span>
                            </div>

                            {{-- Dropdown --}}
                            <div class="lang-menu" id="langMenu">
                                <div class="lang-menu-header">
                                    <i class="fa fa-globe" style="margin-right:6px;"></i> Select Language
                                </div>

                                <div class="lang-item active" data-lang="en" data-name="English" data-flag="us" onclick="switchLang(this)">
                                    <span class="li-flag">
                                        <img src="https://flagcdn.com/32x24/us.png" width="32" height="24" style="border-radius:4px; object-fit:cover;" alt="US">
                                    </span>
                                    <span class="li-info">
                                        <span class="li-name">English</span>
                                        <span class="li-code">EN · English</span>
                                    </span>
                                    <span class="li-check">&#10003;</span>
                                </div>

                                <div class="lang-item" data-lang="es" data-name="Spanish" data-flag="es" onclick="switchLang(this)">
                                    <span class="li-flag">
                                        <img src="https://flagcdn.com/32x24/es.png" width="32" height="24" style="border-radius:4px; object-fit:cover;" alt="ES">
                                    </span>
                                    <span class="li-info">
                                        <span class="li-name">Spanish</span>
                                        <span class="li-code">ES · Español</span>
                                    </span>
                                    <span class="li-check">&#10003;</span>
                                </div>

                                <div class="lang-item" data-lang="fr" data-name="French" data-flag="fr" onclick="switchLang(this)">
                                    <span class="li-flag">
                                        <img src="https://flagcdn.com/32x24/fr.png" width="32" height="24" style="border-radius:4px; object-fit:cover;" alt="FR">
                                    </span>
                                    <span class="li-info">
                                        <span class="li-name">French</span>
                                        <span class="li-code">FR · Français</span>
                                    </span>
                                    <span class="li-check">&#10003;</span>
                                </div>

                                <div class="lang-item" data-lang="de" data-name="German" data-flag="de" onclick="switchLang(this)">
                                    <span class="li-flag">
                                        <img src="https://flagcdn.com/32x24/de.png" width="32" height="24" style="border-radius:4px; object-fit:cover;" alt="DE">
                                    </span>
                                    <span class="li-info">
                                        <span class="li-name">German</span>
                                        <span class="li-code">DE · Deutsch</span>
                                    </span>
                                    <span class="li-check">&#10003;</span>
                                </div>

                                <div class="lang-item" data-lang="ar" data-name="Arabic" data-flag="sa" onclick="switchLang(this)">
                                    <span class="li-flag">
                                        <img src="https://flagcdn.com/32x24/sa.png" width="32" height="24" style="border-radius:4px; object-fit:cover;" alt="SA">
                                    </span>
                                    <span class="li-info">
                                        <span class="li-name">Arabic</span>
                                        <span class="li-code">AR · العربية</span>
                                    </span>
                                    <span class="li-check">&#10003;</span>
                                </div>

                                <div class="lang-item" data-lang="zh-CN" data-name="Chinese" data-flag="cn" onclick="switchLang(this)">
                                    <span class="li-flag">
                                        <img src="https://flagcdn.com/32x24/cn.png" width="32" height="24" style="border-radius:4px; object-fit:cover;" alt="CN">
                                    </span>
                                    <span class="li-info">
                                        <span class="li-name">Chinese (Simplified)</span>
                                        <span class="li-code">ZH · 中文</span>
                                    </span>
                                    <span class="li-check">&#10003;</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    {{-- ── End Language Selector ── --}}
                </ul>
            </div>
        </div>
    </div>
</div>
<!--<div id="soro-blog"></div>-->
<!--<script src="https://app.trysoro.com/api/embed/c043a27c-7c4f-457c-9b76-a1a032079fd9" defer></script>-->

{{-- Google Translate + Instant Language Switcher (no page reload) --}}
<script>
    /* ── 0. Mobile nav toggle ────────────────────────────────────────── */
    function toggleMobNav() {
        var nav    = document.getElementById('mainNavbar');
        var toggle = document.getElementById('mobToggle');
        nav.classList.toggle('mob-open');
        toggle.classList.toggle('active');
    }
    // Close mobile nav when a normal link is clicked
    document.addEventListener('click', function(e) {
        var nav    = document.getElementById('mainNavbar');
        var toggle = document.getElementById('mobToggle');
        if (!nav || !toggle) return;
        // If click is outside nav and toggle, close it
        if (!nav.contains(e.target) && !toggle.contains(e.target)) {
            nav.classList.remove('mob-open');
            toggle.classList.remove('active');
        }
    });

    /* ── 1. Init Google Translate widget ─────────────────────────────── */
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage : 'en',
            autoDisplay  : false,
            multilanguagePage: true
        }, 'google_translate_element');
    }

    /* ── 2. Core: instant switch via doGTranslate() ──────────────────── */
    function doSwitchLang(langCode) {
        // doGTranslate is Google's internal function — translates without reload
        if (typeof doGTranslate === 'function') {
            doGTranslate('en|' + langCode);
            return true;
        }
        // Fallback: use the hidden select inside the Google widget iframe
        var iframe = document.querySelector('.goog-te-menu-frame');
        if (iframe) {
            var select = iframe.contentDocument
                            ? iframe.contentDocument.querySelector('.goog-te-combo')
                            : null;
            if (select) {
                select.value = langCode;
                select.dispatchEvent(new Event('change'));
                return true;
            }
        }
        // Last fallback: try the combo select directly in page
        var combo = document.querySelector('.goog-te-combo');
        if (combo) {
            combo.value = langCode;
            combo.dispatchEvent(new Event('change'));
            return true;
        }
        return false;
    }

    /* ── 3. Show spinner overlay while translating ───────────────────── */
    function showLangSpinner(name) {
        var ov = document.getElementById('langSpinnerOverlay');
        if (!ov) {
            ov = document.createElement('div');
            ov.id = 'langSpinnerOverlay';
            ov.style.cssText = [
                'position:fixed','inset:0','z-index:999999',
                'background:rgba(255,255,255,0.55)',
                'backdrop-filter:blur(3px)',
                'display:flex','align-items:center','justify-content:center',
                'flex-direction:column','gap:12px'
            ].join(';');
            ov.innerHTML =
                '<div style="width:46px;height:46px;border:4px solid #e3f2fd;border-top-color:#1976d2;border-radius:50%;animation:lgSpin .7s linear infinite;"></div>' +
                '<div style="font-size:14px;font-weight:600;color:#1565c0;">Translating to ' + name + '...</div>' +
                '<style>@keyframes lgSpin{to{transform:rotate(360deg)}}</style>';
            document.body.appendChild(ov);
        } else {
            ov.querySelector('div + div').textContent = 'Translating to ' + name + '...';
            ov.style.display = 'flex';
        }
    }

    function hideLangSpinner() {
        var ov = document.getElementById('langSpinnerOverlay');
        if (ov) ov.style.display = 'none';
    }

    /* ── 4. Toggle / close dropdown ─────────────────────────────────── */
    function toggleLangMenu(e) {
        e.stopPropagation();
        document.getElementById('langSelector').classList.toggle('open');
    }
    document.addEventListener('click', function(e) {
        var sel = document.getElementById('langSelector');
        if (sel && !sel.contains(e.target)) sel.classList.remove('open');
    });

    /* ── 5. Main switchLang called on click ──────────────────────────── */
    function switchLang(el) {
        var lang = el.getAttribute('data-lang');
        var name = el.getAttribute('data-name');
        var flag = el.getAttribute('data-flag');

        // Update UI immediately
        document.querySelectorAll('.lang-item').forEach(function(i) { i.classList.remove('active'); });
        el.classList.add('active');
        document.getElementById('lbFlag').src = 'https://flagcdn.com/20x15/' + flag + '.png';
        document.getElementById('lbFlag').alt = name;
        document.getElementById('lbName').textContent = name;
        document.getElementById('langSelector').classList.remove('open');

        // Save choice
        localStorage.setItem('gt_lang', lang);
        localStorage.setItem('gt_name', name);
        localStorage.setItem('gt_flag', flag);

        if (lang === 'en') {
            // Restore English instantly
            showLangSpinner('English');
            doSwitchLang('en');
            // Clear cookies too
            document.cookie = 'googtrans=/en/en; path=/';
            document.cookie = 'googtrans=/en/en; path=/; domain=.' + location.hostname;
            setTimeout(hideLangSpinner, 800);
            return;
        }

        // Show spinner, then translate
        showLangSpinner(name);

        // Small delay ensures widget is ready
        setTimeout(function() {
            var ok = doSwitchLang(lang);
            if (ok) {
                // Hide spinner after translation renders (~1.2s)
                setTimeout(hideLangSpinner, 1400);
            } else {
                // Widget not ready yet — wait and retry once
                setTimeout(function() {
                    doSwitchLang(lang);
                    setTimeout(hideLangSpinner, 1400);
                }, 800);
            }
        }, 100);
    }

    /* ── 6. Restore saved language on page load ──────────────────────── */
    window.addEventListener('load', function() {
        var lang = localStorage.getItem('gt_lang');
        var name = localStorage.getItem('gt_name');
        var flag = localStorage.getItem('gt_flag');
        if (lang && lang !== 'en') {
            // Restore UI button
            var item = document.querySelector('.lang-item[data-lang="' + lang + '"]');
            if (item) {
                document.querySelectorAll('.lang-item').forEach(function(i) { i.classList.remove('active'); });
                item.classList.add('active');
            }
            if (flag) document.getElementById('lbFlag').src = 'https://flagcdn.com/20x15/' + flag + '.png';
            document.getElementById('lbName').textContent = name || lang;

            // Re-apply translation after widget loads (wait for it)
            var tries = 0;
            var retry = setInterval(function() {
                tries++;
                if (doSwitchLang(lang)) {
                    clearInterval(retry);
                } else if (tries > 20) {
                    clearInterval(retry); // give up after 10s
                }
            }, 500);
        }
    });
</script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

