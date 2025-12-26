<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');      // email, sms, etc.
            $table->string('recipient'); // email address or phone number
            $table->json('payload');     // The content/data
            $table->string('status');    // pending, processing, sent, failed
            $table->integer('retry_count')->default(0);
            $table->text('error_message')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
