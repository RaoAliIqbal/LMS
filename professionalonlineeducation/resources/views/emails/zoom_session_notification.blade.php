<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Live Zoom Session Scheduled</title>
</head>
<body style="margin:0;padding:0;background:#f4f7fb;font-family:Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f7fb;padding:30px 0;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.08);">

        {{-- Header --}}
        <tr>
          <td style="background:linear-gradient(135deg,#054f7a,#0877b8);padding:32px 40px;text-align:center;">
            <img src="https://www.professionalonlineeducation.com/webAssets/images/logo-footer.png"
                 alt="Professional Online Education" height="50"
                 style="margin-bottom:14px;display:block;margin-left:auto;margin-right:auto;">
            <h1 style="color:#ffffff;margin:0;font-size:22px;font-weight:700;">
              📹 Live Zoom Session Scheduled!
            </h1>
          </td>
        </tr>

        {{-- Body --}}
        <tr>
          <td style="padding:36px 40px;">

            <p style="font-size:16px;color:#333;margin:0 0 10px 0;">
              Hi <strong>{{ $studentName }}</strong>,
            </p>
            <p style="font-size:14px;color:#555;margin:0 0 24px 0;">
              Great news! A live Zoom session has been scheduled for your course. Here are the details:
            </p>

            {{-- Course name --}}
            <div style="background:#f0f7ff;border-left:4px solid #0877b8;border-radius:0 6px 6px 0;padding:14px 18px;margin-bottom:24px;">
              <p style="margin:0;font-size:13px;color:#888;text-transform:uppercase;letter-spacing:0.5px;">Course</p>
              <p style="margin:4px 0 0 0;font-size:16px;font-weight:700;color:#0877b8;">{{ $lesson->title }}</p>
            </div>

            {{-- Meeting details table --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              @if(!empty($meetingData['zoom_date']))
              <tr>
                <td style="padding:10px 14px;background:#f9f9f9;border-radius:6px;margin-bottom:6px;width:40%;font-size:13px;color:#888;font-weight:600;">
                  📅 Date
                </td>
                <td style="padding:10px 14px;font-size:14px;color:#333;font-weight:700;">
                  {{ \Carbon\Carbon::parse($meetingData['zoom_date'])->format('l, F j, Y') }}
                </td>
              </tr>
              @endif
              @if(!empty($meetingData['zoom_time']))
              <tr>
                <td style="padding:10px 14px;background:#f9f9f9;border-radius:6px;font-size:13px;color:#888;font-weight:600;">
                  🕑 Time
                </td>
                <td style="padding:10px 14px;font-size:14px;color:#333;font-weight:700;">
                  {{ \Carbon\Carbon::createFromFormat('H:i', $meetingData['zoom_time'])->format('g:i A') }} (Pacific Time)
                </td>
              </tr>
              @endif
              @if(!empty($meetingData['meeting_id']))
              <tr>
                <td style="padding:10px 14px;background:#f9f9f9;border-radius:6px;font-size:13px;color:#888;font-weight:600;">
                  # Meeting ID
                </td>
                <td style="padding:10px 14px;font-size:14px;color:#333;font-weight:700;font-family:monospace;">
                  {{ $meetingData['meeting_id'] }}
                </td>
              </tr>
              @endif
              @if(!empty($meetingData['zoom_password']))
              <tr>
                <td style="padding:10px 14px;background:#f9f9f9;border-radius:6px;font-size:13px;color:#888;font-weight:600;">
                  🔒 Passcode
                </td>
                <td style="padding:10px 14px;font-size:14px;color:#333;font-weight:700;font-family:monospace;">
                  {{ $meetingData['zoom_password'] }}
                </td>
              </tr>
              @endif
            </table>

            @if(!empty($meetingData['zoom_description']))
            <div style="background:#fffbe6;border:1px solid #ffe58f;border-radius:6px;padding:12px 16px;margin-bottom:24px;">
              <p style="margin:0;font-size:13px;color:#7d6608;">
                <strong>📝 Note:</strong> {{ $meetingData['zoom_description'] }}
              </p>
            </div>
            @endif

            {{-- Join button --}}
            @if(!empty($meetingData['zoom_link']))
            <div style="text-align:center;margin:28px 0;">
              <a href="{{ $meetingData['zoom_link'] }}"
                 style="display:inline-block;background:#2d8cff;color:#ffffff;text-decoration:none;padding:14px 40px;border-radius:6px;font-size:16px;font-weight:700;letter-spacing:0.3px;">
                📹 Join Live Session
              </a>
              <p style="font-size:11px;color:#999;margin-top:8px;">
                Please join 5 minutes before the session starts.
              </p>
            </div>
            @endif

            <hr style="border:none;border-top:1px solid #eee;margin:24px 0;">

            <p style="font-size:13px;color:#777;margin:0;">
              You can also access this session from your
              <a href="https://www.professionalonlineeducation.com/user/my-courses" style="color:#0877b8;">student dashboard</a>
              under My Courses.
            </p>

          </td>
        </tr>

        {{-- Footer --}}
        <tr>
          <td style="background:#f4f7fb;padding:20px 40px;text-align:center;border-top:1px solid #eee;">
            <p style="margin:0;font-size:12px;color:#aaa;">
              Canham College, Inc. &bull; 2934 E. Avalon Drive, Phoenix, AZ 85016<br>
              Toll Free: 1-888-900-3382 &bull;
              <a href="https://www.professionalonlineeducation.com" style="color:#0877b8;text-decoration:none;">
                www.professionalonlineeducation.com
              </a>
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>
