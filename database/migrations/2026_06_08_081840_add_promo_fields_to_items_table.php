<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (! Schema::hasColumn('items', 'is_promo')) {
                $table->boolean('is_promo')->default(false)->after('is_featured');
            }

            if (! Schema::hasColumn('items', 'promo_price')) {
                $table->decimal('promo_price', 18, 2)->nullable()->after('price');
            }

            if (! Schema::hasColumn('items', 'promo_ends_at')) {
                $table->timestamp('promo_ends_at')->nullable()->after('promo_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn([
                'is_promo',
                'promo_price',
                'promo_ends_at',
            ]);
        });
    }
};