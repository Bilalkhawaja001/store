<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'name',
        'category_id',
        'unit',
        'minimum_stock_level',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'minimum_stock_level' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function purchaseRequisitions(): HasMany
    {
        return $this->hasMany(PurchaseRequisition::class);
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
