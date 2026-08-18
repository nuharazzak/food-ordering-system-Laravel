<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds payment-related fields to the orders table for PayHere integration.
     * payment_method   : how the customer chose to pay
     * payment_status   : authoritative payment state (updated by PayHere server notification)
     * payment_reference: PayHere transaction ID / order reference returned on callback
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_method', ['cash_on_delivery', 'payhere'])
                  ->default('cash_on_delivery')
                  ->after('status');

            $table->enum('payment_status', ['pending', 'paid', 'failed', 'cancelled'])
                  ->default('pending')
                  ->after('payment_method');

            $table->string('payment_reference')->nullable()
                  ->after('payment_status')
                  ->comment('PayHere payment_id / transaction ID returned via server notification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_status', 'payment_reference']);
        });
    }
};
