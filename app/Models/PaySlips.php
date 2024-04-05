<?php

namespace App\Models;

use App\Models\Course;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaySlips extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_id',
        'working_hours',
        'amount',
        'state',
        'month',
        'total_hours',
        'code',
    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Relation avec le cours
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // Relation avec la promotion
    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }

    public function paiment()
    {
        return $this->hasOne(Paiement::class);
    }

}
