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
        Schema::table(config('simple-auditor.table_name'), function (Blueprint $table) {
            $table->string('guard')->nullable()->default(null)->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(config('simple-auditor.table_name'), function (Blueprint $table) {
            $table->dropColumn('guard');
        });
    }
};
