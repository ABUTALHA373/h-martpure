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
                'brand_id' => $brandIds[array_rand($brandIds)],
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

            $initialQty = rand(20, 150);

            Inventory::create([
                'product_id' => $product->id,

                // Batch identity
                'batch_uid' => 'BATCH-' . strtoupper(uniqid()),
                'purchase_date' => now()->subDays(rand(1, 120)),

                // Quantities
                'initial_quantity' => $initialQty,
                'remaining_quantity' => $initialQty,
                'reserved_quantity' => 0,

                // Cost breakdown (per unit)
                'supplier_cost' => rand(50, 300),
                'transport_cost' => rand(5, 30),
                'handling_cost' => rand(3, 20),
                'storage_cost' => rand(2, 15),
                'other_cost' => rand(0, 10),

                // Taxes (per unit)
                'import_tax' => rand(0, 20),
                'vat_tax' => rand(0, 15),
                'other_tax' => rand(0, 10),

                // Pricing
                'sell_price' => rand(120, 800),
                'min_sell_price' => rand(100, 150),

                // Control
                'status' => 'active',
                'sell_order' => 0, // IMPORTANT: keep FIFO clean
                'store_location' => 'Warehouse ' . rand(1, 3),

                'created_at' => now()->subDays(rand(1, 120)),
                'updated_at' => now(),
            ]);
        }

    }

}
