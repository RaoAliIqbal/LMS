<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f8;padding:30px 0;">
  <tr><td align="center">
    <table width="620" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.1);">

      <tr><td style="background:#1379ba;padding:22px 30px;">
        <p style="margin:0;color:#fff;font-size:20px;font-weight:bold;">New Support Request — CCI Chatbot</p>
        <p style="margin:4px 0 0;color:#cce5f6;font-size:13px;">A student has submitted a support request via the website chatbot.</p>
      </td></tr>

      <tr><td style="padding:28px 30px;">
        <p style="margin:0 0 18px;font-size:15px;color:#222;font-weight:bold;">
          Request Type: <span style="color:#1379ba;">{{ $escalation->typeLabel() }}</span>
        </p>

        <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e4e8ec;border-radius:6px;overflow:hidden;margin-bottom:20px;">
          <tr style="background:#f7f9fb;">
            <td style="padding:10px 16px;font-size:13px;color:#888;width:160px;">Student Name</td>
            <td style="padding:10px 16px;font-size:13px;color:#222;font-weight:bold;">{{ $escalation->name }}</td>
          </tr>
          <tr>
            <td style="padding:10px 16px;font-size:13px;color:#888;border-top:1px solid #edf0f3;">Email</td>
            <td style="padding:10px 16px;font-size:13px;color:#222;border-top:1px solid #edf0f3;">
              <a href="mailto:{{ $escalation->email }}" style="color:#1379ba;">{{ $escalation->email }}</a>
            </td>
          </tr>
          <tr style="background:#f7f9fb;">
            <td style="padding:10px 16px;font-size:13px;color:#888;border-top:1px solid #edf0f3;">Phone</td>
            <td style="padding:10px 16px;font-size:13px;color:#222;border-top:1px solid #edf0f3;">{{ $escalation->phone ?: 'Not provided' }}</td>
          </tr>
          <tr>
            <td style="padding:10px 16px;font-size:13px;color:#888;border-top:1px solid #edf0f3;">Course Name</td>
            <td style="padding:10px 16px;font-size:13px;color:#222;border-top:1px solid #edf0f3;">{{ $escalation->course_name ?: 'Not specified' }}</td>
          </tr>
          <tr style="background:#f7f9fb;">
            <td style="padding:10px 16px;font-size:13px;color:#888;border-top:1px solid #edf0f3;">Issue Category</td>
            <td style="padding:10px 16px;font-size:13px;color:#222;border-top:1px solid #edf0f3;">{{ $escalation->typeLabel() }}</td>
          </tr>
          <tr>
            <td style="padding:10px 16px;font-size:13px;color:#888;border-top:1px solid #edf0f3;">Submitted</td>
            <td style="padding:10px 16px;font-size:13px;color:#222;border-top:1px solid #edf0f3;">{{ optional($escalation->created_at)->format('M d, Y g:i A') ?: '—' }}</td>
          </tr>
        </table>

        <p style="margin:0 0 8px;font-size:13px;color:#888;font-weight:bold;">Issue Description:</p>
        <div style="background:#f7f9fb;border-left:4px solid #1379ba;padding:12px 16px;border-radius:4px;font-size:13px;color:#333;line-height:1.6;">
          {{ $escalation->issue ?: 'No description provided.' }}
        </div>

        <p style="margin:20px 0 0;font-size:13px;color:#999;">Please reach out to the student at <a href="mailto:{{ $escalation->email }}" style="color:#1379ba;">{{ $escalation->email }}</a> as soon as possible.</p>
      </td></tr>

      <tr><td style="background:#f0f4f8;padding:14px 30px;border-top:1px solid #e8edf2;">
        <p style="margin:0;font-size:11px;color:#aaa;">© {{ date('Y') }} Canham College, Inc. | CCI Support Bot | 1-888-900-3382</p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body>
</html>
