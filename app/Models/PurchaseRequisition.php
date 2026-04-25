<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseRequisition extends Model
{
    use HasFactory;

    public const PURPOSES = [
        'Repair',
        'New Installation',
        'Replacement',
        'Maintenance',
        'Project Work',
        'Emergency Work',
        'Stock Refill',
        'Office Use',
        'Colony Use',
        'Production Use',
    ];

    public const STATUSES = [
        'Pending',
        'Approved',
        'Rejected',
        'Converted to PO',
    ];

    protected $fillable = [
        'pr_no',
        'pr_date',
        'category_id',
        'item_id',
        'required_qty',
        'purpose',
        'required_for',
        'requested_by',
        'remarks',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'pr_date' => 'date',
            'required_qty' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $purchaseRequisition): void {
            if (blank($purchaseRequisition->pr_no)) {
                $purchaseRequisition->pr_no = self::generateNumber();
            }
        });
    }

    public static function generateNumber(): string
    {
        $nextId = (self::max('id') ?? 0) + 1;

        return 'PR-'.now()->format('Ymd').'-'.str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function stockReceipts(): HasMany
    {
        return $this->hasMany(StockReceipt::class);
    }

    public function stockIssues(): HasMany
    {
        return $this->hasMany(StockIssue::class);
    }
}
