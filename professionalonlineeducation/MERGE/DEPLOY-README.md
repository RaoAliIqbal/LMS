# Chatbot deploy (sirf is ZIP se)

## Copy files

ZIP unzip karo — `app/`, `database/`, `resources/`, `webAssets/` apne Laravel root par same paths par copy karo.

## Routes (`routes/web.php`)

```php
Route::post('/chatbot/escalate', 'WebsiteController@chatbotEscalate')
    ->middleware('throttle:20,1')
    ->name('chatbot.escalate');
Route::get('/chatbot/followup/{token}/{outcome}', 'WebsiteController@chatbotFollowUp')
    ->middleware('throttle:30,1')
    ->name('chatbot.followup');
Route::get('/chatbot/qa.json', 'WebsiteController@chatbotQaJson')
    ->middleware('throttle:60,1')
    ->name('chatbot.qa.json');
```

Admin group ke andar:

```php
Route::resource('chatbot-qas', 'Admin\ChatbotQaController')->except(['show'])->names([
    'index' => 'admin.chatbot-qas.index',
    'create' => 'admin.chatbot-qas.create',
    'store' => 'admin.chatbot-qas.store',
    'edit' => 'admin.chatbot-qas.edit',
    'update' => 'admin.chatbot-qas.update',
    'destroy' => 'admin.chatbot-qas.destroy',
]);
```

## `WebsiteController.php`

`MERGE/WebsiteController-chatbot-METHODS.php.txt` — methods class ke andar paste karo. Zaroori `use` lines (agar missing hon):

- `use App\Mail\ChatbotStaffNotificationMail;`
- `use App\Mail\ChatbotStudentReplyMail;`
- `use App\Mail\ChatbotInternalPriorityMail;`
- `use App\Models\ChatEscalation;`
- `use App\Models\ChatbotQa;`
- `use Illuminate\Database\QueryException;`
- `use Illuminate\Support\Facades\Mail;`
- `use Illuminate\Support\Facades\Schema;`
- `use Illuminate\Support\Facades\Validator;`
- `use Illuminate\Support\Str;`

## Config

`MERGE/settings-FULL-REFERENCE.php` se `'chatbot' => [...]` block apni `config/settings.php` mein merge karo.

## `.env`

`MERGE/.env.example-CHATBOT-REFERENCE.txt` se chatbot lines copy karo. Phir `php artisan config:clear`.

## Kernel + cron

Bundled `app/Console/Kernel.php` copy karo **ya** sirf `SendChatbotFollowUps` + `chatbot:send-followups` hourly schedule merge karo.

## Migrations

```bash
php artisan migrate --force
```

## Admin sidebar

`MERGE/admin-sidebar-snippet.blade.php` ka `<li>...</li>` `resources/views/admin/layouts/partials/sidebar.blade.php` mein add karo.

## `web.blade.php`

`</body>` se pehle: `@include('layouts.partials.chatbot-support')`
