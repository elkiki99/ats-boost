<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LemonWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Log para ver qué llega
        \Log::info('Lemon Webhook Received:', $request->all());

        return response()->json(['status' => 'ok']);
    }
}