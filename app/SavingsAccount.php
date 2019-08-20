<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SavingsAccount extends Model
{
    protected $fillable = ['user_id', 'balance'];
}
