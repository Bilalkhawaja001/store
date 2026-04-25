<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_code',
        'name',
        'location',
        'incharge_name',
        'contact_no',
        'remarks',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
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
