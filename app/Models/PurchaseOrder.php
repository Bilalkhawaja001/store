<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    public const STATUSES = [
        'Pending',
        'Partial Received',
        'Fully Received',
    ];

    protected $fillable = [
        'po_no',
        'po_date',
        'purchase_requisition_id',
        'vendor_name',
        'item_id',
        'ordered_qty',
        'unit_rate',
        'total_amount',
        'status',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'po_date' => 'date',
            'ordered_qty' => 'decimal:2',
            'unit_rate' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $purchaseOrder): void {
            if (blank($purchaseOrder->po_no)) {
                $purchaseOrder->po_no = self::generateNumber();
            }

            $purchaseOrder->total_amount = (float) $purchaseOrder->ordered_qty * (float) $purchaseOrder->unit_rate;
        });

        static::updating(function (self $purchaseOrder): void {
            $purchaseOrder->total_amount = (float) $purchaseOrder->ordered_qty * (float) $purchaseOrder->unit_rate;
        });
    }

    public static function generateNumber(): string
    {
        $nextId = (self::max('id') ?? 0) + 1;

        return 'PO-'.now()->format('Ymd').'-'.str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
    }

    public function purchaseRequisition(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function stockReceipts(): HasMany
    {
        return $this->hasMany(StockReceipt::class);
    }

    public function totalReceivedQty(): float
    {
        return (float) $this->stockReceipts()->sum('received_qty');
    }

    public function pendingQty(): float
    {
        return max((float) $this->ordered_qty - $this->totalReceivedQty(), 0);
    }

    public function refreshReceiptStatus(): void
    {
        $received = $this->totalReceivedQty();
        $this->status = $received <= 0
            ? 'Pending'
            : ($received < (float) $this->ordered_qty ? 'Partial Received' : 'Fully Received');
        $this->save();
    }
}
