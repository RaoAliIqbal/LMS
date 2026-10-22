<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLogs extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'amount','response_code','transaction_id', 'auth_id','quantity','message_code','name_on_card','status','user_id','courses'
//    ];

    protected $guarded = [];

}
