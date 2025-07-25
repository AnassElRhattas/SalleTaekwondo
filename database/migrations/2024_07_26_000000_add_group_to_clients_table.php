<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clients', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->string('group')->nullable()->after('address')->comment('Box, Taekwondo, Karaté');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('group');
        });
    }
};
