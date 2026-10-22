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

    // Google Review URL — update this when client provides the link
    'google_review_url' => env('GOOGLE_REVIEW_URL', 'https://g.page/r/PLACEHOLDER/review'),

    // Website chatbot — escalation & follow-up emails
    // Use MAIL_FROM_* for “From” unless CHATBOT_FROM_EMAIL is set (many SMTP servers reject a From that ≠ authenticated user).
    'chatbot' => [
        // When set, all staff notifications (form escalations, 48h priority follow-up) go here; student-facing mail still goes to the student.
        'manager_email' => env('CHATBOT_MANAGER_EMAIL', 'mgr@professionalonlineeducation.com'),
        'support_email' => env('CHATBOT_SUPPORT_EMAIL', 'support@professionalonlineeducation.com'),
        'from_address' => env('CHATBOT_FROM_EMAIL') ?: null,
        'from_name_escalations' => env('CHATBOT_FROM_NAME', 'Support Bot Escalations'),
        'from_name_student' => env('CHATBOT_STUDENT_FROM_NAME', 'CCI Support Team'),
        // Used only if manager_email is empty (legacy split inboxes)
        'it_email' => env('CHATBOT_IT_EMAIL', null),
        'instructor_email' => env('CHATBOT_INSTRUCTOR_EMAIL', null),
        'customer_service_email' => env('CHATBOT_CUSTOMER_SERVICE_EMAIL', null),
    ],
];
