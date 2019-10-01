<?php

use App\Product;
use App\Customer;
use App\Transaction;
use Illuminate\Database\Seeder;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transaction = Transaction::create([
            'customer_id' => Customer::all()->random()->id,
        ]);
        $transactionItems = [
            'product_id' => Product::all()->random()->id,
        ];
        $transaction->product()->attach($transactionItems, ['qty' => 5]);
    }
}
