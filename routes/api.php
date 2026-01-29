<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/mercadopago', [WebhookController::class, 'handle'])->name('webhooks.mercadopago');
