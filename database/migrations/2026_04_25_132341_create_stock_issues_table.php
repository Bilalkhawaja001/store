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
        Schema::create('stock_issues', function (Blueprint $table) {
            $table->id();
            $table->string('issue_no')->unique();
            $table->date('issue_date');
            $table->foreignId('store_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('item_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('purchase_requisition_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('purchase_order_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->decimal('available_qty', 14, 2)->default(0);
            $table->decimal('issue_qty', 14, 2);
            $table->string('issued_to_person');
            $table->string('department_location')->nullable();
            $table->string('used_at')->nullable();
            $table->string('usage_purpose');
            $table->string('handover_name')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_issues');
    }
};
