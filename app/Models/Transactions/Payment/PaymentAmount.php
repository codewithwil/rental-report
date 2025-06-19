<?php

namespace App\Models\Transactions\Payment;

use Illuminate\Database\Eloquent\Model;

class PaymentAmount extends Model
{
    const TYPE_MASUK      = 1;
    const TYPE_KElUAR     = 2;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    protected $table      = 'payment_amounts';
    protected $primaryKey = 'payAmountId';
    protected $fillable   = [
        'payable_id', 'payable_type', 'type', 'amount', 'status'
    ];

    public function payable(){return $this->morphTo();}
}
