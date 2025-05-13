<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('transactions');
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->decimal('weight', 8, 2);
            $table->date('order_date');
            $table->json('services');
            $table->string('payment_method');
            $table->decimal('amount', 8, 2);
            $table->text('special_instructions')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('pricing_details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};