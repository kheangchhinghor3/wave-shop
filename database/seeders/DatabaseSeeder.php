<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   public function run(): void 
   {
    // 1. Categories (slug => name)
    $categories = [
        'tech' => 'Tech',
        'albums' => 'Albums',
        'fashion' => 'Fashion',
        'accessories' => 'Accessories',
        'photocards' => 'Photocards',
        'limited-edition' => 'Limited Edition',
    ];

    $categoryIds = [];

    foreach ($categories as $slug => $name){
        $category = Category::updateOrCreate(
            ['slug' => $slug],
            ['name' => $name]
        );

        $categoryIds[$slug] = $category->id; 
    }

    // 2.Products (all fictional WAVE SHOP items)
     // 2. Products (all fictional WAVE SHOP items)
        $products = [
            [
                'category' => 'tech',
                'name' => 'Wave Buds Pink Star Edition',
                'slug' => 'wave-buds-pink-star',
                'description' => 'Wireless earbuds with a soft pink star-shaped case. Only 100 units available.',
                'price' => 89.90,
                'stock' => 100,
                'collection' => 'Pink Star',
                'is_limited' => true,
                'is_featured' => true,
                'drop_ends_at' => now()->addDays(7),
            ],
            [
                'category' => 'tech',
                'name' => 'Wave Buds Midnight',
                'slug' => 'wave-buds-midnight',
                'description' => 'Black wireless earbuds with deep bass and 24-hour battery life.',
                'price' => 69.90,
                'stock' => 250,
                'collection' => 'Midnight',
                'is_limited' => false,
                'is_featured' => true,
                'drop_ends_at' => null,
            ],
            [
                'category' => 'tech',
                'name' => 'Wave Beat Headphones',
                'slug' => 'wave-beat-headphones',
                'description' => 'Over-ear wireless headphones with noise cancelling and a soft pink headband.',
                'price' => 129.00,
                'stock' => 80,
                'collection' => 'Midnight',
                'is_limited' => false,
                'is_featured' => false,
                'drop_ends_at' => null,
            ],
            [
                'category' => 'tech',
                'name' => 'Holo Star Phone Case',
                'slug' => 'holo-star-phone-case',
                'description' => 'Shock-proof phone case with a holographic star pattern.',
                'price' => 19.90,
                'stock' => 300,
                'collection' => 'Pink Star',
                'is_limited' => false,
                'is_featured' => false,
                'drop_ends_at' => null,
            ],
            [
                'category' => 'albums',
                'name' => 'Neon Dreams Album (Pink Ver.)',
                'slug' => 'neon-dreams-album-pink',
                'description' => 'Fictional debut album with a photobook, poster and one random photocard.',
                'price' => 24.90,
                'stock' => 500,
                'collection' => 'Neon Dreams',
                'is_limited' => false,
                'is_featured' => true,
                'drop_ends_at' => null,
            ],
            [
                'category' => 'albums',
                'name' => 'Neon Dreams Album (Black Ver.)',
                'slug' => 'neon-dreams-album-black',
                'description' => 'The black version of the Neon Dreams album with a different cover and photobook.',
                'price' => 24.90,
                'stock' => 500,
                'collection' => 'Neon Dreams',
                'is_limited' => false,
                'is_featured' => false,
                'drop_ends_at' => null,
            ],
            [
                'category' => 'fashion',
                'name' => 'Wave Oversized Hoodie',
                'slug' => 'wave-oversized-hoodie',
                'description' => 'Soft oversized hoodie in light gray with a small embroidered wave logo.',
                'price' => 59.90,
                'stock' => 120,
                'collection' => 'Midnight',
                'is_limited' => false,
                'is_featured' => true,
                'drop_ends_at' => null,
            ],
            [
                'category' => 'fashion',
                'name' => 'Stage Lights T-Shirt',
                'slug' => 'stage-lights-t-shirt',
                'description' => 'White cotton t-shirt with a pink stage-lights print.',
                'price' => 29.90,
                'stock' => 200,
                'collection' => 'Neon Dreams',
                'is_limited' => false,
                'is_featured' => false,
                'drop_ends_at' => null,
            ],
            [
                'category' => 'fashion',
                'name' => 'Midnight Crew Cap',
                'slug' => 'midnight-crew-cap',
                'description' => 'Black cap with a pink star on the side and an adjustable strap.',
                'price' => 24.90,
                'stock' => 150,
                'collection' => 'Midnight',
                'is_limited' => false,
                'is_featured' => false,
                'drop_ends_at' => null,
            ],
            [
                'category' => 'fashion',
                'name' => 'Wave Mini Crossbody Bag',
                'slug' => 'wave-mini-crossbody-bag',
                'description' => 'Small black crossbody bag with a pink zipper and a star charm.',
                'price' => 44.90,
                'stock' => 90,
                'collection' => 'Pink Star',
                'is_limited' => false,
                'is_featured' => false,
                'drop_ends_at' => null,
            ],
            [
                'category' => 'accessories',
                'name' => 'Pink Star Keyring',
                'slug' => 'pink-star-keyring',
                'description' => 'Metal star keyring with pink enamel.',
                'price' => 9.90,
                'stock' => 400,
                'collection' => 'Pink Star',
                'is_limited' => false,
                'is_featured' => false,
                'drop_ends_at' => null,
            ],
            [
                'category' => 'accessories',
                'name' => 'Wave Light Stick',
                'slug' => 'wave-light-stick',
                'description' => 'Fictional fan light stick with 16 colors and a star-shaped top.',
                'price' => 39.90,
                'stock' => 60,
                'collection' => 'Neon Dreams',
                'is_limited' => false,
                'is_featured' => true,
                'drop_ends_at' => null,
            ],
            [
                'category' => 'photocards',
                'name' => 'Neon Dreams Photocard Set A',
                'slug' => 'neon-dreams-photocard-set-a',
                'description' => 'Set of 8 fictional photocards in a collectible sleeve.',
                'price' => 14.90,
                'stock' => 350,
                'collection' => 'Neon Dreams',
                'is_limited' => false,
                'is_featured' => false,
                'drop_ends_at' => null,
            ],
            [
                'category' => 'photocards',
                'name' => 'Wave Photocard Binder',
                'slug' => 'wave-photocard-binder',
                'description' => 'A5 binder with 20 pages for storing photocards.',
                'price' => 22.90,
                'stock' => 110,
                'collection' => 'Pink Star',
                'is_limited' => false,
                'is_featured' => false,
                'drop_ends_at' => null,
            ],
            [
                'category' => 'limited-edition',
                'name' => 'Aurora Holo Hoodie',
                'slug' => 'aurora-holo-hoodie',
                'description' => 'Limited drop hoodie with a color-shifting holographic print. Only 50 pieces.',
                'price' => 79.90,
                'stock' => 50,
                'collection' => 'Aurora',
                'is_limited' => true,
                'is_featured' => true,
                'drop_ends_at' => now()->addDays(3),
            ],
            [
                'category' => 'limited-edition',
                'name' => 'Starlight Varsity Jacket',
                'slug' => 'starlight-varsity-jacket',
                'description' => 'Limited varsity jacket with a stitched star on the back. This drop has sold out.',
                'price' => 99.90,
                'stock' => 0,
                'collection' => 'Aurora',
                'is_limited' => true,
                'is_featured' => false,
                'drop_ends_at' => now()->subDay(),
            ],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'category_id' => $categoryIds[$item['category']],
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                    'collection' => $item['collection'],
                    'is_limited' => $item['is_limited'],
                    'is_featured' => $item['is_featured'],
                    'drop_ends_at' => $item['drop_ends_at'],
                ]
            );
        }
   }
}
