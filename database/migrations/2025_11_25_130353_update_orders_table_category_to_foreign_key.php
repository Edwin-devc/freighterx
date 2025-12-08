<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the old category string column
            $table->dropColumn('category');

            // Add the new category_id foreign key
            $table->foreignId('category_id')->nullable()->after('customer_id')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the foreign key and column
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');

            // Restore the old category string column
            $table->string('category')->after('customer_id');
        });
    }
};
