<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_name',
        'currency',
        'status',
        'issued_at',
        'due_at',
        'discount',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'issued_at' => 'datetime',
            'due_at' => 'datetime',
            'discount' => 'float',
        ];
    }

    /**
     * @return HasMany
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function getTotalAmountAttribute(): float
    {
        $total = $this->articles->sum(fn($a) => $a->totalAmount);
        return round($total, 2);
    }

    public function getDiscountAmountAttribute(): float
    {
        $discount = $this->totalAmount * ($this->discount/100);
        return round($discount, 2);
    }
    public function getDiscountedTotalAmountAttribute(): float
    {
        $total = $this->totalAmount - $this->discountAmount;
        return round($total, 2);
    }
}
