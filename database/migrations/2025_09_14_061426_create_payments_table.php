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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->decimal('amount', 10, 2);
            $table->decimal('usd_amount', 10, 2);
            $table->string('currency');
            $table->string('reference_no');
            $table->string('payment_date');
            $table->foreignId('invoice_id')->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('is_processed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
