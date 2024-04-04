<?php

namespace App\Models;

use App\Models\Course;
use App\Models\Promotion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pointing extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_date',
        'start_time',
        'end_time',
        'course_id',
        'promotion_id',
        'state',
        'user_id',
        'comment',
        'reason',

    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
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

    public function diffInHours()
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        $difference =  $end->diff($start);
        $diffHours = $difference->h;
        $totalMinutes = ($difference->h * 60) + $difference->i;
        $nombreDe30Min = floor($totalMinutes / 30);
        return $nombreDe30Min;
    }

    public function price_per_hour()
    {
        if ($this->course) {
            return $this->course->price_per_hour;
        }
        return 0;
    }
}
