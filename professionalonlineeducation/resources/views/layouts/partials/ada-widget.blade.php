{{-- ============================================================
     ADA / WCAG 2.1 Accessibility Widget  —  v2.0
     Sticky bottom-left  |  All pages  |  localStorage persist
     ============================================================ --}}
<style>
/* ═══════════════════════════════════════════════════════════
   RESET (scoped to #ada-*)
═══════════════════════════════════════════════════════════ */
#ada-trigger,#ada-panel,#ada-panel *,#ada-keyboard-modal,#ada-keyboard-modal *{
    box-sizing:border-box;
    font-family:'Segoe UI',Arial,sans-serif;
    line-height:1.4;
}

/* ═══════════════════════════════════════════════════════════
   TRIGGER BUTTON
═══════════════════════════════════════════════════════════ */
#ada-trigger {
    position: fixed;
    bottom: 85px;
    left: 20px;
    z-index: 99998;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0 16px 0 10px;
    height: 48px;
    background: linear-gradient(135deg, #1a73e8 0%, #0d5bbf 100%);
    border-radius: 28px;
    cursor: pointer;
    box-shadow: 0 4px 18px rgba(26,115,232,0.45), 0 2px 6px rgba(0,0,0,0.18);
    border: 2.5px solid rgba(255,255,255,0.35);
    transition: all 0.25s cubic-bezier(0.34,1.56,0.64,1);
    outline: none;
    user-select: none;
}
#ada-trigger:hover {
    transform: translateY(-2px) scale(1.03);
    box-shadow: 0 8px 28px rgba(26,115,232,0.55), 0 3px 10px rgba(0,0,0,0.2);
}
#ada-trigger:active { transform: scale(0.97); }
#ada-trigger:focus-visible {
    outline: 3px solid #fff;
    outline-offset: 2px;
}

/* Wheelchair icon circle */
#ada-trigger .ada-icon-wrap {
    width: 32px;
    height: 32px;
    background: rgba(255,255,255,0.22);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
#ada-trigger svg {
    width: 18px;
    height: 18px;
    fill: #fff;
}
#ada-trigger .ada-label {
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.3px;
    white-space: nowrap;
}

/* Pulse ring */
#ada-trigger::before {
    content: '';
    position: absolute;
    inset: -5px;
    border-radius: 32px;
    border: 2px solid rgba(26,115,232,0.4);
    animation: ada-pulse 2.5s ease-out infinite;
}
@keyframes ada-pulse {
    0%   { opacity:1; transform:scale(1); }
    70%  { opacity:0; transform:scale(1.25); }
    100% { opacity:0; transform:scale(1.25); }
}
#ada-trigger[aria-expanded="true"]::before { animation: none; }

/* ═══════════════════════════════════════════════════════════
   PANEL
═══════════════════════════════════════════════════════════ */
#ada-panel {
    position: fixed;
    bottom: 148px;
    left: 20px;
    z-index: 99999;
    width: 310px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.18), 0 4px 16px rgba(0,0,0,0.1);
    border: 1px solid rgba(26,115,232,0.15);
    overflow: hidden;

    /* Animation */
    opacity: 0;
    transform: translateY(16px) scale(0.97);
    pointer-events: none;
    transition: opacity 0.22s ease, transform 0.22s cubic-bezier(0.34,1.56,0.64,1);
}
#ada-panel.ada-open {
    opacity: 1;
    transform: translateY(0) scale(1);
    pointer-events: all;
}

/* ── Panel Header ── */
.ada-header {
    background: linear-gradient(135deg, #1a73e8 0%, #0d5bbf 100%);
    padding: 14px 16px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}
.ada-header::after {
    content: '♿';
    position: absolute;
    right: 50px;
    top: -4px;
    font-size: 52px;
    opacity: 0.08;
    line-height: 1;
}
.ada-header-left { display: flex; align-items: center; gap: 10px; }
.ada-header-icon {
    width: 34px;
    height: 34px;
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.ada-header-icon svg { width: 20px; height: 20px; fill: #fff; }
.ada-header-title {
    color: #fff;
    font-size: 15px;
    font-weight: 700;
    margin: 0;
    line-height: 1.2;
}
.ada-header-sub {
    color: rgba(255,255,255,0.78);
    font-size: 10.5px;
    margin: 2px 0 0;
}
.ada-close-btn {
    width: 28px;
    height: 28px;
    background: rgba(255,255,255,0.15);
    border: none;
    border-radius: 8px;
    color: #fff;
    font-size: 18px;
    font-weight: 300;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
    padding: 0;
    line-height: 1;
}
.ada-close-btn:hover { background: rgba(255,255,255,0.28); }
.ada-close-btn:focus-visible { outline: 2px solid #fff; }

/* ── Scrollable body ── */
.ada-body {
    max-height: 68vh;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 0 0 4px;
    scrollbar-width: thin;
    scrollbar-color: #c5d5ea transparent;
}
.ada-body::-webkit-scrollbar { width: 5px; }
.ada-body::-webkit-scrollbar-track { background: transparent; }
.ada-body::-webkit-scrollbar-thumb { background: #c5d5ea; border-radius: 10px; }

/* ── Section ── */
.ada-section {
    padding: 12px 14px 6px;
}
.ada-section + .ada-section {
    border-top: 1px solid #f0f4f9;
}
.ada-section-title {
    font-size: 9.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #9baab8;
    margin-bottom: 9px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.ada-section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #edf1f7;
}

/* ── Font Size Control ── */
.ada-font-ctrl {
    display: flex;
    align-items: center;
    gap: 6px;
    background: #f4f7fc;
    border-radius: 10px;
    padding: 6px 8px;
    margin-bottom: 6px;
}
.ada-font-btn {
    width: 36px;
    height: 36px;
    border: 1.5px solid #d0daea;
    border-radius: 8px;
    background: #fff;
    cursor: pointer;
    font-weight: 800;
    color: #2c4a6e;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s;
    flex-shrink: 0;
    padding: 0;
}
.ada-font-btn:hover {
    background: #1a73e8;
    border-color: #1a73e8;
    color: #fff;
    transform: scale(1.08);
}
.ada-font-btn:active { transform: scale(0.95); }
.ada-font-track {
    flex: 1;
    background: #e8eef8;
    border-radius: 6px;
    height: 6px;
    position: relative;
    overflow: hidden;
}
.ada-font-track-fill {
    position: absolute;
    left: 0; top: 0; bottom: 0;
    background: linear-gradient(90deg, #1a73e8, #4fa3f7);
    border-radius: 6px;
    transition: width 0.2s ease;
    width: 37.5%; /* default 100% = 37.5% of 80-160 range at 100% */
}
.ada-font-display {
    font-size: 12px;
    font-weight: 800;
    color: #1a73e8;
    min-width: 38px;
    text-align: center;
    background: #fff;
    border: 1.5px solid #d0daea;
    border-radius: 7px;
    padding: 4px 4px;
    flex-shrink: 0;
}
.ada-font-reset {
    font-size: 10px;
    font-weight: 700;
    color: #8899aa;
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px 2px;
    text-decoration: underline;
    flex-shrink: 0;
    transition: color 0.15s;
}
.ada-font-reset:hover { color: #1a73e8; }

/* ── Feature Grid ── */
.ada-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 7px;
    margin-bottom: 4px;
}
.ada-feat-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 11px 8px 9px;
    border: 1.5px solid #e4eaf3;
    border-radius: 10px;
    background: #f7f9fd;
    cursor: pointer;
    font-size: 11px;
    font-weight: 600;
    color: #445567;
    text-align: center;
    transition: all 0.18s ease;
    user-select: none;
    position: relative;
    overflow: hidden;
    outline: none;
}
.ada-feat-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #1a73e8, #0d5bbf);
    opacity: 0;
    transition: opacity 0.18s;
}
.ada-feat-btn:hover {
    border-color: #1a73e8;
    color: #1a73e8;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(26,115,232,0.15);
}
.ada-feat-btn.ada-on {
    border-color: #1a73e8;
    background: #1a73e8;
    color: #fff;
    box-shadow: 0 4px 14px rgba(26,115,232,0.3);
}
.ada-feat-btn.ada-on::before { opacity: 1; }
.ada-feat-btn:focus-visible {
    outline: 2px solid #1a73e8;
    outline-offset: 2px;
}

/* Icon container */
.ada-feat-icon {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.18s;
    position: relative;
    z-index: 1;
}
.ada-feat-btn:not(.ada-on) .ada-feat-icon {
    background: #e8eef8;
}
.ada-feat-btn.ada-on .ada-feat-icon {
    background: rgba(255,255,255,0.22);
}
.ada-feat-icon svg {
    width: 18px;
    height: 18px;
    fill: #5c7a9e;
    transition: fill 0.18s;
    position: relative;
    z-index: 1;
}
.ada-feat-btn:hover:not(.ada-on) .ada-feat-icon svg { fill: #1a73e8; }
.ada-feat-btn.ada-on .ada-feat-icon svg { fill: #fff; }

/* Label */
.ada-feat-label {
    position: relative;
    z-index: 1;
    line-height: 1.2;
}

/* Active checkmark badge */
.ada-feat-btn.ada-on::after {
    content: '✓';
    position: absolute;
    top: 5px;
    right: 7px;
    font-size: 9px;
    font-weight: 900;
    color: rgba(255,255,255,0.9);
    z-index: 2;
}

/* ── Reset button ── */
.ada-reset-section {
    padding: 8px 14px 14px;
    border-top: 1px solid #f0f4f9;
}
.ada-reset-btn {
    width: 100%;
    padding: 11px;
    background: linear-gradient(135deg, #f44336, #c62828);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.18s;
    letter-spacing: 0.2px;
    box-shadow: 0 3px 10px rgba(244,67,54,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
}
.ada-reset-btn:hover {
    background: linear-gradient(135deg, #e53935, #b71c1c);
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(244,67,54,0.35);
}
.ada-reset-btn:active { transform: scale(0.98); }
.ada-reset-btn svg { width: 16px; height: 16px; fill: #fff; }

/* ── Footer ── */
.ada-panel-footer {
    background: linear-gradient(135deg, #f4f7fc, #edf1f8);
    padding: 9px 16px;
    font-size: 10px;
    color: #98aabb;
    text-align: center;
    border-top: 1px solid #e8eef8;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
.ada-panel-footer svg { width: 11px; height: 11px; fill: #b0c0d0; flex-shrink: 0; }

/* ═══════════════════════════════════════════════════════════
   READING GUIDE
═══════════════════════════════════════════════════════════ */
#ada-reading-guide {
    display: none;
    position: fixed;
    left: 0;
    width: 100%;
    height: 32px;
    background: rgba(255, 235, 59, 0.32);
    border-top: 2px solid rgba(255, 193, 7, 0.6);
    border-bottom: 2px solid rgba(255, 193, 7, 0.6);
    pointer-events: none;
    z-index: 99988;
    backdrop-filter: brightness(1.02);
}
body.ada-reading-guide-on #ada-reading-guide { display: block; }

/* ═══════════════════════════════════════════════════════════
   KEYBOARD MODAL
═══════════════════════════════════════════════════════════ */
#ada-keyboard-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15,25,50,0.55);
    z-index: 999999;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(3px);
    padding: 20px;
}
#ada-keyboard-modal.ada-kmod-open { display: flex; }
.ada-kmod-box {
    background: #fff;
    border-radius: 16px;
    padding: 26px 28px 22px;
    max-width: 400px;
    width: 100%;
    box-shadow: 0 24px 60px rgba(0,0,0,0.22);
    animation: ada-modal-in 0.2s ease;
}
@keyframes ada-modal-in {
    from { opacity:0; transform:scale(0.93) translateY(10px); }
    to   { opacity:1; transform:scale(1) translateY(0); }
}
.ada-kmod-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
}
.ada-kmod-header svg { width: 22px; height: 22px; fill: #1a73e8; flex-shrink: 0; }
.ada-kmod-header h3 { margin: 0; font-size: 16px; font-weight: 800; color: #1a2c4e; }
.ada-kmod-table { width: 100%; border-collapse: collapse; }
.ada-kmod-table tr { border-bottom: 1px solid #f0f4f9; }
.ada-kmod-table tr:last-child { border-bottom: none; }
.ada-kmod-table td { padding: 9px 4px; font-size: 13px; color: #445567; }
.ada-kmod-table td:first-child {
    width: 130px;
    font-family: 'Courier New', monospace;
    font-weight: 700;
    color: #fff;
}
.ada-kmod-table td:first-child span {
    display: inline-block;
    background: linear-gradient(135deg, #2c4a6e, #1a3356);
    color: #fff;
    padding: 3px 10px;
    border-radius: 5px;
    font-size: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    border-bottom: 2px solid #0d2040;
}
.ada-kmod-close {
    width: 100%;
    margin-top: 18px;
    padding: 11px;
    background: linear-gradient(135deg, #1a73e8, #0d5bbf);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.15s;
    box-shadow: 0 4px 14px rgba(26,115,232,0.3);
}
.ada-kmod-close:hover { background: linear-gradient(135deg, #1565c0, #0a3d8f); }

/* ═══════════════════════════════════════════════════════════
   GLOBAL ACCESSIBILITY STATES
═══════════════════════════════════════════════════════════ */

/* High Contrast */
body.ada-high-contrast { background: #000 !important; }
body.ada-high-contrast *:not(#ada-panel):not(#ada-panel *):not(#ada-trigger):not(#ada-trigger *):not(#cci-chatbot-root):not(#cci-chatbot-root *) {
    background-color: #000 !important;
    color: #ffff00 !important;
    border-color: #ffff00 !important;
    text-shadow: none !important;
}
body.ada-high-contrast a:not(#ada-panel a):not(#cci-chatbot-root a) { color: #00e5ff !important; }
body.ada-high-contrast img:not(#ada-panel img):not(#cci-chatbot-root img) { filter: contrast(130%); }

/* Negative Contrast */
body.ada-negative > *:not(#ada-panel):not(#ada-trigger):not(#ada-reading-guide):not(#ada-keyboard-modal):not(#cci-chatbot-root) {
    filter: invert(100%);
}
body.ada-negative img:not(#ada-panel img):not(#cci-chatbot-root img) { filter: invert(100%); }

/* Grayscale */
body.ada-grayscale > *:not(#ada-panel):not(#ada-trigger):not(#ada-reading-guide):not(#ada-keyboard-modal):not(#cci-chatbot-root) {
    filter: grayscale(100%);
}

/* Underline Links */
body.ada-links-underline a { text-decoration: underline !important; }

/* Readable Font */
body.ada-readable-font *:not(#ada-panel *) {
    font-family: 'Arial', 'Helvetica', sans-serif !important;
    letter-spacing: 0.4px !important;
    word-spacing: 2px !important;
}
body.ada-readable-font p:not(#ada-panel p),
body.ada-readable-font li:not(#ada-panel li) {
    line-height: 1.8 !important;
}

/* Support chatbot: restore intended typography (readable-font would flatten all text) */
body.ada-readable-font #cci-chatbot-root,
body.ada-readable-font #cci-chatbot-root * {
    font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
    letter-spacing: normal !important;
    word-spacing: normal !important;
}
body.ada-readable-font #cci-chatbot-root p,
body.ada-readable-font #cci-chatbot-root li {
    line-height: 1.45 !important;
}

/* Text Spacing */
body.ada-text-spacing p:not(#ada-panel p),
body.ada-text-spacing li:not(#ada-panel li),
body.ada-text-spacing span:not(#ada-panel span) {
    line-height: 2.1 !important;
    letter-spacing: 1.6px !important;
    word-spacing: 5px !important;
}

body.ada-text-spacing #cci-chatbot-root p,
body.ada-text-spacing #cci-chatbot-root li,
body.ada-text-spacing #cci-chatbot-root span {
    line-height: 1.45 !important;
    letter-spacing: normal !important;
    word-spacing: normal !important;
}

/* Highlight Focus */
body.ada-highlight-focus *:focus {
    outline: 3px solid #ff6d00 !important;
    outline-offset: 3px !important;
    border-radius: 2px !important;
}

/* Big Cursor */
body.ada-big-cursor, body.ada-big-cursor * {
    cursor: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='42' height='42' viewBox='0 0 42 42'%3E%3Cpath d='M6 3 L6 36 L14 28 L19 40 L24 38 L19 26 L29 26 Z' fill='%23111' stroke='%23fff' stroke-width='2.5' stroke-linejoin='round'/%3E%3C/svg%3E") 0 0, auto !important;
}

/* ═══════════════════════════════════════════════════════════
   MOBILE
═══════════════════════════════════════════════════════════ */
@media (max-width: 480px) {
    #ada-trigger { bottom: 75px; left: 12px; padding: 0 12px 0 8px; height: 44px; }
    #ada-trigger .ada-label { font-size: 12px; }
    #ada-panel { width: calc(100vw - 24px); left: 12px; bottom: 132px; }
    .ada-body { max-height: 60vh; }
}
</style>

{{-- ── Reading guide ── --}}
<div id="ada-reading-guide" aria-hidden="true"></div>

{{-- ══════════════════════════════════════════════════════════
     TRIGGER BUTTON
══════════════════════════════════════════════════════════ --}}
<div id="ada-trigger"
     role="button"
     aria-label="Open Accessibility Options"
     aria-expanded="false"
     aria-controls="ada-panel"
     tabindex="0">
    <div class="ada-icon-wrap">
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="12" cy="4.5" r="2.2"/>
            <path d="M12 8c-3.8 0-6.5 1.4-6.5 3.2l1.8 1.1 1.7-.9v8.6h2v-4.5h2v4.5h2v-8.6l1.7.9 1.8-1.1C18.5 9.4 15.8 8 12 8z"/>
        </svg>
    </div>
    <span class="ada-label">Accessibility</span>
</div>

{{-- ══════════════════════════════════════════════════════════
     ACCESSIBILITY PANEL
══════════════════════════════════════════════════════════ --}}
<div id="ada-panel"
     role="dialog"
     aria-label="Accessibility Options"
     aria-modal="true">

    {{-- Header --}}
    <div class="ada-header">
        <div class="ada-header-left">
            <div class="ada-header-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="4.5" r="2.2"/>
                    <path d="M12 8c-3.8 0-6.5 1.4-6.5 3.2l1.8 1.1 1.7-.9v8.6h2v-4.5h2v4.5h2v-8.6l1.7.9 1.8-1.1C18.5 9.4 15.8 8 12 8z"/>
                </svg>
            </div>
            <div>
                <div class="ada-header-title">Accessibility</div>
                <div class="ada-header-sub">WCAG 2.1 AA Compliance Tools</div>
            </div>
        </div>
        <button class="ada-close-btn" id="ada-close-btn" aria-label="Close accessibility panel">&#215;</button>
    </div>

    {{-- Scrollable body --}}
    <div class="ada-body">

        {{-- ── TEXT SIZE ── --}}
        <div class="ada-section">
            <div class="ada-section-title">Text Size</div>
            <div class="ada-font-ctrl">
                <button class="ada-font-btn" id="ada-font-dec" aria-label="Decrease text size" title="Decrease">
                    <svg viewBox="0 0 24 24" style="width:14px;height:14px;fill:currentColor"><path d="M19 13H5v-2h14v2z"/></svg>
                </button>
                <div style="flex:1; display:flex; flex-direction:column; gap:4px;">
                    <div class="ada-font-track">
                        <div class="ada-font-track-fill" id="ada-font-track-fill"></div>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:9px;color:#aab;">
                        <span>80%</span><span>120%</span><span>160%</span>
                    </div>
                </div>
                <button class="ada-font-btn" id="ada-font-inc" aria-label="Increase text size" title="Increase">
                    <svg viewBox="0 0 24 24" style="width:14px;height:14px;fill:currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                </button>
                <div class="ada-font-display" id="ada-font-display">100%</div>
                <button class="ada-font-reset" id="ada-font-reset-size" aria-label="Reset text size">Reset</button>
            </div>
        </div>

        {{-- ── DISPLAY ── --}}
        <div class="ada-section">
            <div class="ada-section-title">Color &amp; Display</div>
            <div class="ada-grid">

                <button class="ada-feat-btn" id="btn-contrast" data-feature="ada-high-contrast"
                        aria-pressed="false" tabindex="0">
                    <div class="ada-feat-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 0 0 0 20V2z"/><circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                    </div>
                    <span class="ada-feat-label">High Contrast</span>
                </button>

                <button class="ada-feat-btn" id="btn-negative" data-feature="ada-negative"
                        aria-pressed="false" tabindex="0">
                    <div class="ada-feat-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm0-3a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg>
                    </div>
                    <span class="ada-feat-label">Neg. Contrast</span>
                </button>

                <button class="ada-feat-btn" id="btn-grayscale" data-feature="ada-grayscale"
                        aria-pressed="false" tabindex="0">
                    <div class="ada-feat-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93V4.07C7.05 4.56 4 8.02 4 12s3.05 7.44 7 7.93z"/></svg>
                    </div>
                    <span class="ada-feat-label">Grayscale</span>
                </button>

                <button class="ada-feat-btn" id="btn-links" data-feature="ada-links-underline"
                        aria-pressed="false" tabindex="0">
                    <div class="ada-feat-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 17c3.31 0 6-2.69 6-6V3h-2.5v8c0 1.93-1.57 3.5-3.5 3.5S8.5 12.93 8.5 11V3H6v8c0 3.31 2.69 6 6 6zm-7 2v2h14v-2H5z"/></svg>
                    </div>
                    <span class="ada-feat-label">Underline Links</span>
                </button>

            </div>
        </div>

        {{-- ── READING ── --}}
        <div class="ada-section">
            <div class="ada-section-title">Reading &amp; Focus</div>
            <div class="ada-grid">

                <button class="ada-feat-btn" id="btn-readable" data-feature="ada-readable-font"
                        aria-pressed="false" tabindex="0">
                    <div class="ada-feat-icon">
                        <svg viewBox="0 0 24 24"><path d="M9.17 15.5h5.64l1.14 3h2.09L13 4h-2L6.18 18.5h2.05l.94-3zM12 6.29l2.24 7.21H9.76L12 6.29z"/></svg>
                    </div>
                    <span class="ada-feat-label">Readable Font</span>
                </button>

                <button class="ada-feat-btn" id="btn-spacing" data-feature="ada-text-spacing"
                        aria-pressed="false" tabindex="0">
                    <div class="ada-feat-icon">
                        <svg viewBox="0 0 24 24"><path d="M4 6h16v2H4zm4 5h8v2H8zm-4 5h16v2H4z"/></svg>
                    </div>
                    <span class="ada-feat-label">Text Spacing</span>
                </button>

                <button class="ada-feat-btn" id="btn-guide" data-feature="ada-reading-guide-on"
                        aria-pressed="false" tabindex="0">
                    <div class="ada-feat-icon">
                        <svg viewBox="0 0 24 24"><path d="M3 9v6h18V9H3zm16 4H5v-2h14v2z"/></svg>
                    </div>
                    <span class="ada-feat-label">Reading Guide</span>
                </button>

                <button class="ada-feat-btn" id="btn-focus" data-feature="ada-highlight-focus"
                        aria-pressed="false" tabindex="0">
                    <div class="ada-feat-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                    </div>
                    <span class="ada-feat-label">Focus Highlight</span>
                </button>

            </div>
        </div>

        {{-- ── NAVIGATION ── --}}
        <div class="ada-section">
            <div class="ada-section-title">Navigation</div>
            <div class="ada-grid">

                <button class="ada-feat-btn" id="btn-cursor" data-feature="ada-big-cursor"
                        aria-pressed="false" tabindex="0">
                    <div class="ada-feat-icon">
                        <svg viewBox="0 0 24 24"><path d="M4 0l16 12.279-6.951 1.17 4.325 8.817-3.596 1.734-4.35-8.879L4 18.617z"/></svg>
                    </div>
                    <span class="ada-feat-label">Big Cursor</span>
                </button>

                <button class="ada-feat-btn" id="btn-keyboard" aria-pressed="false" tabindex="0"
                        onclick="adaShowKeyboardHelp()">
                    <div class="ada-feat-icon">
                        <svg viewBox="0 0 24 24"><path d="M20 5H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm-9 3h2v2h-2V8zm0 3h2v2h-2v-2zM8 8h2v2H8V8zm0 3h2v2H8v-2zm-1 5H5v-2h2v2zm10 0H9v-2h8v2zm0-3h-2v-2h2v2zm0-3h-2V8h2v2zm2 3h-2v-2h2v2z"/></svg>
                    </div>
                    <span class="ada-feat-label">Keyboard Tips</span>
                </button>

            </div>
        </div>

        {{-- ── RESET ── --}}
        <div class="ada-reset-section">
            <button class="ada-reset-btn" id="ada-reset-all" aria-label="Reset all accessibility settings">
                <svg viewBox="0 0 24 24"><path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg>
                Reset All Settings
            </button>
        </div>

    </div>{{-- end .ada-body --}}

    {{-- Footer --}}
    <div class="ada-panel-footer">
        <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 4.41l5 2.22V11c0 3.47-2.37 6.7-5 7.72-2.63-1.01-5-4.25-5-7.72V7.63l5-2.22z"/></svg>
        ADA / WCAG 2.1 AA &bull; Professional Online Education
    </div>

</div>{{-- end #ada-panel --}}

{{-- ══════════════════════════════════════════════════════════
     KEYBOARD TIPS MODAL
══════════════════════════════════════════════════════════ --}}
<div id="ada-keyboard-modal" role="dialog" aria-modal="true" aria-label="Keyboard Navigation Tips">
    <div class="ada-kmod-box">
        <div class="ada-kmod-header">
            <svg viewBox="0 0 24 24"><path d="M20 5H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm-9 3h2v2h-2V8zm0 3h2v2h-2v-2zM8 8h2v2H8V8zm0 3h2v2H8v-2zm-1 5H5v-2h2v2zm10 0H9v-2h8v2zm0-3h-2v-2h2v2zm0-3h-2V8h2v2zm2 3h-2v-2h2v2z"/></svg>
            <h3>Keyboard Navigation</h3>
        </div>
        <table class="ada-kmod-table">
            <tr><td><span>Tab</span></td><td>Move to next focusable element</td></tr>
            <tr><td><span>Shift + Tab</span></td><td>Move to previous element</td></tr>
            <tr><td><span>Enter / Space</span></td><td>Activate button or link</td></tr>
            <tr><td><span>Arrow Keys</span></td><td>Navigate menus and lists</td></tr>
            <tr><td><span>Escape</span></td><td>Close dialogs and menus</td></tr>
            <tr><td><span>Ctrl + +</span></td><td>Zoom in (browser)</td></tr>
            <tr><td><span>Ctrl + −</span></td><td>Zoom out (browser)</td></tr>
            <tr><td><span>Ctrl + 0</span></td><td>Reset browser zoom</td></tr>
        </table>
        <button class="ada-kmod-close" onclick="adaCloseKeyboardHelp()">Close</button>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════════════════════════ --}}
<script>
(function () {
    'use strict';

    var ADA_KEY     = 'ada_v2_settings';
    var fontStep    = 10;
    var fontMin     = 80;
    var fontMax     = 160;
    var currentFont = 100;

    var features = [
        'ada-high-contrast','ada-negative','ada-grayscale',
        'ada-links-underline','ada-readable-font','ada-text-spacing',
        'ada-reading-guide-on','ada-highlight-focus','ada-big-cursor'
    ];

    /* ── Font track fill ── */
    function updateTrack() {
        var pct = ((currentFont - fontMin) / (fontMax - fontMin)) * 100;
        var fill = document.getElementById('ada-font-track-fill');
        if (fill) fill.style.width = pct + '%';
    }

    /* ── Apply font size ── */
    function applyFontSize() {
        document.documentElement.style.fontSize = currentFont + '%';
        var d = document.getElementById('ada-font-display');
        if (d) d.textContent = currentFont + '%';
        updateTrack();
    }

    /* ── Save to localStorage ── */
    function save() {
        var active = features.filter(function (c) {
            return document.body.classList.contains(c);
        });
        try {
            localStorage.setItem(ADA_KEY, JSON.stringify({ fontSize: currentFont, active: active }));
        } catch(e) {}
    }

    /* ── Load from localStorage ── */
    function load() {
        try {
            var s = JSON.parse(localStorage.getItem(ADA_KEY) || '{}');
            if (s.fontSize && s.fontSize >= fontMin && s.fontSize <= fontMax) {
                currentFont = s.fontSize;
                applyFontSize();
            }
            if (s.active && Array.isArray(s.active)) {
                s.active.forEach(function (c) {
                    document.body.classList.add(c);
                    var b = document.querySelector('[data-feature="' + c + '"]');
                    if (b) { b.classList.add('ada-on'); b.setAttribute('aria-pressed','true'); }
                });
            }
        } catch(e) {}
    }

    /* ── Toggle feature ── */
    function toggleFeat(cls, btn) {
        var on = document.body.classList.toggle(cls);
        btn.classList.toggle('ada-on', on);
        btn.setAttribute('aria-pressed', on ? 'true' : 'false');
        if (on) {
            var contrasts = ['ada-high-contrast','ada-negative','ada-grayscale'];
            if (contrasts.indexOf(cls) !== -1) {
                contrasts.forEach(function (c) { if (c !== cls) removeFeat(c); });
            }
        }
        save();
    }

    function removeFeat(cls) {
        document.body.classList.remove(cls);
        var b = document.querySelector('[data-feature="' + cls + '"]');
        if (b) { b.classList.remove('ada-on'); b.setAttribute('aria-pressed','false'); }
    }

    /* ── Reset ── */
    function resetAll() {
        features.forEach(removeFeat);
        currentFont = 100;
        applyFontSize();
        try { localStorage.removeItem(ADA_KEY); } catch(e) {}
    }

    /* ── Panel ── */
    var trigger  = document.getElementById('ada-trigger');
    var panel    = document.getElementById('ada-panel');
    var closeBtn = document.getElementById('ada-close-btn');

    function openPanel()  {
        panel.classList.add('ada-open');
        trigger.setAttribute('aria-expanded','true');
        closeBtn.focus();
    }
    function closePanel() {
        panel.classList.remove('ada-open');
        trigger.setAttribute('aria-expanded','false');
    }
    function togglePanel() {
        panel.classList.contains('ada-open') ? closePanel() : openPanel();
    }

    trigger.addEventListener('click', togglePanel);
    trigger.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); togglePanel(); }
    });
    closeBtn.addEventListener('click', closePanel);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (panel.classList.contains('ada-open')) closePanel();
            adaCloseKeyboardHelp();
        }
    });

    /* Close panel when clicking outside */
    document.addEventListener('click', function(e) {
        if (panel.classList.contains('ada-open') &&
            !panel.contains(e.target) &&
            !trigger.contains(e.target)) {
            closePanel();
        }
    });

    /* ── Feature buttons ── */
    document.querySelectorAll('.ada-feat-btn[data-feature]').forEach(function (btn) {
        btn.addEventListener('click', function () { toggleFeat(btn.dataset.feature, btn); });
        btn.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggleFeat(btn.dataset.feature, btn); }
        });
    });

    /* ── Font size ── */
    document.getElementById('ada-font-inc').addEventListener('click', function () {
        if (currentFont < fontMax) { currentFont += fontStep; applyFontSize(); save(); }
    });
    document.getElementById('ada-font-dec').addEventListener('click', function () {
        if (currentFont > fontMin) { currentFont -= fontStep; applyFontSize(); save(); }
    });
    document.getElementById('ada-font-reset-size').addEventListener('click', function () {
        currentFont = 100; applyFontSize(); save();
    });

    /* ── Reset all ── */
    document.getElementById('ada-reset-all').addEventListener('click', resetAll);

    /* ── Reading guide ── */
    var guide = document.getElementById('ada-reading-guide');
    document.addEventListener('mousemove', function (e) {
        if (guide && document.body.classList.contains('ada-reading-guide-on')) {
            guide.style.top = (e.clientY - 16) + 'px';
        }
    });

    /* ── Init ── */
    load();
    updateTrack();

})();

/* Keyboard modal (global) */
function adaShowKeyboardHelp() {
    document.getElementById('ada-keyboard-modal').classList.add('ada-kmod-open');
}
function adaCloseKeyboardHelp() {
    document.getElementById('ada-keyboard-modal').classList.remove('ada-kmod-open');
}
document.getElementById('ada-keyboard-modal').addEventListener('click', function (e) {
    if (e.target === this) adaCloseKeyboardHelp();
});
</script>
