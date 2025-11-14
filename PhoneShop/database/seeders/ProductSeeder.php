<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Seed bảng products từ config/phone_presets.php
     */
    public function run(): void
    {
        // Lấy brands từ config
        $brands = config('phone_presets.brands', []);

        if (empty($brands)) {
            $this->command?->warn('config("phone_presets.brands") đang rỗng, bỏ qua ProductSeeder.');
            return;
        }

        foreach ($brands as $brandKey => $brandData) {
            $brandLabel   = $brandData['label'] ?? ucfirst($brandKey);
            $categoryName = $brandData['default_category_name'] ?? null;

            // Tìm category theo tên (ví dụ "Điện thoại")
            $categoryId = null;
            if ($categoryName) {
                $category = Category::where('name', $categoryName)->first();
                if ($category) {
                    $categoryId = $category->id;
                }
            }

            // Nếu không tìm thấy, fallback category đầu tiên (để có gì đó mà gán)
            if (!$categoryId) {
                $fallback = Category::first();
                if ($fallback) {
                    $categoryId = $fallback->id;
                    $this->command?->warn(
                        "Không tìm thấy category '{$categoryName}', dùng tạm category ID {$categoryId} ({$fallback->name})."
                    );
                } else {
                    $this->command?->error('Không có category nào trong DB. Hãy seed categories trước rồi chạy lại ProductSeeder.');
                    return;
                }
            }

            $models = $brandData['models'] ?? [];
            foreach ($models as $modelKey => $modelData) {

                $name  = $modelData['name']  ?? null;
                $price = $modelData['price'] ?? null;

                if (!$name || $price === null) {
                    $this->command?->warn("Bỏ qua model '{$modelKey}' của '{$brandLabel}' do thiếu name hoặc price.");
                    continue;
                }

                // Tránh seed trùng: nếu đã có sản phẩm cùng name thì bỏ qua
                $exists = Product::where('name', $name)->first();
                if ($exists) {
                    $this->command?->line("Đã tồn tại sản phẩm '{$name}', bỏ qua.");
                    continue;
                }

                $stock     = $modelData['default_stock'] ?? 0;
                $thumbnail = $modelData['thumbnail']     ?? null;
                $specs     = $modelData['specs']         ?? [];

                $slug = Str::slug($name);

                $productData = [
                    'name'        => $name,
                    'slug'        => $slug,        // bảng products của bạn đang có slug (dùng cho route sản phẩm)
                    'category_id' => $categoryId,
                    'price'       => $price,
                    'stock'       => $stock,
                    'brand'       => $brandLabel,
                    'thumbnail'   => $thumbnail,   // hiện tại có field thumbnail trong admin view
                    'specs'       => $specs,       // nhớ trong Product model có $casts['specs'] = 'array';
                ];

                Product::create($productData);

                $this->command?->info("Đã tạo sản phẩm: {$name}");
            }
        }
    }
}
