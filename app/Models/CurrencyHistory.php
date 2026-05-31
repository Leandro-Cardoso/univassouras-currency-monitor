<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyHistory extends Model
{
    protected $table = 'currency_histories';
    protected $fillable = ['user_id', 'from_currency', 'to_currency', 'bid_value', 'ask_value', 'consulted_at'];
}
