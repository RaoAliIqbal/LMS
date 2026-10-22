/* ============================================================
   ProfessionalOnlineEducation — Chatbot v2
   ============================================================ */

var chatState = 'idle';
var escalationType = '';
var userData = { name:'', phone:'', email:'', issue:'' };
var lastUserQuestion = '';
var welcomeShown = false;

// ── Enter key ──────────────────────────────────────────────
$('#userInput').keypress(function(e){
    if(e.which == 13){ sendMessage(); }
});

// ── Send ───────────────────────────────────────────────────
function sendMessage(){
    var input = document.getElementById('userInput');
    var text  = input.value.trim();
    if(!text) return;
    addUserMessage(text);
    input.value = '';
    scrollChat();
    handleFlow(text);
}

// ── Flow ───────────────────────────────────────────────────
function handleFlow(text){
    if(chatState === 'collecting_name'){
        userData.name = text;
        chatState = 'collecting_phone';
        delayedBotMsg('Thanks <strong>' + esc(userData.name) + '</strong>! What is your <strong>phone number</strong>?');
        return;
    }
    if(chatState === 'collecting_phone'){
        userData.phone = text;
        chatState = 'collecting_email';
        delayedBotMsg('Got it! What is your <strong>email address</strong>?');
        return;
    }
    if(chatState === 'collecting_email'){
        userData.email = text;
        chatState = 'collecting_issue';
        delayedBotMsg('Almost done! Please briefly describe your <strong>issue or question</strong>:');
        return;
    }
    if(chatState === 'collecting_issue'){
        userData.issue = text;
        chatState = 'done';
        submitEscalation();
        return;
    }

    // Normal Q&A
    lastUserQuestion = text;
    var response = matchBotResponse(text);
    saveUserBotConversation(text, response);
    delayedBotMsg(response, true); // true = show help buttons after
}

// ── Keyword matching ───────────────────────────────────────
function matchBotResponse(input){
    var maxCount = 0;
    var matched  = null;
    for(var i = 0; i < botResponses.length - 1; i++){
        var r = botResponses[i]; var count = 0;
        for(var j = 0; j < r.keywords.length; j++){
            if(input.match(new RegExp('\\b' + r.keywords[j] + '\\b', 'i'))) count++;
        }
        if(count > maxCount){ maxCount = count; matched = r.response; }
    }
    return matched || botResponses[botResponses.length - 1].response;
}

// ── Delayed bot message ────────────────────────────────────
function delayedBotMsg(html, showHelp){
    showTyping();
    setTimeout(function(){
        hideTyping();
        addBotMessage(html);
        if(showHelp) showQuickHelp();
        scrollChat();
    }, 1000);
}

// ── Quick help buttons ─────────────────────────────────────
function showQuickHelp(){
    removeHelp();
    var chatbox = document.getElementById('chatbox');
    var wrap = document.createElement('div');
    wrap.id = 'cbHelpWrap';
    wrap.innerHTML =
        '<p class="cb-hint">Was this helpful?</p>' +
        '<a class="cb-opt cb-yes" href="javascript:void(0)" onclick="userSatisfied()">&#10004; Yes, thanks!</a>' +
        '<a class="cb-opt cb-no"  href="javascript:void(0)" onclick="showEscMenu()">&#10008; No, I need more help</a>';
    chatbox.appendChild(wrap);
    setTimeout(scrollChat, 50);
}

function removeHelp(){
    ['cbHelpWrap','cbEscMenu'].forEach(function(id){
        var el = document.getElementById(id); if(el) el.remove();
    });
}

function userSatisfied(){
    removeHelp();
    delayedBotMsg('Glad I could help! &#128522; Feel free to ask anything else anytime.');
}

// ── Escalation menu ────────────────────────────────────────
function showEscMenu(){
    removeHelp();
    var chatbox = document.getElementById('chatbox');
    var menu = document.createElement('div');
    menu.id = 'cbEscMenu';
    menu.innerHTML =
        '<p class="cb-hint"><strong>How can we help you further?</strong></p>' +
        '<a class="cb-opt cb-it"   href="javascript:void(0)" onclick="startEsc(\'it\')">&#128231; Submit IT Support Request</a>' +
        '<a class="cb-opt cb-inst" href="javascript:void(0)" onclick="startEsc(\'instructor\')">&#128105;&#8205;&#127979; Talk to an Instructor</a>' +
        '<a class="cb-opt cb-call" href="javascript:void(0)" onclick="showPhone()">&#128222; Call Us</a>';
    chatbox.appendChild(menu);
    setTimeout(scrollChat, 50);
}

// ── Phone support ──────────────────────────────────────────
function showPhone(){
    removeHelp();
    delayedBotMsg(
        '<strong>&#128222; Phone Support</strong><br>' +
        '<a href="tel:18889003382" style="color:#0877b8;font-weight:bold;">1-888-900-3382</a><br>' +
        '<span style="font-size:12px;color:#777;">Monday &ndash; Friday, 8:00 AM &ndash; 5:00 PM</span>'
    );
}

// ── Start info collection ──────────────────────────────────
function startEsc(type){
    removeHelp();
    escalationType = type;
    userData = { name:'', phone:'', email:'', issue: lastUserQuestion };
    chatState = 'collecting_name';
    var intro = type === 'instructor'
        ? "I'll connect you with an instructor. A few quick details first."
        : "I'll send your request to our support team. Just a few quick questions.";
    delayedBotMsg(intro + '<br><br>What is your <strong>full name</strong>?');
}

// ── Submit to server ───────────────────────────────────────
function submitEscalation(){
    delayedBotMsg('&#9203; Sending your request...');
    setTimeout(function(){
        $.ajax({
            type:'POST', url: chatbotEscalationRoute,
            data:{
                _token: csrfToken,
                name:   userData.name,
                phone:  userData.phone,
                email:  userData.email,
                issue:  userData.issue,
                type:   escalationType
            },
            success:function(){
                // Remove the "sending" message
                var msgs = document.querySelectorAll('#chatbox p.ResponseP');
                if(msgs.length) msgs[msgs.length-1].remove();
                var msg = escalationType === 'instructor'
                    ? '&#9989; Your request has been sent to our instructor. We will contact you at <strong>' + esc(userData.email) + '</strong> shortly.'
                    : '&#9989; IT support request submitted! Please allow <strong>24&ndash;48 hours</strong> for a response to <strong>' + esc(userData.email) + '</strong>.';
                addBotMessage(msg);
                addBotMessage('Is there anything else I can help you with?');
                chatState = 'idle';
                showQuickHelp();
                scrollChat();
            },
            error:function(){
                addBotMessage('Sorry, there was a problem. Please call <a href="tel:18889003382" style="color:#0877b8;">1-888-900-3382</a>.');
                chatState = 'idle';
            }
        });
    }, 1200);
}

// ── UI helpers ─────────────────────────────────────────────
function showTyping(){
    var chatbox = document.getElementById('chatbox');
    var el = document.createElement('p');
    el.id = 'cbTyping';
    el.innerHTML = '<strong class="bot">Bot:</strong> <em style="color:#aaa;">typing&hellip;</em>';
    chatbox.appendChild(el);
    scrollChat();
}
function hideTyping(){ var el=document.getElementById('cbTyping'); if(el) el.remove(); }

function addUserMessage(msg){
    var el = document.createElement('p');
    el.className = 'MymsgP';
    el.innerHTML = '<strong class="myMsg">You:</strong> ' + esc(msg);
    document.getElementById('chatbox').appendChild(el);
}

function addBotMessage(html){
    var el = document.createElement('p');
    el.className = 'ResponseP';
    el.innerHTML = '<strong class="bot">Bot:</strong> ' + html;
    document.getElementById('chatbox').appendChild(el);
}

function scrollChat(){
    var c = document.getElementById('chatbox');
    if(c) c.scrollTop = c.scrollHeight;
}

function esc(t){
    return String(t).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Open / close ───────────────────────────────────────────
$('#hideChatContainer, #hideChat').click(function(){
    $('#chatboxContainer').hide();
});

$('#chatBtn_block, .chat').click(function(){
    $('#chatboxContainer').show();
    if(!welcomeShown){
        welcomeShown = true;
        showTyping();
        setTimeout(function(){
            hideTyping();
            addBotMessage(
                'Hello! &#128075; Welcome to <strong>Professional Online Education</strong>.<br>' +
                'How can I help you today? Ask me about courses, certificates, payments, and more!'
            );
            showQuickHelp();
            scrollChat();
        }, 800);
    }
});

// ── Save conversation ──────────────────────────────────────
function saveUserBotConversation(q, a){
    $.ajax({ type:'GET', url: save_user_bot_conversation_route, data:{ question:q, answer:a } });
}
