<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'account_type', 'transaction_type', 'access', 'description', 'amount'];
}
