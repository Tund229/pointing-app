<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'promotion_id',
        'user_id',
        'support_file',
        'comment',
        'state',
        'user_id',
    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
}
