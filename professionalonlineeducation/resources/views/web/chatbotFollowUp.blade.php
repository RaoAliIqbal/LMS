<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $title ?? 'Thank You' }} — Professional Online Education</title>
  <style>
    body { margin:0; font-family:Arial,sans-serif; background:#f4f6f8; display:flex; align-items:center; justify-content:center; min-height:100vh; }
    .card { background:#fff; border-radius:10px; box-shadow:0 4px 20px rgba(0,0,0,.1); padding:48px 40px; max-width:500px; width:90%; text-align:center; }
    .icon { font-size:54px; margin-bottom:16px; }
    h1 { font-size:24px; color:#1a2e44; margin:0 0 12px; }
    p { color:#555; font-size:15px; line-height:1.7; margin:0 0 28px; }
    a.btn { display:inline-block; background:#1379ba; color:#fff; text-decoration:none; padding:12px 28px; border-radius:6px; font-weight:bold; font-size:14px; }
    a.btn:hover { background:#0e5a96; }
    .footer { margin-top:24px; font-size:12px; color:#aaa; }
  </style>
</head>
<body>
  <div class="card">
    <div class="icon">{{ $icon ?? '✅' }}</div>
    <h1>{{ $title ?? 'Thank You!' }}</h1>
    <p>{{ $message }}</p>
    <a href="{{ url('/') }}" class="btn">Back to Homepage</a>
    <p class="footer">CCI Support Team &mdash; Professional Online Education<br><a href="tel:+18889003382" style="color:#1379ba;font-weight:bold;">1-888-900-3382</a> (toll free)</p>
  </div>
</body>
</html>
