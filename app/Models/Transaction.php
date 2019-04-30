<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'customer_id', 
    ];
    
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function transactionItem()
    {
        return $this->hasMany('App\Models\TransactionItem');
    }
    
    public function getCreatedAtAttribute($value)
    {
        return strtotime($value);
    }

    
    public function getUpdatedAtAttribute($value)
    {
        return strtotime($value);
    }

    
}
