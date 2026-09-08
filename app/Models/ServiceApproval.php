<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'user_id',
        'decision',
        'approved_amount',
        'note',
    ];

    protected $casts = [
        'approved_amount' => 'decimal:2',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
