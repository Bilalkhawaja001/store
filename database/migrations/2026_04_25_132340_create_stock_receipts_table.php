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
        Schema::create('stock_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('grn_no')->unique();
            $table->date('receive_date');
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('purchase_requisition_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('item_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('store_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('ordered_qty', 14, 2);
            $table->decimal('already_received_qty', 14, 2)->default(0);
            $table->decimal('received_qty', 14, 2);
            $table->decimal('pending_qty', 14, 2)->default(0);
            $table->string('challan_no')->nullable();
            $table->string('received_by');
            $table->string('handover_to')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_receipts');
    }
};
