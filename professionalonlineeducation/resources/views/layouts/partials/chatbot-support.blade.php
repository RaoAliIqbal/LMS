{{-- Floating support chatbot — colors aligned with site header / CCI branding --}}
@php
    $cciChatPath = base_path('webAssets/js/cci-chatbot.js');
    $cciChatV = file_exists($cciChatPath) ? (string) filemtime($cciChatPath) : '1';
@endphp
<style>
    /* Scoped design tokens (match header.blade / web.blade blues + nav green) */
    #cci-chatbot-root {
        --cci-blue: #1379ba;
        --cci-blue-mid: #1a6fc4;
        --cci-blue-bright: #2196f3;
        --cci-blue-dark: #0e5a96;
        --cci-blue-soft: #e3f2fd;
        --cci-blue-ghost: #f0f7fc;
        --cci-green: #6ba546;
        --cci-green-dark: #5a9239;
        --cci-text: #1a2e44;
        --cci-muted: #5a6b7d;
        --cci-line: #c5dce8;
        --cci-radius: 16px;
        --cci-radius-sm: 10px;
        font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        isolation: isolate;
    }

    /* Shield from site-wide / Bootstrap leaks (style.css .btn, form resets, etc.) */
    #cci-chatbot-root,
    #cci-chatbot-root *,
    #cci-chatbot-root *::before,
    #cci-chatbot-root *::after {
        box-sizing: border-box;
    }

    #cci-chatbot-root button.cci-chatbot-btn {
        -webkit-appearance: none;
        appearance: none;
        text-transform: none !important;
        letter-spacing: normal !important;
        font-family: inherit !important;
        line-height: 1.3;
        width: auto;
        min-height: 0;
    }

    #cci-chatbot-root input:not([type="hidden"]),
    #cci-chatbot-root textarea {
        -webkit-appearance: none;
        appearance: none;
        max-width: 100%;
        min-width: 0;
    }

    #cci-chatbot-fab {
        position: fixed;
        right: max(18px, env(safe-area-inset-right));
        bottom: max(22px, env(safe-area-inset-bottom));
        z-index: 200000;
        width: 58px;
        height: 58px;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,.92);
        cursor: pointer;
        background: linear-gradient(145deg, var(--cci-blue) 0%, var(--cci-blue-mid) 45%, var(--cci-blue-bright) 100%);
        color: #fff;
        box-shadow:
            0 4px 0 rgba(14, 90, 150, .35),
            0 10px 28px rgba(19, 121, 186, .42),
            0 0 0 1px rgba(19, 121, 186, .15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        transition: transform .22s ease, box-shadow .22s ease, filter .22s ease;
    }
    #cci-chatbot-fab:hover {
        transform: translateY(-2px) scale(1.04);
        filter: brightness(1.05);
        box-shadow:
            0 6px 0 rgba(14, 90, 150, .28),
            0 16px 36px rgba(19, 121, 186, .48);
    }
    #cci-chatbot-fab:active { transform: translateY(0) scale(.98); }
    #cci-chatbot-fab .fa { filter: drop-shadow(0 1px 1px rgba(0,0,0,.12)); }

    #cci-chatbot-panel {
        position: fixed;
        right: max(18px, env(safe-area-inset-right));
        bottom: calc(88px + env(safe-area-inset-bottom));
        top: auto;
        z-index: 199999;
        width: 100%;
        max-width: 412px;
        max-height: calc(100vh - 160px);
        background: #fff;
        border-radius: var(--cci-radius);
        box-shadow:
            0 0 0 1px rgba(19, 121, 186, .12),
            0 24px 56px rgba(26, 46, 68, .18),
            0 8px 24px rgba(19, 121, 186, .1);
        display: none;
        overflow: hidden;
        min-height: 0;
        box-sizing: border-box;
    }
    /* Flex column + scroll wrapper: fixes middle area collapsing so the dock cannot cover messages */
    #cci-chatbot-root #cci-chatbot-panel.is-open {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        animation: cciChatPanelIn .24s ease-out;
    }
    @keyframes cciChatPanelIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    #cci-chatbot-root #cci-chatbot-scroll-wrap {
        flex: 1 1 0%;
        min-height: 0;
        min-width: 0;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        position: relative !important;
        top: auto !important;
        right: auto !important;
        bottom: auto !important;
        left: auto !important;
    }

    #cci-chatbot-root #cci-chatbot-panel-dock {
        flex: 0 0 auto;
        flex-shrink: 0;
        min-width: 0;
        display: flex;
        flex-direction: column;
        background: #fff;
        border-top: 1px solid var(--cci-line);
        box-shadow: 0 -8px 24px rgba(19, 121, 186, .08);
        position: relative !important;
        top: auto !important;
        right: auto !important;
        bottom: auto !important;
        left: auto !important;
    }

    .cci-chatbot-head {
        position: relative;
        z-index: 2;
        overflow: hidden;
        background: linear-gradient(125deg, var(--cci-blue-dark) 0%, var(--cci-blue) 42%, var(--cci-blue-mid) 72%, var(--cci-blue-bright) 100%);
        color: #fff;
        padding: 14px 16px 16px;
        flex-shrink: 0;
    }
    .cci-chatbot-head::after {
        content: "";
        position: absolute;
        left: 0; right: 0; bottom: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--cci-green) 0%, #8bc34a 50%, var(--cci-green) 100%);
    }
    .cci-chatbot-head-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }
    .cci-chatbot-head-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }
    .cci-chatbot-head-logo {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.35);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .cci-chatbot-head-text strong {
        display: block;
        font-size: 17px;
        font-weight: 700;
        letter-spacing: .02em;
        line-height: 1.2;
    }
    .cci-chatbot-head-tagline {
        font-size: 12px;
        opacity: .92;
        font-weight: 500;
        line-height: 1.35;
        margin-top: 4px;
        max-width: 260px;
    }
    .cci-chatbot-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        background: rgba(255,255,255,.2);
        border: 1px solid rgba(255,255,255,.28);
    }
    .cci-chatbot-badge-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #8bc34a;
        box-shadow: 0 0 0 2px rgba(139, 195, 74, .35);
        animation: cciPulse 2s ease-in-out infinite;
    }
    @keyframes cciPulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .75; transform: scale(.92); }
    }
    #cci-chatbot-close {
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.28);
        color: #fff;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 22px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background .15s ease, transform .15s ease;
        flex-shrink: 0;
    }
    #cci-chatbot-close:hover {
        background: rgba(255,255,255,.3);
        transform: rotate(90deg);
    }

    #cci-chatbot-root #cci-chatbot-window {
        flex: 1 1 auto;
        min-height: 0;
        width: 100%;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 16px 14px 12px;
        margin-top: 0;
        background: linear-gradient(180deg, var(--cci-blue-ghost) 0%, #e8f2fa 55%, #dfeef8 100%);
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        position: relative !important;
        top: auto !important;
        right: auto !important;
        bottom: auto !important;
        left: auto !important;
    }
    #cci-chatbot-window::-webkit-scrollbar { width: 8px; }
    #cci-chatbot-window::-webkit-scrollbar-track { background: rgba(19, 121, 186, .06); border-radius: 8px; }
    #cci-chatbot-window::-webkit-scrollbar-thumb {
        background: rgba(19, 121, 186, .28);
        border-radius: 8px;
    }
    #cci-chatbot-window::-webkit-scrollbar-thumb:hover { background: rgba(19, 121, 186, .42); }

    #cci-chatbot-messages { padding-bottom: 4px; }

    .cci-chatbot-msg {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 14px;
        animation: cciMsgIn .32s ease;
    }
    @keyframes cciMsgIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .cci-chatbot-msg--bot { justify-content: flex-start; }
    .cci-chatbot-msg--user {
        justify-content: flex-end;
        flex-direction: row-reverse;
        align-items: flex-end;
    }

    .cci-chatbot-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        color: #fff;
        background: linear-gradient(145deg, var(--cci-blue) 0%, var(--cci-blue-mid) 100%);
        border: 2px solid #fff;
        box-shadow: 0 2px 8px rgba(19, 121, 186, .25);
    }
    .cci-chatbot-msg--user .cci-chatbot-avatar {
        background: linear-gradient(145deg, var(--cci-green) 0%, var(--cci-green-dark) 100%);
        font-size: 13px;
        font-weight: 700;
    }

    .cci-chatbot-bubble {
        max-width: calc(100% - 46px);
        padding: 12px 14px;
        border-radius: 14px;
        font-size: 14px;
        line-height: 1.5;
        color: var(--cci-text);
        box-shadow: 0 2px 10px rgba(26, 46, 68, .06);
    }
    .cci-chatbot-msg--bot .cci-chatbot-bubble {
        background: #fff;
        border: 1px solid rgba(197, 220, 232, .85);
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 12px rgba(19, 121, 186, .08);
        position: relative;
        z-index: 1;
    }
    .cci-chatbot-msg--user .cci-chatbot-bubble {
        max-width: 88%;
        background: linear-gradient(135deg, var(--cci-blue) 0%, var(--cci-blue-mid) 100%);
        color: #fff;
        border: none;
        border-bottom-right-radius: 4px;
        box-shadow: 0 4px 14px rgba(19, 121, 186, .35);
    }
    .cci-chatbot-msg--user .cci-chatbot-bubble a { color: #e3f2fd; text-decoration: underline; }

    .cci-chatbot-bubble p { margin: 0 0 10px; }
    .cci-chatbot-bubble p:last-child { margin-bottom: 0; }
    .cci-chatbot-bubble a { color: var(--cci-blue); font-weight: 600; }
    .cci-chatbot-bubble strong { color: inherit; }

    .cci-chatbot-welcome-label {
        display: inline-block;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--cci-blue);
        background: var(--cci-blue-soft);
        padding: 4px 10px;
        border-radius: 6px;
        margin-bottom: 10px;
    }

    .cci-chatbot-tip-list {
        margin: 10px 0 0;
        padding-left: 20px;
        color: var(--cci-text);
    }
    .cci-chatbot-tip-list li { margin-bottom: 10px; padding-left: 2px; }
    .cci-chatbot-tip-list li::marker { color: var(--cci-blue); font-weight: 700; }

    #cci-chatbot-root .cci-chatbot-tip-issue-hint {
        margin: 14px 0 0;
        font-size: 12px;
        line-height: 1.45;
    }
    #cci-chatbot-root .cci-chatbot-tip-issue-label {
        display: block;
        font-size: 11px;
        font-weight: 800;
        color: var(--cci-blue-dark);
        margin: 10px 0 6px;
        text-transform: uppercase;
        letter-spacing: .06em;
    }
    #cci-chatbot-root .cci-chatbot-tip-issue {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        border: 1px solid var(--cci-line);
        border-radius: var(--cci-radius-sm);
        padding: 10px 12px;
        font-size: 14px;
        line-height: 1.45;
        color: var(--cci-text);
        background: #fafdff;
        min-height: 72px;
        resize: vertical;
        font-family: inherit;
    }
    #cci-chatbot-root .cci-chatbot-tip-issue:focus {
        outline: none;
        border-color: var(--cci-blue);
        box-shadow: 0 0 0 3px rgba(19, 121, 186, .18);
    }
    #cci-chatbot-root .cci-chatbot-tip-resolve-q {
        margin: 14px 0 0 !important;
    }

    .cci-chatbot-call-block {
        margin-top: 6px;
        padding: 14px 16px;
        border-radius: var(--cci-radius-sm);
        background: linear-gradient(135deg, var(--cci-blue-soft) 0%, #fff 100%);
        border: 1px solid var(--cci-line);
        text-align: center;
    }
    .cci-chatbot-call-block a {
        font-size: 20px;
        font-weight: 800;
        color: var(--cci-blue-dark);
        text-decoration: none;
    }
    .cci-chatbot-call-block a:hover { text-decoration: underline; }

    .cci-chatbot-alert { margin: 0; font-size: 14px; line-height: 1.45; }
    .cci-chatbot-alert--error { color: #a82323; }
    .cci-chatbot-alert--success { color: #2e6b1f; }

    #cci-chatbot-root #cci-chatbot-actions {
        flex: 0 1 auto;
        min-height: 0;
        max-height: min(42vh, 340px);
        overflow-y: auto;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
        padding: 12px 14px 14px;
        background: #fff;
        border-top: none;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-content: flex-start;
        position: relative !important;
        top: auto !important;
        right: auto !important;
        bottom: auto !important;
        left: auto !important;
    }
    #cci-chatbot-actions::-webkit-scrollbar { width: 6px; }
    #cci-chatbot-actions::-webkit-scrollbar-thumb {
        background: rgba(19, 121, 186, .25);
        border-radius: 6px;
    }

    .cci-chatbot-btn {
        flex: 1 1 calc(50% - 10px);
        min-width: 138px;
        border: 1px solid var(--cci-line);
        border-radius: var(--cci-radius-sm);
        padding: 11px 12px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        background: #fff;
        color: var(--cci-text);
        text-align: center;
        transition: background .16s ease, border-color .16s ease, color .16s ease, transform .12s ease, box-shadow .16s ease;
        box-shadow: 0 1px 3px rgba(26, 46, 68, .04);
    }
    .cci-chatbot-btn .fa {
        margin-right: 7px;
        opacity: .92;
    }
    .cci-chatbot-btn:hover:not(.cci-chatbot-btn--ok):not(.cci-chatbot-btn--primary):not(.cci-chatbot-btn--warn):not(.cci-chatbot-btn--muted) {
        background: var(--cci-blue-soft);
        border-color: rgba(19, 121, 186, .45);
        color: var(--cci-blue-dark);
        box-shadow: 0 2px 8px rgba(19, 121, 186, .1);
    }
    .cci-chatbot-btn:active { transform: scale(.98); }

    .cci-chatbot-btn--primary {
        background: linear-gradient(135deg, var(--cci-blue) 0%, var(--cci-blue-mid) 100%);
        color: #fff !important;
        border-color: transparent;
        box-shadow: 0 3px 12px rgba(19, 121, 186, .35);
    }
    .cci-chatbot-btn--primary .fa {
        color: inherit;
        opacity: 1;
    }
    .cci-chatbot-btn--primary:hover {
        background: linear-gradient(135deg, var(--cci-blue-mid) 0%, var(--cci-blue-bright) 100%);
        color: #fff !important;
        border-color: transparent;
        box-shadow: 0 5px 18px rgba(19, 121, 186, .42);
    }

    .cci-chatbot-btn--ok {
        background: linear-gradient(135deg, var(--cci-green) 0%, var(--cci-green-dark) 100%);
        color: #fff !important;
        border-color: transparent;
        box-shadow: 0 3px 12px rgba(107, 165, 70, .35);
    }
    .cci-chatbot-btn--ok:hover {
        filter: brightness(1.06);
        color: #fff !important;
        border-color: transparent;
        box-shadow: 0 5px 18px rgba(107, 165, 70, .4);
        background: linear-gradient(135deg, var(--cci-green) 0%, var(--cci-green-dark) 100%) !important;
    }

    /* Strong contrast: generic .btn:hover was washing out the green "Yes" button */
    #cci-chatbot-root #cci-chatbot-actions .cci-chatbot-btn--ok {
        background: linear-gradient(135deg, var(--cci-green) 0%, var(--cci-green-dark) 100%) !important;
        color: #fff !important;
        border: 2px solid rgba(74, 122, 48, .85) !important;
        text-shadow: 0 1px 1px rgba(0, 0, 0, .18);
    }
    #cci-chatbot-root #cci-chatbot-actions .cci-chatbot-btn--ok .fa {
        color: #fff !important;
    }

    .cci-chatbot-btn--outline {
        background: #fff;
        color: var(--cci-blue-dark);
        border: 2px solid rgba(19, 121, 186, .35);
    }
    .cci-chatbot-btn--outline:hover {
        background: var(--cci-blue-soft);
        border-color: var(--cci-blue);
    }

    .cci-chatbot-btn--muted {
        flex-basis: 100%;
        background: #f4f7f9;
        color: var(--cci-muted);
        border-color: #dde5eb;
    }
    .cci-chatbot-btn--muted:hover {
        background: #e8eef3;
        color: var(--cci-text);
    }

    .cci-chatbot-btn--warn {
        background: linear-gradient(180deg, #fff8f8 0%, #fff 100%);
        color: #9b2c2c;
        border: 2px solid rgba(231, 76, 60, .4);
    }
    .cci-chatbot-btn--warn:hover {
        background: #fff0f0;
        border-color: #e74c3c;
        color: #7a1f1f;
    }

    .cci-chatbot-form { width: 100%; flex-basis: 100%; }
    .cci-chatbot-form-hint {
        font-size: 12px;
        color: var(--cci-muted);
        margin: 0 0 10px;
        line-height: 1.45;
    }
    .cci-chatbot-form label {
        display: block;
        font-size: 11px;
        font-weight: 800;
        color: var(--cci-blue-dark);
        margin: 12px 0 6px;
        text-transform: uppercase;
        letter-spacing: .06em;
    }
    .cci-chatbot-form label:first-of-type { margin-top: 4px; }
    .cci-chatbot-form input,
    .cci-chatbot-form textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid var(--cci-line);
        border-radius: var(--cci-radius-sm);
        padding: 10px 12px;
        font-size: 14px;
        color: var(--cci-text);
        background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .cci-chatbot-form textarea {
        min-height: 88px;
        resize: vertical;
        max-height: 200px;
    }
    .cci-chatbot-form input::placeholder,
    .cci-chatbot-form textarea::placeholder { color: #94a3b8; }
    .cci-chatbot-form input:focus,
    .cci-chatbot-form textarea:focus {
        outline: none;
        border-color: var(--cci-blue);
        box-shadow: 0 0 0 3px rgba(19, 121, 186, .18);
    }

    .cci-chatbot-actions-title {
        flex-basis: 100%;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--cci-blue-dark);
        margin: 4px 0 0;
        padding-top: 4px;
        border-top: 1px dashed var(--cci-line);
    }
    .cci-chatbot-actions-title:first-child { border-top: none; padding-top: 0; margin-top: 0; }

    .cci-chatbot-intake-hint {
        flex-basis: 100%;
        width: 100%;
        margin-top: 0 !important;
        margin-bottom: 8px !important;
    }
    #cci-chatbot-root .cci-chatbot-dept.is-selected {
        border: 2px solid var(--cci-blue);
        background: var(--cci-blue-soft);
        color: var(--cci-blue-dark);
        box-shadow: 0 2px 10px rgba(19, 121, 186, .15);
    }
    #cci-chatbot-root .cci-chatbot-submit-esc {
        flex-basis: 100%;
        width: 100%;
        margin-top: 6px;
        padding: 13px 16px;
        font-size: 15px;
        font-weight: 700;
    }

    /* Intake form scrolls inside chat (not fixed dock) — one column, no overlap with messages */
    #cci-chatbot-intake-active {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-content: flex-start;
        width: 100%;
        box-sizing: border-box;
    }
    #cci-chatbot-intake-active .cci-chatbot-actions-title,
    #cci-chatbot-intake-active .cci-chatbot-intake-hint,
    #cci-chatbot-intake-active .cci-chatbot-form {
        flex-basis: 100%;
        width: 100%;
    }
    #cci-chatbot-intake-active .cci-chatbot-submit-esc {
        flex-basis: 100%;
        width: 100%;
        margin-top: 4px;
    }
    #cci-chatbot-root .cci-chatbot-bubble--intake {
        max-width: calc(100% - 46px);
    }

    #cci-chatbot-root .cci-chatbot-foot {
        flex-shrink: 0;
        padding: 10px 14px max(12px, env(safe-area-inset-bottom));
        font-size: 12px;
        color: var(--cci-muted);
        text-align: center;
        background: linear-gradient(180deg, #fafcfe 0%, #f0f6fa 100%);
        border-top: 1px solid var(--cci-line);
    }
    .cci-chatbot-foot a { color: var(--cci-blue); font-weight: 700; text-decoration: none; }
    .cci-chatbot-foot a:hover { text-decoration: underline; color: var(--cci-blue-dark); }
    #cci-chatbot-restart {
        background: none;
        border: none;
        color: var(--cci-blue);
        cursor: pointer;
        font-size: 12px;
        font-weight: 700;
        padding: 0 4px;
        text-decoration: none;
        border-bottom: 1px solid transparent;
        transition: border-color .15s ease, color .15s ease;
    }
    #cci-chatbot-restart:hover {
        color: var(--cci-green-dark);
        border-bottom-color: var(--cci-green);
    }

    @media (max-width: 480px) {
        #cci-chatbot-panel {
            left: max(10px, env(safe-area-inset-left));
            right: max(10px, env(safe-area-inset-right));
            bottom: calc(84px + env(safe-area-inset-bottom));
            max-width: none;
            max-height: calc(100vh - 140px);
        }
        #cci-chatbot-root #cci-chatbot-panel.is-open {
            height: calc(100vh - 140px);
            max-height: calc(100vh - 140px);
        }
        #cci-chatbot-fab {
            right: max(12px, env(safe-area-inset-right));
            bottom: max(16px, env(safe-area-inset-bottom));
        }
        #cci-chatbot-root #cci-chatbot-actions {
            max-height: min(48vh, 400px);
        }
    }
</style>

<div id="cci-chatbot-root" aria-live="polite">
    <button type="button" id="cci-chatbot-fab" aria-label="Open support chat" aria-expanded="false" title="Chat with support">
        <i class="fa fa-comments" aria-hidden="true"></i>
    </button>
    <div id="cci-chatbot-panel" role="dialog" aria-labelledby="cci-chatbot-title" aria-modal="true">
        <div class="cci-chatbot-head">
            <div class="cci-chatbot-head-row">
                <div class="cci-chatbot-head-brand">
                    <div class="cci-chatbot-head-logo" aria-hidden="true">
                        <i class="fa fa-life-ring"></i>
                    </div>
                    <div class="cci-chatbot-head-text">
                        <strong id="cci-chatbot-title">Support</strong>
                        <div class="cci-chatbot-head-tagline">We troubleshoot first, then route your request to the right team.</div>
                        <div class="cci-chatbot-badge"><span class="cci-chatbot-badge-dot" aria-hidden="true"></span> Online</div>
                    </div>
                </div>
                <button type="button" id="cci-chatbot-close" aria-label="Close chat">&times;</button>
            </div>
        </div>
        <div id="cci-chatbot-scroll-wrap">
            <div id="cci-chatbot-window">
                <div id="cci-chatbot-messages"></div>
            </div>
        </div>
        <div id="cci-chatbot-panel-dock">
            <div id="cci-chatbot-actions"></div>
            <div class="cci-chatbot-foot">
                <button type="button" id="cci-chatbot-restart">Start over</button>
                <span aria-hidden="true"> · </span>
                Toll free <a href="tel:+18889003382">1-888-900-3382</a>
            </div>
        </div>
    </div>
</div>

@if(Route::has('chatbot.escalate') && Route::has('chatbot.qa.json'))
<script>
    window.CCI_CHATBOT = window.CCI_CHATBOT || {};
    window.CCI_CHATBOT.escalateUrl = @json(route('chatbot.escalate'));
    window.CCI_CHATBOT.qaJsonUrl = @json(route('chatbot.qa.json'));
</script>
@else
{{-- Routes missing: site stays up; add chatbot routes from MERGE/DEPLOY-README then clear view cache. JS falls back to /chatbot/escalate. --}}
<script>
    window.CCI_CHATBOT = window.CCI_CHATBOT || {};
    window.CCI_CHATBOT.escalateUrl = @json(url('/chatbot/escalate'));
    window.CCI_CHATBOT.qaJsonUrl = @json(url('/chatbot/qa.json'));
</script>
@endif
<script src="{{ url('webAssets/js/cci-chatbot.js') }}?v={{ $cciChatV }}"></script>
