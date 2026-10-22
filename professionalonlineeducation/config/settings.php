<?php

return [

//   'site_logo'=>'assets/images/logo-1.png',
    'admin_email'=>'drcanham@professionalonlineeducation.com',
   //  'admin_email'=>'mikecalhon7966@gmail.com',
    'text_limit'=>'100',
    'from_email_notification'=>'',
    'from_name_notification'=>'',
    'project_url_admin'=>'/project/',
    'pagination'=>array(
        'alfa' => '8',
        'beta' => '10',
    ),
    'userStatus'=>array(
        '1'=>'Active',
        '0'=>'Not Active'
    ),

    'post_placeholder'=>'/assets/images/psx-vault.png',

    'CeCategories'=>['1','3','4'],
    'nonCeCategories'=>['2','5','6','7'],

    // Google Review URL — set GOOGLE_REVIEW_URL in .env; widget stays hidden until set (no broken placeholder link).
    'google_review_url' => env('GOOGLE_REVIEW_URL') ?: null,

    // Website chatbot — escalation & follow-up emails
    // All staff notifications (new form, DB fallback, 48h priority) and Reply-To on student-facing chatbot mail go to manager_email.
    'chatbot' => [
        'manager_email' => env('CHATBOT_MANAGER_EMAIL', 'mgr@professionalonlineeducation.com'),
        'support_email' => env('CHATBOT_SUPPORT_EMAIL', 'mgr@professionalonlineeducation.com'),
        // Default true: when MAIL_USERNAME is a valid email, use it as envelope From for all chatbot mail
        // (matches typical local Mailtrap / cPanel behaviour). Set CHATBOT_FORCE_SMTP_ENVELOPE_FROM=false only
        // if your provider requires a different From than the SMTP login (e.g. some API transports).
        'force_smtp_username_as_envelope_from' => env('CHATBOT_FORCE_SMTP_ENVELOPE_FROM', true),
        // When false (default), staff/student chatbot mail uses MAIL_FROM_ADDRESS as envelope From
        // so SMTP auth matches — set CHATBOT_USE_CUSTOM_FROM=true only if your provider allows it.
        'use_custom_chatbot_from' => env('CHATBOT_USE_CUSTOM_FROM', false),
        'from_address' => env('CHATBOT_FROM_EMAIL') ?: null,
        'from_name_escalations' => env('CHATBOT_FROM_NAME', 'Support Bot Escalations'),
        'from_name_student' => env('CHATBOT_STUDENT_FROM_NAME', 'CCI Support Team'),
        // Legacy keys (unused when manager_email is set — kept for older merges)
        'it_email' => env('CHATBOT_IT_EMAIL', null),
        'instructor_email' => env('CHATBOT_INSTRUCTOR_EMAIL', null),
        'customer_service_email' => env('CHATBOT_CUSTOMER_SERVICE_EMAIL', null),
    ],

    /*
    | Homepage-only maintenance overlay (config/settings.php — NOT .env).
    | Shown only on / and /home. Checkout, login, cart, and user dashboard stay open.
    | Set enabled => false when done.
    */
    'footer_maintenance_banner' => [
        'enabled' => false,
        'headline' => "We'll Be Back Soon",
        'message' => 'Our website is undergoing scheduled maintenance to serve you better. Please check back shortly or call us for immediate assistance.',
        'phone' => '1-888-900-3382',
        'phone_href' => 'tel:+18889003382',
    ],
];
