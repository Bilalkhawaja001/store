<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_receipts', function (Blueprint $table) {
            $table->string('acquisition_type')->nullable()->after('pending_qty');
            $table->string('acquisition_reference')->nullable()->after('acquisition_type');
            $table->string('lender_name')->nullable()->after('acquisition_reference');
            $table->date('loan_due_date')->nullable()->after('lender_name');
            $table->string('loan_status')->nullable()->after('loan_due_date');
            $table->foreignId('source_store_id')->nullable()->after('loan_status')->constrained('stores')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('stock_receipts', 'source_store_id')) {
            Schema::table('stock_receipts', function (Blueprint $table) {
                $table->dropConstrainedForeignId('source_store_id');
            });
        }

        Schema::table('stock_receipts', function (Blueprint $table) {
            $table->dropColumn([
                'acquisition_type',
                'acquisition_reference',
                'lender_name',
                'loan_due_date',
                'loan_status',
            ]);
        });
    }
};
