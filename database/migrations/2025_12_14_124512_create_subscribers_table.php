<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete();

            // Lemon identifiers
            $table->string('lemon_subscription_id')->unique();
            $table->string('lemon_variant_id');

            // SOURCE OF TRUTH (Lemon)
            $table->string('status')->default('active'); // active, cancelled, expired, paused, past_due, on_trial

            // DERIVED / CACHE
            $table->boolean('active')->default(false);

            // Dates
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('renews_at')->nullable();

            // URLs (muy útil)
            $table->string('customer_portal_url')->nullable();
            $table->string('update_payment_method_url')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
