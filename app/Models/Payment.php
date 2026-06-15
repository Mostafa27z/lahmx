<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'method',
        'transaction_id',
        'amount',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getMethodLabelAttribute(): string
    {
        return match ($this->method) {
            'visa' => 'بطاقة ائتمانية (Visa)',
            'tabby' => 'تابي (Tabby)',
            'tamara' => 'تمارا (Tamara)',
            default => $this->method,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'قيد الانتظار',
            'success' => 'ناجحة',
            'failed' => 'فاشلة',
            default => $this->status,
        };
    }
}
