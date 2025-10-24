<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

// database/seeders/CategorySeeder.php
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $cats = ['iPhone', 'Samsung', 'Xiaomi', 'OPPO', 'Vivo', 'Realme', 'Nokia', 'Google Pixel', 'Huawei', 'Asus'];
        foreach ($cats as $c) {
            Category::firstOrCreate([
                'slug' => Str::slug($c),
            ], ['name' => $c]);
        }
    }
}
