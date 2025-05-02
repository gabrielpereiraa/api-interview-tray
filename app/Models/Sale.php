<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'seller_id',
        'amount',
        'commission',
        'made_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission' => 'decimal:2',
        'made_at' => 'datetime'
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getMadeAtFormattedAttribute()
    {
        return Carbon::parse($this->made_at)->format('d/m/Y H:i');
    }

    public function getAmountFormattedAttribute()
    {
        return number_format($this->amount, 2, ',', '.');
    }

    public function getCommissionFormattedAttribute()
    {
        return number_format($this->commission, 2, ',', '.');
    }
}
