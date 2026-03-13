<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'saving_percent',
        'investment_percent',
        'spending_percent',
    ];

    protected $casts = [
        'saving_percent'     => 'decimal:2',
        'investment_percent' => 'decimal:2',
        'spending_percent'   => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate allocation amounts based on a given total.
     */
    public function calculate(float $total): array
    {
        return [
            'saving'     => round($total * $this->saving_percent / 100, 2),
            'investment' => round($total * $this->investment_percent / 100, 2),
            'spending'   => round($total * $this->spending_percent / 100, 2),
        ];
    }

    /**
     * Total percent should always equal 100.
     */
    public function getTotalPercentAttribute(): float
    {
        return (float) $this->saving_percent
             + (float) $this->investment_percent
             + (float) $this->spending_percent;
    }
}
