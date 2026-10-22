{{-- ═══════════════════════════════════════════
     CURRENCY CONVERTER — Sticky Right Side Widget
     ═══════════════════════════════════════════ --}}

<style>
    /* ── Sticky wrapper ── */
    #curr-widget {
        position: fixed;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        z-index: 99998;
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    /* ── Tab trigger (always visible on right edge) ── */
    #curr-tab {
        writing-mode: vertical-rl;
        text-orientation: mixed;
        transform: rotate(180deg);
        background: linear-gradient(180deg, #1b5e20 0%, #2e7d32 50%, #388e3c 100%);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        padding: 16px 8px;
        border-radius: 10px 0 0 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: -3px 0 16px rgba(0,0,0,0.18);
        user-select: none;
        transition: background 0.2s, padding 0.2s;
        min-height: 110px;
    }
    #curr-tab:hover {
        background: linear-gradient(180deg, #2e7d32 0%, #43a047 100%);
        padding: 16px 10px;
    }
    #curr-tab .ct-icon {
        font-size: 14px;
        writing-mode: horizontal-tb;
        transform: rotate(90deg);
        display: block;
        line-height: 1;
    }

    /* ── Panel ── */
    #curr-panel {
        background: #fff;
        border-radius: 14px 0 0 14px;
        box-shadow: -6px 0 30px rgba(0,0,0,0.15);
        width: 0;
        overflow: hidden;
        transition: width 0.3s cubic-bezier(.4,0,.2,1);
        border: none;
    }
    #curr-widget.open #curr-panel { width: 230px; }

    .curr-panel-inner { width: 230px; padding: 0; }

    /* Panel header */
    .curr-panel-head {
        background: linear-gradient(135deg, #1b5e20, #43a047);
        color: #fff;
        padding: 13px 16px 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .curr-panel-head h6 {
        margin: 0;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .curr-panel-head small {
        font-size: 10px;
        opacity: 0.8;
        display: block;
        margin-top: 2px;
    }
    .curr-close-btn {
        background: rgba(255,255,255,0.2);
        border: none;
        color: #fff;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        cursor: pointer;
        font-size: 14px;
        line-height: 24px;
        text-align: center;
        transition: background 0.2s;
        padding: 0;
        flex-shrink: 0;
    }
    .curr-close-btn:hover { background: rgba(255,255,255,0.35); }

    /* Rate ticker */
    .curr-ticker {
        background: #f1f8e9;
        padding: 7px 14px;
        font-size: 10.5px;
        color: #33691e;
        font-weight: 600;
        border-bottom: 1px solid #dcedc8;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .curr-ticker span { color: #1b5e20; font-weight: 700; }

    /* Currency options */
    .curr-option {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 16px;
        cursor: pointer;
        border-bottom: 1px solid #f5f5f5;
        transition: background 0.15s;
    }
    .curr-option:last-child { border-bottom: none; }
    .curr-option:hover { background: #f1f8e9; }
    .curr-option.active { background: #e8f5e9; }

    .curr-option .co-badge {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #a5d6a7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        font-weight: 800;
        color: #2e7d32;
        flex-shrink: 0;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }
    .curr-option.active .co-badge {
        background: #2e7d32;
        color: #fff;
        border-color: #2e7d32;
    }
    .curr-option .co-info { flex: 1; }
    .curr-option .co-name {
        font-size: 13px;
        font-weight: 600;
        color: #222;
        display: block;
        line-height: 1.3;
    }
    .curr-option.active .co-name { color: #1b5e20; }
    .curr-option .co-rate {
        font-size: 10px;
        color: #888;
        font-weight: 500;
    }
    .curr-option .co-check {
        color: #2e7d32;
        font-size: 15px;
        font-weight: 700;
        display: none;
    }
    .curr-option.active .co-check { display: block; }

    @media (max-width: 768px) {
        #curr-widget { top: auto; bottom: 90px; transform: none; }
        #curr-widget.open #curr-panel { width: 200px; }
        .curr-panel-inner { width: 200px; }
    }
</style>

{{-- Widget HTML --}}
<div id="curr-widget">
    {{-- Slide-out panel --}}
    <div id="curr-panel">
        <div class="curr-panel-inner">
            <div class="curr-panel-head">
                <div>
                    <h6><i class="fa fa-exchange"></i> Currency</h6>
                    <small id="curr-rate-info">Loading live rates...</small>
                </div>
                <button class="curr-close-btn" onclick="closeCurrWidget()">✕</button>
            </div>
            <div class="curr-ticker" id="currTicker">Fetching exchange rates...</div>
            <div>
                <div class="curr-option active" data-curr="USD" data-symbol="$" data-label="USD" onclick="switchCurr(this)">
                    <div class="co-badge">$</div>
                    <div class="co-info">
                        <span class="co-name">US Dollar</span>
                        <span class="co-rate" id="rate-USD">1 USD = 1.00 USD</span>
                    </div>
                    <span class="co-check">✔</span>
                </div>
                <div class="curr-option" data-curr="EUR" data-symbol="€" data-label="EUR" onclick="switchCurr(this)">
                    <div class="co-badge">€</div>
                    <div class="co-info">
                        <span class="co-name">Euro</span>
                        <span class="co-rate" id="rate-EUR">—</span>
                    </div>
                    <span class="co-check">✔</span>
                </div>
                <div class="curr-option" data-curr="GBP" data-symbol="£" data-label="GBP" onclick="switchCurr(this)">
                    <div class="co-badge">£</div>
                    <div class="co-info">
                        <span class="co-name">British Pound</span>
                        <span class="co-rate" id="rate-GBP">—</span>
                    </div>
                    <span class="co-check">✔</span>
                </div>
                <div class="curr-option" data-curr="CAD" data-symbol="CA$" data-label="CAD" onclick="switchCurr(this)">
                    <div class="co-badge" style="font-size:11px;">CA$</div>
                    <div class="co-info">
                        <span class="co-name">Canadian Dollar</span>
                        <span class="co-rate" id="rate-CAD">—</span>
                    </div>
                    <span class="co-check">✔</span>
                </div>
                <div class="curr-option" data-curr="AUD" data-symbol="A$" data-label="AUD" onclick="switchCurr(this)">
                    <div class="co-badge" style="font-size:11px;">A$</div>
                    <div class="co-info">
                        <span class="co-name">Australian Dollar</span>
                        <span class="co-rate" id="rate-AUD">—</span>
                    </div>
                    <span class="co-check">✔</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab trigger --}}
    <div id="curr-tab" onclick="toggleCurrWidget()">
        <span class="ct-icon"><i class="fa fa-usd" style="writing-mode:horizontal-tb; transform:none;"></i></span>
        <span>CURRENCY</span>
    </div>
</div>

<script>
(function () {
    var FALLBACK   = { USD:1, EUR:0.92, GBP:0.79, CAD:1.36, AUD:1.55 };
    var currRates  = FALLBACK;
    var currSel    = localStorage.getItem('selectedCurr')    || 'USD';
    var currSym    = localStorage.getItem('selectedSymbol')  || '$';
    var currLbl    = localStorage.getItem('selectedLabel')   || 'USD';

    // ── Fetch live rates ──────────────────────────────────────────────
    fetch('https://api.frankfurter.app/latest?from=USD&to=EUR,GBP,CAD,AUD')
        .then(function(r){ return r.json(); })
        .then(function(data){
            if (data && data.rates) {
                currRates = Object.assign({ USD:1 }, data.rates);
                updateRateLabels();
                applyConversion(currSel, currSym);
                document.getElementById('curr-rate-info').textContent = 'Live rates · ' + (data.date || 'today');
                document.getElementById('currTicker').innerHTML =
                    '1 USD &rarr; <span>' + currRates.EUR.toFixed(4) + ' EUR</span> &middot; ' +
                    '<span>' + currRates.GBP.toFixed(4) + ' GBP</span> &middot; ' +
                    '<span>' + currRates.CAD.toFixed(4) + ' CAD</span> &middot; ' +
                    '<span>' + currRates.AUD.toFixed(4) + ' AUD</span>';
            }
        })
        .catch(function(){
            document.getElementById('curr-rate-info').textContent = 'Fallback rates (offline)';
            updateRateLabels();
            applyConversion(currSel, currSym);
        });

    function updateRateLabels() {
        var codes = ['USD','EUR','GBP','CAD','AUD'];
        var syms  = { USD:'$', EUR:'€', GBP:'£', CAD:'CA$', AUD:'A$' };
        codes.forEach(function(c){
            var el = document.getElementById('rate-' + c);
            if (el) el.textContent = '1 USD = ' + (currRates[c]||1).toFixed(4) + ' ' + c;
        });
    }

    // ── Apply conversion to all price elements ────────────────────────
    window.applyConversion = function(curr, symbol) {
        var rate = currRates[curr] || 1;
        document.querySelectorAll('.currency-price').forEach(function(el) {
            var raw = el.getAttribute('data-usd');
            var usd = parseFloat(String(raw == null ? '' : raw).replace(/,/g, ''));
            if (isNaN(usd)) {
                usd = 0;
            }
            var symEl = el.querySelector('.curr-symbol');
            var amtEl = el.querySelector('.curr-amount');
            if (symEl) symEl.textContent = symbol;
            if (amtEl) amtEl.textContent = (usd * rate).toFixed(2);
        });
    };

    // ── Toggle widget ─────────────────────────────────────────────────
    window.toggleCurrWidget = function() {
        document.getElementById('curr-widget').classList.toggle('open');
    };
    window.closeCurrWidget = function() {
        document.getElementById('curr-widget').classList.remove('open');
    };

    // ── Switch currency ───────────────────────────────────────────────
    window.switchCurr = function(el) {
        var curr   = el.getAttribute('data-curr');
        var symbol = el.getAttribute('data-symbol');
        var label  = el.getAttribute('data-label');

        localStorage.setItem('selectedCurr',   curr);
        localStorage.setItem('selectedSymbol', symbol);
        localStorage.setItem('selectedLabel',  label);

        document.querySelectorAll('.curr-option').forEach(function(i){ i.classList.remove('active'); });
        el.classList.add('active');

        currSel = curr; currSym = symbol; currLbl = label;
        applyConversion(curr, symbol);
    };

    // ── Restore saved currency on load ────────────────────────────────
    if (currSel !== 'USD') {
        var saved = document.querySelector('.curr-option[data-curr="' + currSel + '"]');
        if (saved) {
            document.querySelectorAll('.curr-option').forEach(function(i){ i.classList.remove('active'); });
            saved.classList.add('active');
        }
        applyConversion(currSel, currSym);
    }

})();
</script>
