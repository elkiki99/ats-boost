<?php

use App\Http\Controllers\LemonWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/lemon/webhook', [LemonWebhookController::class, 'handle']);