<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $user = \App\Models\User::factory()->hasOrders(1)->create();

       $order = $user->orders->first();

       foreach (\App\Models\Product::orderByRaw('RANDOM()')->take(4)->get() as $prod) {
            $amount = rand(1, 5);

            $order->items()->create([
                'product_id' => $prod->id,
                'amount' => $amount,
                'order_value' => $prod->price * $amount
            ]);
       }

    }
}


