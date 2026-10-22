# Live Events + Support Chatbot — deploy package

This ZIP mirrors paths under your Laravel project root (`professional/`). Copy files **over** the same paths on the live server, then complete the **merge** steps below if anything is missing on live.

---

## 1) Copy files (preserve folders)

Unpack so `app/`, `resources/`, `webAssets/`, `config/`, `database/migrations/`, `.env.example` sit next to your existing `artisan` file.

---

## 2) Routes (`routes/web.php`)

If live `web.php` differs, **do not** overwrite the whole file. Add:

**A — Public chatbot (near top, after home route is fine):**

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

**B — Public live events / cart (with other `WebsiteController` routes):**

```php
Route::get('/events', 'WebsiteController@events')->name('eventsPage');
Route::get('/event', 'WebsiteController@events')->name('eventsPages');
Route::get('/event/add-to-cart-intent/{id}', 'WebsiteController@seminarAddToCartLoginIntent')->name('seminarAddToCartLoginIntent');
Route::get('/event/{id}', 'WebsiteController@eventDetails')->name('eventDetails');
Route::post('/add-to-cart/seminar', 'WebsiteController@addToCartSeminar')->name('addToCartSeminar');
Route::get('/remove-seminar-cart/{id}', 'WebsiteController@removeSeminarFromCart')->name('removeSeminarCart');
```

**C — Logged-in user group** (`prefix` => `user`, …): seminar checkout + enroll (if not already there):

```php
Route::get('/seminar/{id}/checkout', 'WebsiteController@seminarCheckoutPage')->name('seminarCheckoutPage');
Route::post('/enroll/seminar-cart', 'EducationController@seminarCartEnroll')->name('seminarCartEnroll');
Route::post('/seminar/{id}/enroll', 'EducationController@seminarEnroll')->name('client_seminarEnroll');
Route::get('/live-events', 'EducationController@myEvents')->name('client_events');
Route::get('/live-event/{id}', 'EducationController@myEvent')->name('client_event');
Route::get('/live-event/{id}/certificate', 'EducationController@generateSeminarCertificate')->name('seminarCertificate');
```

**D — Admin group** (`prefix` => `admin`, …):

```php
Route::resource('chatbot-qas', 'Admin\ChatbotQaController')->except(['show'])->names([
    'index' => 'admin.chatbot-qas.index',
    'create' => 'admin.chatbot-qas.create',
    'store' => 'admin.chatbot-qas.store',
    'edit' => 'admin.chatbot-qas.edit',
    'update' => 'admin.chatbot-qas.update',
    'destroy' => 'admin.chatbot-qas.destroy',
]);
Route::resource('seminars', 'Admin\SeminarController');
Route::post('/seminars/{seminarId}/attendees/{seminarUserId}/mark-attended', 'Admin\SeminarController@markAttended')->name('seminar.markAttended');
Route::post('/seminars/{seminarId}/attendees/{seminarUserId}/unmark-attended', 'Admin\SeminarController@unmarkAttended')->name('seminar.unmarkAttended');
```

---

## 3) `WebsiteController.php`

The bundle includes the **full** `app/Http/Controllers/WebsiteController.php` from dev.  
**Live par pehle diff / backup:** agar live controller alag hai to poori file mat copy karein — sirf **events**, **seminar cart**, **checkout**, **chatbot** wale methods + related `use` imports merge karein (WinMerge / VS Code compare).

---

## 4) `app/Console/Kernel.php`

Ensure:

- `$commands` array contains `\App\Console\Commands\SendChatbotFollowUps::class`
- `schedule()` contains: `$schedule->command('chatbot:send-followups')->hourly();`

Cron on server: `* * * * * cd /path-to-app && php artisan schedule:run >> /dev/null 2>&1`

---

## 5) `config/settings.php`

Merge the `'chatbot' => [ ... ]` block from bundled `config/settings.php` (and `google_review_url` if missing). Do not wipe unrelated keys.

---

## 6) `resources/views/layouts/web.blade.php`

Before `</body>`, ensure:

```blade
@include('layouts.partials.chatbot-support')
```

---

## 7) Admin sidebar

In `resources/views/admin/layouts/partials/sidebar.blade.php`, add the **Support Chatbot Q&A** link (see `MERGE/admin-sidebar-snippet.blade.php` in this bundle).

---

## 8) Migrations (live DB)

SSH on live (PHP version same as app, e.g. Laragon / server PHP):

```bash
php artisan migrate --force
```

Runs:

- `chat_escalations` + columns (`course_name`, etc.)
- `chatbot_qas`

---

## 9) `.env` on live

Copy chatbot lines from bundled `.env.example`, e.g.:

- `CHATBOT_MANAGER_EMAIL=mgr@professionalonlineeducation.com`
- `CHATBOT_SUPPORT_EMAIL=...`
- Optional `CHATBOT_FROM_EMAIL`, names, etc.

Then:

```bash
php artisan config:clear
php artisan config:cache   # if you use config cache
```

---

## 10) `EducationController` / seminar enroll

Agar live site par pehle se seminar checkout / `seminarCartEnroll` / `unifiedCartEnroll` maujood nahi, to sirf is bundle se **views** copy karne se checkout UI aa sakta hai lekin enroll fail ho sakta hai. Us surat mein dev branch ka `EducationController` bhi compare karke merge karein (yeh package us file ko include **nahi** karta taake galat overwrite na ho).

---

## Urdu short checklist

1. ZIP se folders copy karo (paths same rakho).  
2. `routes/web.php` mein upar wale blocks add/merge karo.  
3. `WebsiteController` live se match karke merge — poori file blind copy risky.  
4. `Kernel.php` + cron.  
5. `php artisan migrate --force`  
6. `.env` + `config:clear`  
7. Admin sidebar mein Chatbot Q&A link.

---

**Questions:** Compare `WebsiteController` and `EducationController` on live with dev before overwriting.
