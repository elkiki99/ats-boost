<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete();

            // Mercado Pago identifiers
            $table->string('mp_subscription_id')->unique();
            $table->string('mp_plan_id');

            // SOURCE OF TRUTH (Mercado Pago)
            $table->string('status')->default('active'); // active, cancelled, paused, pending

            // DERIVED / CACHE
            $table->boolean('active')->default(false);

            // Dates
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('renews_at')->nullable();

            // Mercado Pago data
            $table->string('payer_email')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};