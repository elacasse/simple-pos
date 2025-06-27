<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Article;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Seeder;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bills = Bill::factory()->count(50)->create();

        Article::factory()->count(200)->create(['bill_id' => fn() => $bills->random()]);
//        $bill = new Bill([
//            'customer_name' => 'Benny Hill',
//            'currency' => 'CAD',
//            'status' => 'draft',
//            'issued_at' => now(),
//            'due_at' => now()->addWeek(),
//            'discount' => 20,
//        ]);
//
//        $bill->save();
//
//        $article1 = new Article([
//            'description' => 'M5 Bolts',
//            'quantity' => 5,
//            'unit_price' => 0.50,
//        ]);
//
//        $article2 = new Article([
//            'description' => 'M5 Nuts',
//            'quantity' => 5,
//            'unit_price' => 0.65,
//        ]);
//
//        $bill->articles()->saveMany([$article1, $article2]);
    }
}
