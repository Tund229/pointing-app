<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Code extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'expire_at',
        'verified',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expired()
    {
        return $this->expire_at < Carbon::now();
    }

}
