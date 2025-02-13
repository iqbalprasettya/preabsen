<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveQuota extends Model
{
    protected $fillable = [
        'user_id',
        'year',
        'annual_quota',
        'used_quota',
        'remaining_quota'
    ];

    protected $casts = [
        'year' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
