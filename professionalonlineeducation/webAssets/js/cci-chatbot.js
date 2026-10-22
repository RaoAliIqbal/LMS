/**
 * CCI website support chatbot — troubleshooting first, then escalation + intake.
 * Depends on jQuery + meta[name="csrf-token"].
 */
(function ($) {
  'use strict';

  var cfg = window.CCI_CHATBOT || {};
  var escalateUrl = cfg.escalateUrl || '/chatbot/escalate';
  var qaJsonUrl = cfg.qaJsonUrl || '';

  var topics = {
    login: {
      title: 'Login / LMS access',
      tips: [
        'Try a <strong>private or incognito</strong> window and log in again — this clears stuck cookies.',
        'Confirm you are on the correct site and use <strong>Forgot password</strong> if you are unsure of your password.',
        'Disable browser extensions (ad blockers) temporarily — they sometimes block the LMS login page.',
      ],
    },
    billing: {
      title: 'Billing / payment / receipt',
      tips: [
        'Check your <strong>email spam folder</strong> for receipts or course access messages.',
        'If a card was declined, try the same card again or another card; your bank may show a pending hold that drops off.',
        'Note the <strong>last 4 digits</strong> of the card used and approximate time of purchase if you need us to trace a charge.',
      ],
    },
    course: {
      title: 'Course content / instructor / grades',
      tips: [
        'Open the course from your <strong>dashboard</strong> and mark each chapter/lesson complete in order where required.',
        'Allow videos to finish; some courses require the player to reach the end before the next section unlocks.',
        'If something looks incorrect, jot down the <strong>lesson name</strong> and what you expected — it speeds up instructor review.',
      ],
    },
    certificate: {
      title: 'Certificate / CE reporting',
      tips: [
        'Certificates usually appear in your account after <strong>all course requirements</strong> are complete.',
        'Download the PDF and keep a copy; you can re-download later from your dashboard when available.',
        'For state board reporting questions, have your <strong>license number and state</strong> ready when you reach out.',
      ],
    },
    other: {
      title: 'Something else',
      tips: [
        'Write a <strong>short description</strong> of what happened and any error message (exact wording helps).',
        'Try another browser or device once — this tells us if it is account-specific or device-specific.',
        'If the site will not load, check your connection and try again in a few minutes in case of brief maintenance.',
      ],
    },
  };

  function escapeHtml(s) {
    if (!s) return '';
    return String(s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function scrollBottom() {
    var $w = $('#cci-chatbot-window');
    if ($w.length) {
      $w.scrollTop($w.prop('scrollHeight'));
    }
  }

  function appendBot(html) {
    $('#cci-chatbot-messages').append(
      '<div class="cci-chatbot-msg cci-chatbot-msg--bot">' +
        '<span class="cci-chatbot-avatar" aria-hidden="true"><i class="fa fa-graduation-cap"></i></span>' +
        '<div class="cci-chatbot-bubble">' +
        html +
        '</div></div>'
    );
    scrollBottom();
  }

  function appendUser(text) {
    $('#cci-chatbot-messages').append(
      '<div class="cci-chatbot-msg cci-chatbot-msg--user">' +
        '<div class="cci-chatbot-bubble">' +
        escapeHtml(text) +
        '</div>' +
        '<span class="cci-chatbot-avatar" aria-hidden="true"><i class="fa fa-user"></i></span>' +
        '</div>'
    );
    scrollBottom();
  }

  /** Full intake (team + form + send) lives in the scroll area — avoids dock overlapping chat. */
  function appendIntakeCard(innerHtml) {
    $('#cci-chatbot-messages').append(
      '<div class="cci-chatbot-msg cci-chatbot-msg--bot cci-chatbot-msg--intake">' +
        '<span class="cci-chatbot-avatar" aria-hidden="true"><i class="fa fa-graduation-cap"></i></span>' +
        '<div class="cci-chatbot-bubble cci-chatbot-bubble--intake">' +
        '<div id="cci-chatbot-intake-active">' +
        innerHtml +
        '</div></div></div>'
    );
    scrollBottom();
  }

  function clearActions() {
    $('#cci-chatbot-actions').empty();
  }

  function actions(html) {
    clearActions();
    $('#cci-chatbot-actions').html(html);
  }

  function extraTipsForTopic(topicKey) {
    var qa = window.CCI_CHATBOT_QA;
    if (!qa || !qa.tips) {
      return [];
    }
    var arr = qa.tips[topicKey];
    return Array.isArray(arr) ? arr : [];
  }

  function stateWelcome() {
    appendBot(
      '<span class="cci-chatbot-welcome-label">Professional Online Education</span>' +
        '<p><strong>Hi — I am your support assistant.</strong></p>' +
        '<p>I will walk you through quick fixes first, then connect you with <strong>IT</strong>, an <strong>instructor</strong>, or <strong>customer service</strong> if needed.</p>' +
        '<p>What best describes your issue?</p>'
    );
    var btns =
      '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--outline" data-topic="login">Login / LMS access</button>' +
      '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--outline" data-topic="billing">Billing / payment</button>' +
      '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--outline" data-topic="course">Course / instructor</button>' +
      '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--outline" data-topic="certificate">Certificate / CE</button>' +
      '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--outline" data-topic="other">Something else</button>';
    actions(btns);
  }

  /** "Something else" — no canned tips; clean thread, user types query, then contact form for team email. */
  function stateOtherDirect() {
    $('#cci-chatbot-messages').empty();
    $('#cci-chatbot-actions').removeData('topicKey');
    appendUser('Something else');
    appendBot(
      '<p><strong>Tell us what you need</strong></p>' +
        '<p class="cci-chatbot-form-hint cci-chatbot-tip-issue-hint" style="margin-top:0;">Write your question or problem below (no bot scripts on this path). Next, we will ask for your <strong>name</strong>, <strong>email</strong>, and <strong>course name</strong> so our team can reply — usually within <strong>24 hours</strong>.</p>' +
        '<label class="cci-chatbot-tip-issue-label" for="cci-other-query">Your message</label>' +
        '<textarea id="cci-other-query" class="cci-chatbot-tip-issue" rows="5" maxlength="8000" placeholder="Type your full question or describe the issue…"></textarea>'
    );
    actions(
      '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--primary" id="cci-other-continue"><i class="fa fa-arrow-right"></i> Continue to contact form</button>' +
        '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--outline" id="cci-other-back"><i class="fa fa-arrow-left"></i> Back to menu</button>'
    );
    $('#cci-chatbot-actions').scrollTop(0);
    scrollBottom();
  }

  /** "Something else" path — extra step: pick a department before showing the full intake form. */
  function stateOtherDeptPick(preIssue) {
    if (typeof preIssue === 'string' && preIssue !== '') {
      $('#cci-chatbot-messages').data('preIssue', preIssue);
    }
    $('#cci-chatbot-intake-active').closest('.cci-chatbot-msg').remove();
    appendBot(
      '<p><strong>Which team should we route this to?</strong></p>' +
        '<p class="cci-chatbot-form-hint" style="margin-top:4px;">Pick the team that best matches your question, then tap <strong>Next</strong> to see the contact form.</p>'
    );
    var pickInner =
      '<p class="cci-chatbot-actions-title">Who should we notify?</p>' +
      '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--outline cci-chatbot-dept" data-esc-dept="it"><i class="fa fa-desktop"></i> IT Support</button>' +
      '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--outline cci-chatbot-dept" data-esc-dept="instructor"><i class="fa fa-mortar-board"></i> Instructor</button>' +
      '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--outline cci-chatbot-dept" data-esc-dept="customer_service"><i class="fa fa-headphones"></i> Customer Service</button>' +
      '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--muted" data-esc-dept="call"><i class="fa fa-phone"></i> Call us instead</button>' +
      '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--primary" id="cci-other-dept-next"><i class="fa fa-arrow-right"></i> Next</button>';
    appendIntakeCard(pickInner);
    clearActions();
    $('#cci-chatbot-actions').removeData('escDept');
  }

  function stateTips(topicKey) {
    var t = topics[topicKey] || topics.other;
    var extras = extraTipsForTopic(topicKey);
    var tipsHtml = '<ol class="cci-chatbot-tip-list">';
    var i;
    for (i = 0; i < t.tips.length; i++) {
      tipsHtml += '<li>' + t.tips[i] + '</li>';
    }
    for (i = 0; i < extras.length; i++) {
      tipsHtml += '<li>' + extras[i] + '</li>';
    }
    tipsHtml += '</ol>';
    var issueIntro =
      '<p class="cci-chatbot-form-hint cci-chatbot-tip-issue-hint"><strong>Optional:</strong> describe your issue below — it helps if you still need to reach the team.</p>' +
      '<label class="cci-chatbot-tip-issue-label" for="cci-tip-desc">Your issue in your own words (optional)</label>' +
      '<textarea id="cci-tip-desc" class="cci-chatbot-tip-issue" rows="2" maxlength="8000" placeholder="Optional short note"></textarea>';
    appendBot(
      '<p><strong>' +
        escapeHtml(t.title) +
        '</strong> — please try these steps first:</p>' +
        tipsHtml +
        issueIntro +
        '<p class="cci-chatbot-tip-resolve-q"><strong>Did this resolve your issue?</strong></p>'
    );
    actions(
      '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--ok" data-resolve="yes"><i class="fa fa-check"></i> Yes, all set</button>' +
        '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--warn" data-resolve="no"><i class="fa fa-arrow-right"></i> Still need help</button>'
    );
    $('#cci-chatbot-actions').data('topicKey', topicKey);
  }

  function stateThanksClose() {
    appendBot(
      '<p class="cci-chatbot-alert cci-chatbot-alert--success"><strong>Glad we could help.</strong></p>' +
        '<p>If anything else comes up, open <strong>Support</strong> in the menu or use the chat button.</p>' +
        '<div class="cci-chatbot-call-block" style="margin-top:12px;">' +
        '<span style="font-size:11px;font-weight:800;letter-spacing:.08em;color:#5a6b7d;">TOLL FREE</span><br>' +
        '<a href="tel:+18889003382">1-888-900-3382</a></div>'
    );
    clearActions();
  }

  function stateIntake() {
    $('#cci-chatbot-intake-active').closest('.cci-chatbot-msg').remove();

    var fromOther = $('#cci-chatbot-messages').data('fromOtherDirect');
    if (fromOther) {
      $('#cci-chatbot-messages').removeData('fromOtherDirect');
      appendBot(
        '<p><strong>Thanks for your message.</strong></p>' +
          '<p class="cci-chatbot-form-hint" style="margin-top:4px;">Complete the form below so we can email you back. <strong>Course name</strong> is required for routing.</p>'
      );
    } else {
      appendBot(
        '<p><strong>No problem.</strong> Tell us a bit more so the right team can help.</p>' +
          '<p class="cci-chatbot-form-hint" style="margin-top:4px;"><strong>Course name</strong> is required so we can route instructor-related tickets correctly.</p>'
      );
    }
    var intakeInner =
      '<p class="cci-chatbot-actions-title">Who should we notify?</p>' +
      '<p class="cci-chatbot-form-hint cci-chatbot-intake-hint">Pick a team, fill the fields, then tap <strong>Send request</strong>. Scroll this panel to see everything.</p>' +
      '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--outline cci-chatbot-dept" data-esc-dept="it"><i class="fa fa-desktop"></i> IT Support</button>' +
      '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--outline cci-chatbot-dept" data-esc-dept="instructor"><i class="fa fa-mortar-board"></i> Instructor</button>' +
      '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--outline cci-chatbot-dept" data-esc-dept="customer_service"><i class="fa fa-headphones"></i> Customer Service</button>' +
      '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--muted" data-esc-dept="call"><i class="fa fa-phone"></i> Call us instead</button>' +
      '<div class="cci-chatbot-form">' +
      '<label>Full name *</label><input type="text" id="cci-f-name" maxlength="255" required autocomplete="name" />' +
      '<label>Email *</label><input type="email" id="cci-f-email" maxlength="255" required autocomplete="email" />' +
      '<label>Phone</label><input type="tel" id="cci-f-phone" maxlength="40" autocomplete="tel" />' +
      '<label>Course name *</label><input type="text" id="cci-f-course" maxlength="255" required placeholder="As shown on your enrollment" />' +
      '<label>Brief issue summary</label><textarea id="cci-f-issue" rows="3" maxlength="8000" placeholder="What is still wrong?"></textarea>' +
      '</div>' +
      '<button type="button" class="cci-chatbot-btn cci-chatbot-btn--primary cci-chatbot-submit-esc"><i class="fa fa-paper-plane"></i> Send request</button>';
    appendIntakeCard(intakeInner);
    clearActions();
    $('#cci-chatbot-actions').removeData('escDept');
    var preIssue = $('#cci-chatbot-messages').data('preIssue');
    if (typeof preIssue === 'string' && preIssue !== '') {
      $('#cci-chatbot-messages').removeData('preIssue');
      setTimeout(function () {
        var $ta = $('#cci-f-issue');
        if ($ta.length) {
          $ta.val(preIssue);
        }
      }, 0);
    }
  }

  function stateCallInfo() {
    appendBot(
      '<p>Our team is available by phone toll free:</p>' +
        '<div class="cci-chatbot-call-block">' +
        '<a href="tel:+18889003382">1-888-900-3382</a>' +
        '<p class="cci-chatbot-form-hint" style="margin-top:10px;margin-bottom:0;">If you still need a ticket after calling, open the chat again, pick a team, fill the form, and tap <strong>Send request</strong>.</p>' +
        '</div>'
    );
    clearActions();
  }

  function submitEscalation(type) {
    var name = $.trim($('#cci-f-name').val() || '');
    var email = $.trim($('#cci-f-email').val() || '');
    var phone = $.trim($('#cci-f-phone').val() || '');
    var course = $.trim($('#cci-f-course').val() || '');
    var issue = $.trim($('#cci-f-issue').val() || '');
    if (!name || !email || !course) {
      appendBot('<p class="cci-chatbot-alert cci-chatbot-alert--error">Please fill in <strong>full name</strong>, <strong>email</strong>, and <strong>course name</strong>, then tap <strong>Send request</strong> again.</p>');
      scrollBottom();
      return;
    }
    var $btns = $('#cci-chatbot-intake-active button');
    $btns.prop('disabled', true);
    var labels = { it: 'IT Support', instructor: 'Instructor Support', customer_service: 'Customer Service' };
    appendUser('Sending to ' + (labels[type] || type));
    var csrf = $('meta[name="csrf-token"]').attr('content') || '';
    $.ajax({
      url: escalateUrl,
      method: 'POST',
      dataType: 'json',
      headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrf,
      },
      data: {
        _token: csrf,
        name: name,
        email: email,
        phone: phone,
        course_name: course,
        issue: issue,
        type: type,
      },
      success: function (res) {
        if (res && res.ok) {
          if (res.staff_notified) {
            appendBot(
              '<p class="cci-chatbot-alert cci-chatbot-alert--success"><strong>Your email and query have been sent to our respective team.</strong></p>' +
                '<p class="cci-chatbot-form-hint" style="margin-top:10px;">We will get back to you within <strong>24 hours</strong>. Please check the email address you entered (including your <strong>spam</strong> or <strong>junk</strong> folder) for a reply from us.</p>'
            );
          } else {
            appendBot(
              '<p class="cci-chatbot-alert cci-chatbot-alert--success"><strong>Your request was saved.</strong></p>' +
                '<p class="cci-chatbot-alert cci-chatbot-alert--error" style="margin-top:10px;">We could <strong>not</strong> send the staff email from the website server (SMTP). Please call <a href="tel:+18889003382">1-888-900-3382</a> or email support. Our team can fix this using server logs (<code>chatbotEscalate mail failed</code>).</p>' +
                '<p class="cci-chatbot-form-hint" style="margin-top:10px;">If you are the site owner: confirm production <code>.env</code> has real <strong>MAIL_*</strong> SMTP settings, <strong>MAIL_MAILER=smtp</strong> (not log), and run <code>php artisan chatbot:test-mail</code> on the server. Chatbot mail uses <strong>MAIL_USERNAME</strong> as From when it is a valid email (set <strong>CHATBOT_FORCE_SMTP_ENVELOPE_FROM=false</strong> only if your provider needs otherwise).</p>' +
                '<p class="cci-chatbot-form-hint" style="margin-top:6px;">' +
                escapeHtml(res.message || '') +
                '</p>'
            );
          }
          clearActions();
        } else {
          appendBot(
            '<p class="cci-chatbot-alert cci-chatbot-alert--error">We could not submit that just now. Please call ' +
              '<a href="tel:+18889003382">1-888-900-3382</a>.</p>'
          );
          $btns.prop('disabled', false);
        }
      },
      error: function (xhr) {
        var msg = 'Please try again or call 1-888-900-3382.';
        if (xhr.status === 0) {
          msg = 'Could not reach the server (network or security block). Check your connection, disable ad-blockers for this site, or call 1-888-900-3382.';
        } else if (xhr.status === 404) {
          msg = 'Support form URL was not found (404). On the server: add routes/chatbot.php, register it in app/Providers/RouteServiceProvider.php (see mapWebRoutes), then run: php artisan route:clear';
        } else if (xhr.status === 419) {
          msg = 'Your session expired. Please refresh the page and try again.';
        } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
          var errs = xhr.responseJSON.errors;
          var k = Object.keys(errs)[0];
          if (k && errs[k] && errs[k][0]) {
            msg = errs[k][0];
          } else if (xhr.responseJSON.message) {
            msg = xhr.responseJSON.message;
          }
        } else if (xhr.responseJSON && xhr.responseJSON.message) {
          msg = xhr.responseJSON.message;
        }
        appendBot('<p class="cci-chatbot-alert cci-chatbot-alert--error">' + escapeHtml(msg) + '</p>');
        $btns.prop('disabled', false);
      },
    });
  }

  function positionPanel() {
    var headerH = $('header').outerHeight(true) || $('nav').outerHeight(true) || 120;
    var margin = 10;
    var fabH = 80;
    var topOffset = headerH + margin;
    var maxH = $(window).height() - topOffset - fabH;
    $('#cci-chatbot-panel').css({
      'max-height': maxH + 'px',
      'height': maxH + 'px'
    });
  }

  function openPanel() {
    positionPanel();
    $('#cci-chatbot-panel').addClass('is-open');
    $('#cci-chatbot-fab').attr('aria-expanded', 'true');
    if ($('#cci-chatbot-messages').data('inited')) {
      return;
    }
    function beginWelcome() {
      if ($('#cci-chatbot-messages').data('inited')) {
        return;
      }
      $('#cci-chatbot-messages').data('inited', 1);
      stateWelcome();
    }
    if (qaJsonUrl) {
      $.getJSON(qaJsonUrl)
        .done(function (d) {
          window.CCI_CHATBOT_QA = d || { tips: {} };
        })
        .fail(function () {
          window.CCI_CHATBOT_QA = { tips: {} };
        })
        .always(function () {
          beginWelcome();
        });
      return;
    }
    beginWelcome();
  }

  function closePanel() {
    $('#cci-chatbot-panel').removeClass('is-open');
    $('#cci-chatbot-fab').attr('aria-expanded', 'false');
  }

  $(document).on('click', '.chat, a.chat', function (e) {
    e.preventDefault();
    openPanel();
  });

  $(document).on('click', '#cci-chatbot-fab', function () {
    if ($('#cci-chatbot-panel').hasClass('is-open')) {
      closePanel();
    } else {
      openPanel();
    }
  });

  $(document).on('click', '#cci-chatbot-close', function () {
    closePanel();
  });

  $(document).on('click', '#cci-chatbot-actions button[data-topic]', function () {
    var topic = $(this).data('topic');
    if (topic === 'other') {
      stateOtherDirect();
      return;
    }
    appendUser($(this).text());
    stateTips(topic);
  });

  $(document).on('click', '#cci-other-continue', function () {
    var q = $.trim($('#cci-other-query').val() || '');
    if (!q) {
      appendBot(
        '<p class="cci-chatbot-alert cci-chatbot-alert--error">Please type your message in the box above, then tap <strong>Continue to contact form</strong> again.</p>'
      );
      scrollBottom();
      return;
    }
    $('#cci-chatbot-messages').data('preIssue', q);
    $('#cci-chatbot-messages').data('fromOtherDirect', 1);
    appendUser('Continue to contact form');
    stateOtherDeptPick(q);
  });

  $(document).on('click', '#cci-other-back', function () {
    $('#cci-chatbot-messages').empty();
    clearActions();
    stateWelcome();
  });

  $(document).on('click', '#cci-chatbot-actions button[data-resolve="yes"]', function () {
    appendUser('Yes, I am all set');
    stateThanksClose();
  });

  $(document).on('click', '#cci-chatbot-actions button[data-resolve="no"]', function () {
    var pre = $.trim($('#cci-tip-desc').val() || '');
    $('#cci-chatbot-messages').data('preIssue', pre);
    appendUser('No, I still need help');
    stateIntake();
  });

  $(document).on('click', '#cci-chatbot-intake-active button[data-esc-dept]', function () {
    var dept = $(this).data('escDept');
    if (dept === 'call') {
      appendUser('Call us instead');
      $('#cci-chatbot-intake-active').closest('.cci-chatbot-msg').remove();
      clearActions();
      stateCallInfo();
      return;
    }
    $('#cci-chatbot-intake-active').data('escDept', dept);
    $('#cci-chatbot-intake-active .cci-chatbot-dept').removeClass('is-selected');
    $(this).addClass('is-selected');
  });

  $(document).on('click', '#cci-other-dept-next', function () {
    var dept = $('#cci-chatbot-intake-active').data('escDept');
    if (!dept) {
      appendBot(
        '<p class="cci-chatbot-alert cci-chatbot-alert--error">Please tap <strong>IT Support</strong>, <strong>Instructor</strong>, or <strong>Customer Service</strong> above, then tap <strong>Next</strong>.</p>'
      );
      scrollBottom();
      return;
    }
    if (dept === 'call') {
      appendUser('Call us instead');
      $('#cci-chatbot-intake-active').closest('.cci-chatbot-msg').remove();
      clearActions();
      stateCallInfo();
      return;
    }
    var labels = { it: 'IT Support', instructor: 'Instructor', customer_service: 'Customer Service' };
    appendUser('Continuing as ' + (labels[dept] || dept));
    stateIntake();
    $('#cci-chatbot-intake-active').data('escDept', dept);
    $('#cci-chatbot-intake-active .cci-chatbot-dept[data-esc-dept="' + dept + '"]').addClass('is-selected');
  });

  $(document).on('click', '#cci-chatbot-intake-active .cci-chatbot-submit-esc', function () {
    var type = $('#cci-chatbot-intake-active').data('escDept');
    if (!type) {
      appendBot(
        '<p class="cci-chatbot-alert cci-chatbot-alert--error">Please tap <strong>IT Support</strong>, <strong>Instructor</strong>, or <strong>Customer Service</strong> in the form above, then <strong>Send request</strong>.</p>'
      );
      scrollBottom();
      return;
    }
    var labels = { it: 'IT Support', instructor: 'Instructor Support', customer_service: 'Customer Service' };
    appendUser('Submitted: ' + (labels[type] || type));
    submitEscalation(type);
  });

  $(document).on('click', '#cci-chatbot-restart', function () {
    $('#cci-chatbot-messages').empty().removeData('inited').removeData('preIssue').removeData('fromOtherDirect');
    clearActions();
    openPanel();
  });
})(jQuery);
