<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name', 'price', 'stock'
    ];

    public function transaction()
    {
        return $this->hasMany('App\Models\Transaction');
    }

    public function transactionItem()
    {
        return $this->hasMany('App\Models\TransactionItem');
    }
}
