<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f8;padding:30px 0;">
  <tr><td align="center">
    <table width="620" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.1);">

      {{-- Header colour changes per variant --}}
      @if($variant === 'great_news')
        <tr><td style="background:#28a745;padding:22px 30px;">
          <p style="margin:0;color:#fff;font-size:22px;font-weight:bold;">Great news! 🎉</p>
          <p style="margin:4px 0 0;color:#d4f5dc;font-size:13px;">Professional Online Education — CCI Support Team</p>
        </td></tr>
      @elseif($variant === 'on_it')
        <tr><td style="background:#e67e22;padding:22px 30px;">
          <p style="margin:0;color:#fff;font-size:22px;font-weight:bold;">We're on it! ⚡</p>
          <p style="margin:4px 0 0;color:#fde8cf;font-size:13px;">Professional Online Education — CCI Support Team</p>
        </td></tr>
      @else
        <tr><td style="background:#c0392b;padding:22px 30px;">
          <p style="margin:0;color:#fff;font-size:22px;font-weight:bold;">Issue Escalated to Administration</p>
          <p style="margin:4px 0 0;color:#f5c6c1;font-size:13px;">Professional Online Education — CCI Support Team</p>
        </td></tr>
      @endif

      <tr><td style="padding:30px;">
        <p style="font-size:15px;color:#222;margin-top:0;">Hi <strong>{{ $escalation->name }}</strong>,</p>

        @if($variant === 'great_news')
          <p style="color:#555;line-height:1.7;font-size:14px;">
            Thanks for letting us know! We're glad we could help. You're all set to get back to your coursework.
          </p>
          <p style="color:#555;line-height:1.7;font-size:14px;">
            If anything else comes up, you know where to find us. Happy learning! 📚
          </p>

        @elseif($variant === 'on_it')
          <p style="color:#555;line-height:1.7;font-size:14px;">
            We're sorry to hear that things aren't quite right yet. We have flagged your request as a <strong>priority</strong>.
          </p>
          <p style="color:#555;line-height:1.7;font-size:14px;">
            A team member (IT, Instructor, or Support) will review your case and reach out to you via email shortly to get this finished. Hang tight!
          </p>

        @else
          <p style="color:#555;line-height:1.7;font-size:14px;">
            Thank you for your feedback. We are sorry the initial solution didn't work.
          </p>
          <p style="color:#555;line-height:1.7;font-size:14px;">
            This issue has been officially reported to our LMS (Learning Management System) Administrator for a deeper technical review.
          </p>
          <p style="color:#555;line-height:1.7;font-size:14px;">
            We are working to ensure this doesn't happen again. However, if you encounter this specific error in the future, please contact us immediately so we can investigate it in real-time.
          </p>
        @endif

        <hr style="border:none;border-top:1px solid #eee;margin:24px 0;">
        <p style="color:#555;font-size:14px;margin-bottom:4px;">Best Regards,</p>
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
