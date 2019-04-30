<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id', 'product_id', 'qty'
    ];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function getPriceAttribute($value)
    {
        return $this->product->price;
    }

    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction');
    }
}
