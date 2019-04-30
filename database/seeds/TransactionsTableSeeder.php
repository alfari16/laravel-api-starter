<?php

use App\Models\Customer;
use App\Models\Transaction;
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
        Transaction::create([
            'customer_id' => Customer::all()->random()->id,
        ]);
    }
}
