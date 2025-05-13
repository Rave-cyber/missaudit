<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Add missing columns
            $table->string('customer_name')->after('id');
            $table->decimal('weight', 8, 2)->after('customer_name');
            $table->date('order_date')->after('weight');
            $table->json('services')->after('order_date');
            $table->decimal('amount', 8, 2)->after('payment_method');
            $table->text('special_instructions')->nullable()->after('amount');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('special_instructions');
            $table->json('pricing_details')->nullable()->after('user_id');

            // If order_id is not needed, you can drop it (optional)
            // $table->dropColumn('order_id');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'weight',
                'order_date',
                'services',
                'amount',
                'special_instructions',
                'user_id',
                'pricing_details'
            ]);
            // If you dropped order_id, re-add it here (optional)
            // $table->unsignedBigInteger('order_id')->after('id');
        });
    }
};