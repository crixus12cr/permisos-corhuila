<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'request_date',
        'day',
        'start_hour',
        'end_hour',
        'commitment',
        'observations',
        'autorization_user',
        'autorization_boss',
        'autorization_hr',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
