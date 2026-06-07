<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (! Schema::hasColumn('items', 'requires_access_link')) {
                $table->boolean('requires_access_link')->default(false)->after('is_featured');
            }

            if (! Schema::hasColumn('items', 'access_link')) {
                $table->string('access_link')->nullable()->after('requires_access_link');
            }

            if (! Schema::hasColumn('items', 'access_instruction')) {
                $table->text('access_instruction')->nullable()->after('access_link');
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'access_instruction')) {
                $table->dropColumn('access_instruction');
            }

            if (Schema::hasColumn('items', 'access_link')) {
                $table->dropColumn('access_link');
            }

            if (Schema::hasColumn('items', 'requires_access_link')) {
                $table->dropColumn('requires_access_link');
            }
        });
    }
};