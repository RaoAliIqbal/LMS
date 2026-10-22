<?php

/*
 * Add the item shown in this example inside the array returned by config/mail.php.
 */

return [
    // Keep the existing mail configuration entries here.
    'signup_audit_bcc' => env('SIGNUP_EMAIL_AUDIT_BCC'),
];
