<?php

namespace App\Models;

use App\Models\User;
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
        return $this->belongsTo(PaySlip::class, 'pay_slip_id');
    }
}
