<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'check_in',
        'check_out', 
        'check_in_photo',
        'check_out_photo',
        'check_in_address',
        'check_out_address',
        'check_in_latitude',
        'check_in_longitude',
        'check_out_latitude', 
        'check_out_longitude',
        'status',
        'notes'
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'check_in_latitude' => 'decimal:8',
        'check_in_longitude' => 'decimal:8',
        'check_out_latitude' => 'decimal:8',
        'check_out_longitude' => 'decimal:8',
        'status' => 'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
