<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'check_in_start',
        'check_in_end',
        'check_out_start', 
        'check_out_end'
    ];

    protected $casts = [
        'check_in_start' => 'datetime:H:i',
        'check_in_end' => 'datetime:H:i',
        'check_out_start' => 'datetime:H:i',
        'check_out_end' => 'datetime:H:i'
    ];

    // Tambahkan relasi ke users
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
