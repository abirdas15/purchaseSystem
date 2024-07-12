<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::truncate();
        $productData = [
            ['name' => 'Orange Paint Card', "description" => "Some quick example text to build on the card title and make up the bulk of the card's content.", "price" => 50, "image" => '2.jpg', "created_at" => now(), "updated_at" => now()],
            ['name' => 'Tea Cup art', "description" => "Some quick example text to build on the card title and make up the bulk of the card's content.", "price" => 35, "image" => '5.jpg', "created_at" => now(), "updated_at" => now()],
            ['name' => 'Leg tattoo paint', "description" => "Some quick example text to build on the card title and make up the bulk of the card's content.", "price" => 65, "image" => '4.jpg', "created_at" => now(), "updated_at" => now()],
        ];
        Product::insert($productData);
    }
}
