<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing products
        Product::truncate();

        // Insert products
        Product::create([
            'name' => 'Fresh Apples',
            'description' => 'Crisp and sweet red apples, perfect for snacking',
            'price' => 49.00,
            'image' => 'https://images.pexels.com/photos/102104/pexels-photo-102104.jpeg',
            'category' => 'Fruits',
            'in_stock' => true,
            'rating' => 4.5,
            'reviews' => 23
        ]);// add products

        Product::create([
            'name' => 'Organic Bananas',
            'description' => 'Rich in potassium, perfect for energy boost',
            'price' => 55.00,
            'image' => 'https://images.pexels.com/photos/2238309/pexels-photo-2238309.jpeg',
            'category' => 'Fruits',
            'in_stock' => true,
            'rating' => 4.7,
            'reviews' => 30
        ]);// add products

        Product::create([
            'name' => 'Sweet Oranges',
            'description' => 'Juicy and full of vitamin C',
            'price' => 60.00,
            'image' => 'https://images.pexels.com/photos/161559/pexels-photo-161559.jpeg',
            'category' => 'Citrus',
            'in_stock' => true,
            'rating' => 4.2,
            'reviews' => 18
        ]);// add products
    }
}
