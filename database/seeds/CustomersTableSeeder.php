<?php

use App\Customer;
use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::create([
            'name' => 'Jhon',
            'email' => 'dummy@gmail.com',
            'gender' => 'Men',
            'phone' => '+6282970845670',
        ]);
    }
}
