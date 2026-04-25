<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_no',
        'issue_date',
        'store_id',
        'category_id',
        'item_id',
        'purchase_requisition_id',
        'purchase_order_id',
        'available_qty',
        'issue_qty',
        'issued_to_person',
        'department_location',
        'used_at',
        'usage_purpose',
        'handover_name',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'available_qty' => 'decimal:2',
            'issue_qty' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $stockIssue): void {
            if (blank($stockIssue->issue_no)) {
                $stockIssue->issue_no = self::generateNumber();
            }
        });
    }

    public static function generateNumber(): string
    {
        $nextId = (self::max('id') ?? 0) + 1;

        return 'ISS-'.now()->format('Ymd').'-'.str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function purchaseRequisition(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
