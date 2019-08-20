<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurrentAccount extends Model
{
    protected $fillable = ['user_id', 'balance'];
}
