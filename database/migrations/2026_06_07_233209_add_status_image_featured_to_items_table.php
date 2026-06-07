<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (! Schema::hasColumn('items', 'status')) {
                $table->string('status')->default('active')->after('stock');
            }

            if (! Schema::hasColumn('items', 'image')) {
                $table->string('image')->nullable()->after('description');
            }

            if (! Schema::hasColumn('items', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'is_featured')) {
                $table->dropColumn('is_featured');
            }

            if (Schema::hasColumn('items', 'image')) {
                $table->dropColumn('image');
            }

            if (Schema::hasColumn('items', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};