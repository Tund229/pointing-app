<?php

namespace App\Models;

use App\Models\User;
use App\Models\PaySlips;
use App\Models\TuteurFix;
use App\Models\FicheAdminTuteurFixe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'amount',
        'externalTransactionId',
        'status',
        'success',
        'transactionFee',
        'transactionCommission',
        'transactionId',
        'transactionType',
        'previousBalance',
        'currentBalance',
        'user_id',
        'paye_by',
        'pay_slip_id',
        'pay_slip_admin_id',
        'tuteur_fixe_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payeBy()
    {
        return $this->belongsTo(User::class, 'paye_by');
    }

    public function paySlip()
    {
        return $this->belongsTo(PaySlips::class, 'pay_slip_id');
    }

    public function paySlipAdmin()
    {
        return $this->belongsTo(FicheAdminTuteurFixe::class, 'pay_slip_admin_id');
    }


    public function tuteurFixe()
    {
        return $this->belongsTo(TuteurFix::class, 'tuteur_fixe_id');
    }


}
