<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,sans-serif">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0"
       style="background:#f4f6f8;padding:30px 15px">
    <tr>
        <td align="center">
            <table role="presentation" width="600" cellpadding="0" cellspacing="0"
                   style="max-width:600px;width:100%;background:#fff;border-radius:8px;overflow:hidden">
                <tr>
                    <td style="background:#183b56;padding:25px;text-align:center">
                        <h1 style="margin:0;color:#fff;font-size:24px">Canham College</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding:35px;color:#333">
                        <h2 style="margin-top:0;color:#183b56">Registration Successful</h2>
                        <p>Hello {{ $user->name ?: 'Student' }},</p>
                        <p>
                            Thank you for registering with Canham College.
                            Your account has been created successfully.
                        </p>
                        <p><strong>Registered email:</strong> {{ $user->email }}</p>
                        <p style="text-align:center;margin:35px 0">
                            <a href="{{ $loginUrl }}"
                               style="display:inline-block;padding:13px 28px;background:#f4a024;
                                      color:#fff;text-decoration:none;border-radius:5px">
                                Login to Your Account
                            </a>
                        </p>
                        <p>If you did not create this account, please contact support.</p>
                        <p style="margin-bottom:0">
                            Regards,<br><strong>Canham College Team</strong>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="background:#eef1f4;padding:20px;text-align:center;color:#666;font-size:13px">
                        Professional Online Education<br>
                        <a href="https://professionalonlineeducation.com/" style="color:#183b56">
                            professionalonlineeducation.com
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

