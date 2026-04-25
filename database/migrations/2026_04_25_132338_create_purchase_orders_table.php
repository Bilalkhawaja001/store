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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_no')->unique();
            $table->date('po_date');
            $table->foreignId('purchase_requisition_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('vendor_name');
            $table->foreignId('item_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('ordered_qty', 14, 2);
            $table->decimal('unit_rate', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->string('status')->default('Pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
