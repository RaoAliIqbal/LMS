<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f8;padding:30px 0;">
  <tr><td align="center">
    <table width="620" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.1);">

      <tr><td style="background:#1379ba;padding:22px 30px;">
        <p style="margin:0;color:#fff;font-size:20px;font-weight:bold;">Professional Online Education</p>
        <p style="margin:4px 0 0;color:#cce5f6;font-size:13px;">Support Follow-Up — Canham College, Inc.</p>
      </td></tr>

      <tr><td style="padding:30px;">
        <p style="font-size:16px;color:#222;margin-top:0;">Hi <strong>{{ $escalation->name }}</strong>,</p>

        <p style="color:#555;line-height:1.7;font-size:14px;">
          We're following up on your recent chat support request regarding
          <strong>{{ $escalation->course_name ?: $escalation->typeLabel() }}</strong>.
        </p>

        <p style="color:#555;line-height:1.7;font-size:14px;">
          We want to make sure you have everything you need to get back to your studies.<br>
          <strong>Please let us know if your issue has been handled by clicking one of the options below:</strong>
        </p>

        {{-- Buttons --}}
        <table cellpadding="0" cellspacing="0" style="margin:28px 0;width:100%;">
          <tr>
            <td style="padding:6px 6px 6px 0;">
              <a href="{{ $yesUrl }}"
                 style="display:inline-block;background:#28a745;color:#fff;text-decoration:none;padding:13px 20px;border-radius:6px;font-weight:bold;font-size:13px;white-space:nowrap;">
                ✅ Yes, All Set
              </a>
            </td>
            <td style="padding:6px;">
              <a href="{{ $noUrl }}"
                 style="display:inline-block;background:#e74c3c;color:#fff;text-decoration:none;padding:13px 20px;border-radius:6px;font-weight:bold;font-size:13px;white-space:nowrap;">
                ❌ No, I Still Need Help
              </a>
            </td>
            <td style="padding:6px 0 6px 6px;">
              <a href="{{ $notResolvedUrl }}"
                 style="display:inline-block;background:#6c757d;color:#fff;text-decoration:none;padding:13px 16px;border-radius:6px;font-weight:bold;font-size:13px;white-space:nowrap;">
                🚫 Not Resolved, No Help Needed
              </a>
            </td>
          </tr>
        </table>

        <p style="color:#777;font-size:13px;line-height:1.6;">
          If you click <strong>"No, I Still Need Help"</strong>, a member of our team will reach out to you directly to prioritize your request.
        </p>

        <hr style="border:none;border-top:1px solid #eee;margin:24px 0;">

        <p style="color:#555;font-size:14px;margin-bottom:4px;">Best regards,</p>
        <p style="color:#222;font-size:14px;font-weight:bold;margin-top:0;">CCI Support Team<br>Professional Online Education</p>
      </td></tr>

      @php
        $cciFooterMail = config('settings.chatbot.manager_email')
            ?: config('settings.chatbot.support_email', 'mgr@professionalonlineeducation.com');
      @endphp
      <tr><td style="background:#f0f4f8;padding:14px 30px;border-top:1px solid #e8edf2;">
        <p style="margin:0;font-size:11px;color:#aaa;">© {{ date('Y') }} Canham College, Inc. | 1-888-900-3382 | <a href="mailto:{{ $cciFooterMail }}" style="color:#1379ba;">{{ $cciFooterMail }}</a></p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body>
</html>
