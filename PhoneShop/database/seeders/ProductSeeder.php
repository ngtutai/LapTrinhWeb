<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

// database/seeders/ProductSeeder.php
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brands = ['Apple', 'Samsung', 'Xiaomi', 'OPPO', 'Vivo', 'Realme', 'Google', 'Asus', 'Nokia', 'Huawei'];
        $cats = Category::all();
        foreach ($brands as $i => $brand) {
            $cat = $cats[$i % $cats->count()];
            for ($j = 1; $j <= 2; $j++) {
                $name = "$brand Phone $j";
                Product::create([
                    'category_id' => $cat->id,
                    'name' => $name,
                    'slug' => Str::slug($name.'-'.Str::random(4)),
                    'price' => fake()->numberBetween(3000000, 35000000),
                    'stock' => fake()->numberBetween(5, 50),
                    'brand' => $brand,
                    'specs' => [
                        'RAM' => fake()->randomElement([4, 6, 8, 12]).'GB',
                        'ROM' => fake()->randomElement([64, 128, 256, 512]).'GB',
                        'Screen' => fake()->randomElement(['6.1"', '6.5"', '6.7"']),
                        'Battery' => fake()->randomElement([4000, 4500, 5000]).'mAh',
                    ],
                    'thumbnail' => null,
                ]);
            }
        }
    }
}
