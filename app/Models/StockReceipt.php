<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockReceipt extends Model
{
    use HasFactory;

    public const ACQUISITION_TYPES = [
        'Purchase',
        'Transfer In',
        'Loan',
        'Donation',
        'Return',
        'Adjustment',
        'Other',
    ];

    public const LOAN_STATUSES = [
        'On Loan',
        'Returned',
        'Overdue',
    ];

    protected $fillable = [
        'grn_no',
        'receive_date',
        'purchase_order_id',
        'purchase_requisition_id',
        'item_id',
        'store_id',
        'ordered_qty',
        'already_received_qty',
        'received_qty',
        'pending_qty',
        'acquisition_type',
        'acquisition_reference',
        'lender_name',
        'loan_due_date',
        'loan_status',
        'source_store_id',
        'challan_no',
        'received_by',
        'handover_to',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'receive_date' => 'date',
            'ordered_qty' => 'decimal:2',
            'already_received_qty' => 'decimal:2',
            'received_qty' => 'decimal:2',
            'pending_qty' => 'decimal:2',
            'loan_due_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $stockReceipt): void {
            if (blank($stockReceipt->grn_no)) {
                $stockReceipt->grn_no = self::generateNumber();
            }
        });

        static::saved(function (self $stockReceipt): void {
            $stockReceipt->purchaseOrder->refreshReceiptStatus();
        });

        static::deleted(function (self $stockReceipt): void {
            $stockReceipt->purchaseOrder?->refreshReceiptStatus();
        });
    }

    public static function generateNumber(): string
    {
        $nextId = (self::max('id') ?? 0) + 1;

        return 'GRN-'.now()->format('Ymd').'-'.str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function purchaseRequisition(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function sourceStore(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'source_store_id');
    }
}
