<?php

/*
 * Add the imports below to the registration controller.
 */

use App\Services\SignupEmailService;
use Illuminate\Support\Facades\DB;

/*
 * Immediately after User::create(...) succeeds, add:
 *
 * This sends synchronously, so no queue worker is required.
 */

DB::afterCommit(function () use ($user) {
    app(SignupEmailService::class)->send($user);
});

