<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('stock_receipts')) {
            return;
        }

        $hasSource = Schema::hasColumn('stock_receipts', 'acquisition_source');
        $hasNotes = Schema::hasColumn('stock_receipts', 'acquisition_notes');
        $hasCost = Schema::hasColumn('stock_receipts', 'acquisition_cost');

        if ($hasSource || $hasNotes) {
            DB::table('stock_receipts')
                ->select(['id', 'lender_name', 'remarks', 'acquisition_source', 'acquisition_notes'])
                ->orderBy('id')
                ->chunkById(100, function ($receipts) use ($hasSource, $hasNotes): void {
                    foreach ($receipts as $receipt) {
                        $updates = [];

                        if ($hasSource && empty($receipt->lender_name) && ! empty($receipt->acquisition_source)) {
                            $updates['lender_name'] = $receipt->acquisition_source;
                        }

                        if ($hasNotes && ! empty($receipt->acquisition_notes)) {
                            $updates['remarks'] = blank($receipt->remarks)
                                ? $receipt->acquisition_notes
                                : $receipt->remarks."\n".$receipt->acquisition_notes;
                        }

                        if ($updates !== []) {
                            DB::table('stock_receipts')->where('id', $receipt->id)->update($updates);
                        }
                    }
                });
        }

        Schema::table('stock_receipts', function (Blueprint $table) use ($hasSource, $hasNotes, $hasCost) {
            if ($hasSource) {
                $table->dropColumn('acquisition_source');
            }

            if ($hasCost) {
                $table->dropColumn('acquisition_cost');
            }

            if ($hasNotes) {
                $table->dropColumn('acquisition_notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_receipts', function (Blueprint $table) {
            if (! Schema::hasColumn('stock_receipts', 'acquisition_source')) {
                $table->string('acquisition_source')->nullable()->after('acquisition_type');
            }

            if (! Schema::hasColumn('stock_receipts', 'acquisition_cost')) {
                $table->decimal('acquisition_cost', 14, 2)->nullable()->after('acquisition_reference');
            }

            if (! Schema::hasColumn('stock_receipts', 'acquisition_notes')) {
                $table->text('acquisition_notes')->nullable()->after('acquisition_cost');
            }
        });
    }
};
