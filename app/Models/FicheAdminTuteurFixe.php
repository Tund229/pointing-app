<?php

namespace App\Models;

use App\Models\User;
use App\Models\TuteurFix;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FicheAdminTuteurFixe extends Model
{
    use HasFactory;

    protected $fillable = [
        'tuteur_fixe_id',
        'admin_id',
        'amount',
        'total_hours',
        'month',
        'file_path',
        'code',
        'state',
    ];

    public function tuteurFixe()
    {
        return $this->belongsTo(TuteurFix::class, 'tuteur_fixe_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
