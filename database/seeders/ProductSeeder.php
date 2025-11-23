<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- Seed Brands ---
        $brands = [
            ['name' => 'Apple', 'code_name' => 'apple'],
            ['name' => 'Samsung', 'code_name' => 'samsung'],
            ['name' => 'Nike', 'code_name' => 'nike'],
            ['name' => 'Adidas', 'code_name' => 'adidas'],
            ['name' => 'Sony', 'code_name' => 'sony'],
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(['code_name' => $brand['code_name']], $brand);
        }

        // --- Seed Categories ---
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics'],
            ['name' => 'Clothing', 'slug' => 'clothing'],
            ['name' => 'Food', 'slug' => 'food'],
            ['name' => 'Home & Garden', 'slug' => 'home-garden'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }

        // --- Seed Products ---
        $brandIds = Brand::pluck('id')->all();
        $categoryIds = Category::pluck('id')->all();

        for ($i = 1; $i <= 20; $i++) {
            Product::create([
                'name' => "Sample Product $i",
                'slug' => "sample-product-$i",
                'sku' => "SKU$i",
                'brand' => $brandIds[array_rand($brandIds)],
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'description' => "This is a description for product $i.",
                'measurement' => rand(1, 10),
                'measurement_unit' => ['kg', 'gm', 'ltr', 'pcs'][rand(0, 3)],
                'status' => rand(0, 1),
                'images' => json_encode([
                    'products/default-product-image1.jpg',
                    'products/default-product-image2.jpg'
                ]),
                'sales_count' => rand(0, 100),
            ]);
        }
        $products = Product::all();

        foreach ($products as $product) {
            Inventory::create([
                'product_id' => $product->id,
                'uid' => 'INV-' . strtoupper(uniqid()), // Unique inventory ID
                'quantity' => rand(10, 100), // Random stock
                'cost_price' => rand(50, 500), // Example cost price
                'sell_price' => rand(100, 1000), // Example selling price
                'location' => 'Warehouse ' . rand(1, 3),
                'status' => 'active',
                'sell_order' => rand(1, 100), // Order for selling priority
            ]);
        }
    }

}
