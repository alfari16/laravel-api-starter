<?php

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Database\Seeder;

class TransactionItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransactionItem::create([
            'transaction_id' => Transaction::all()->random()->id,
            'product_id' => Product::all()->random()->id,
            'qty' => 10,
        ]);
        
        TransactionItem::create([
            'transaction_id' => Transaction::all()->random()->id,
            'product_id' => Product::all()->random()->id,
            'qty' => 5,
        ]);
    }
}
