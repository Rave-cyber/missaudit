<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
      Schema::create('orders', function (Blueprint $table) {
    $table->id(); // Auto-increment primary key
    $table->string('order_name'); // Order name (required)
    $table->decimal('weight', 8, 2); // Weight of the order (required)
    $table->date('date'); // Date of the order (required)
    $table->string('service_type'); // Service type (required)
    $table->string('status')->default('Pending'); // Status (default to "Pending")
    $table->string('payment_method'); // Payment method (required)
    $table->decimal('amount', 10, 2); // Amount (required)
    $table->text('special_instructions')->nullable(); // Special instructions (optional)
    $table->string('payment_status')->default('pending'); // Payment status (default to "pending")
    $table->boolean('is_archived')->default(false); // Archive flag (default to false)
    $table->timestamps(); // Timestamps for created_at and updated_at
});


    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
