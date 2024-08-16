<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(config('simple-auditor.table_name'), function (Blueprint $table) {
            $table->id();

            $table->string('message');
            $table->json('context')->default('[]');

            $table->unsignedBigInteger('user_id')->nullable()->default(null);
            $table->string('ip_address', 45)->nullable()->default(null);

            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
