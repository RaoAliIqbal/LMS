<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f8;padding:30px 0;">
  <tr><td align="center">
    <table width="620" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.1);">

      <tr><td style="background:#c0392b;padding:22px 30px;">
        <p style="margin:0;color:#fff;font-size:20px;font-weight:bold;">⚠️ Priority Escalation: {{ $issueTypeLabel ?? $escalation->typeLabel() }}</p>
        <p style="margin:4px 0 0;color:#f5c6c1;font-size:13px;">Support Bot Escalations — Professional Online Education</p>
      </td></tr>

      <tr><td style="padding:28px 30px;">
        <p style="font-size:14px;color:#333;line-height:1.7;margin-top:0;">
          A student has indicated that their issue is <strong style="color:#c0392b;">not yet resolved</strong> and requires direct intervention.
          Please review the details below and reach out to the student to finalize a solution.
        </p>

        {{-- Student details table --}}
        <p style="font-size:14px;color:#222;font-weight:bold;margin:20px 0 10px;">Student Profile &amp; Issue Details:</p>
        <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e4e8ec;border-radius:6px;overflow:hidden;margin-bottom:20px;">
          <tr style="background:#f7f9fb;">
            <td style="padding:10px 16px;font-size:13px;color:#888;width:160px;">Student Name</td>
            <td style="padding:10px 16px;font-size:13px;color:#222;font-weight:bold;">{{ $escalation->name }}</td>
          </tr>
          <tr>
            <td style="padding:10px 16px;font-size:13px;color:#888;border-top:1px solid #edf0f3;">Email</td>
            <td style="padding:10px 16px;font-size:13px;border-top:1px solid #edf0f3;">
              <a href="mailto:{{ $escalation->email }}" style="color:#1379ba;">{{ $escalation->email }}</a>
            </td>
          </tr>
          <tr style="background:#f7f9fb;">
            <td style="padding:10px 16px;font-size:13px;color:#888;border-top:1px solid #edf0f3;">Telephone</td>
            <td style="padding:10px 16px;font-size:13px;color:#222;border-top:1px solid #edf0f3;">{{ $escalation->phone ?: 'Not provided' }}</td>
          </tr>
          <tr>
            <td style="padding:10px 16px;font-size:13px;color:#888;border-top:1px solid #edf0f3;">Course Name</td>
            <td style="padding:10px 16px;font-size:13px;color:#222;border-top:1px solid #edf0f3;">{{ $escalation->course_name ?: 'Not specified' }}</td>
          </tr>
          <tr style="background:#f7f9fb;">
            <td style="padding:10px 16px;font-size:13px;color:#888;border-top:1px solid #edf0f3;">Issue Category</td>
            <td style="padding:10px 16px;font-size:13px;color:#222;border-top:1px solid #edf0f3;">{{ $issueTypeLabel ?? $escalation->typeLabel() }}</td>
          </tr>
          <tr>
            <td style="padding:10px 16px;font-size:13px;color:#888;border-top:1px solid #edf0f3;">Original Submission</td>
            <td style="padding:10px 16px;font-size:13px;color:#222;border-top:1px solid #edf0f3;">{{ optional($escalation->created_at)->format('M d, Y g:i A') ?: '—' }}</td>
          </tr>
        </table>

        <p style="font-size:13px;color:#888;font-weight:bold;margin:0 0 8px;">Issue Description:</p>
        <div style="background:#fff8f8;border-left:4px solid #c0392b;padding:12px 16px;border-radius:4px;font-size:13px;color:#333;line-height:1.6;margin-bottom:20px;">
          {{ $escalation->issue ?: 'No description provided.' }}
        </div>

        <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:6px;padding:14px 16px;">
          <p style="margin:0;font-size:13px;color:#856404;line-height:1.6;">
            <strong>Interaction Summary:</strong><br>
            The student interacted with the chatbot but signalled via the follow-up email that the resolution was unsuccessful.
            This ticket is now assigned to your department for immediate follow-up.
          </p>
          <p style="margin:10px 0 0;font-size:12px;color:#856404;">
            <strong>Internal Note:</strong> Please update the ticket status once you have contacted the student to ensure we maintain our support standards.
          </p>
        </div>

      </td></tr>

      <tr><td style="background:#f0f4f8;padding:14px 30px;border-top:1px solid #e8edf2;">
        <p style="margin:0;font-size:11px;color:#aaa;">© {{ date('Y') }} Canham College, Inc. | CCI Support Bot | 1-888-900-3382</p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body>
</html>
