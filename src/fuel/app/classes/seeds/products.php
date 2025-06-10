<?php

class Seeds_Products
{
    public static function run()
    {
        $products = [
            [
                'name' => 'iPhone 15 Pro Max',
                'image_path' => 'https://picsum.photos/id/1011/400/300',
                'price' => 36990000,
                'category_id' => 1,
                'quantity' => 80,
                'description' => 'The iPhone 15 Pro Max features Apple’s most advanced camera system ever, a titanium design, and the blazing-fast A17 Pro chip that handles everything from gaming to photography with ease.',
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'image_path' => 'https://picsum.photos/id/1012/400/300',
                'price' => 33990000,
                'category_id' => 2,
                'quantity' => 120,
                'description' => 'The Samsung Galaxy S24 Ultra delivers a dynamic AMOLED display, a 200MP camera, and top-tier performance powered by the Snapdragon chipset, making it a true flagship contender.',
            ],
            [
                'name' => 'MacBook Pro 16" M3 Max',
                'image_path' => 'https://picsum.photos/id/1013/400/300',
                'price' => 72990000,
                'category_id' => 3,
                'quantity' => 50,
                'description' => 'With a stunning 16-inch Liquid Retina XDR display and the cutting-edge M3 Max chip, the MacBook Pro is designed for professionals who demand high performance in video editing, 3D rendering, and beyond.',
            ],
            [
                'name' => 'Asus ROG Zephyrus G16',
                'image_path' => 'https://picsum.photos/id/1014/400/300',
                'price' => 38990000,
                'category_id' => 3,
                'quantity' => 70,
                'description' => 'A sleek, ultra-powerful gaming laptop featuring an Intel i9 processor and NVIDIA RTX 4080 GPU, delivering smooth frame rates and a high refresh rate display for competitive gaming.',
            ],
            [
                'name' => 'Google Pixel 8 Pro',
                'image_path' => 'https://picsum.photos/id/1015/400/300',
                'price' => 26990000,
                'category_id' => 2,
                'quantity' => 95,
                'description' => 'Combining Google’s best camera AI and an intuitive Android experience, the Pixel 8 Pro offers stunning photos, long battery life, and years of software updates right out of the box.',
            ],
            [
                'name' => 'iPad Pro 13" M4',
                'image_path' => 'https://picsum.photos/id/1016/400/300',
                'price' => 36990000,
                'category_id' => 1,
                'quantity' => 60,
                'description' => 'Apple’s iPad Pro with the M4 chip pushes tablet boundaries, offering a desktop-class performance in a lightweight, ultra-portable form factor, ideal for creatives and professionals on the go.',
            ],
            [
                'name' => 'Dell XPS 15 OLED',
                'image_path' => 'https://picsum.photos/id/1018/400/300',
                'price' => 42990000,
                'category_id' => 3,
                'quantity' => 40,
                'description' => 'With an edge-to-edge OLED display and premium aluminum design, the Dell XPS 15 is a productivity powerhouse that balances aesthetics, speed, and long battery life.',
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'image_path' => 'https://picsum.photos/id/1019/400/300',
                'price' => 8490000,
                'category_id' => 2,
                'quantity' => 180,
                'description' => 'The Sony WH-1000XM5 wireless headphones provide industry-leading noise cancellation, crystal-clear sound quality, and up to 30 hours of battery life with quick charging support.',
            ],
            [
                'name' => 'Apple Watch Ultra 2',
                'image_path' => 'https://picsum.photos/id/1020/400/300',
                'price' => 19990000,
                'category_id' => 1,
                'quantity' => 75,
                'description' => 'Designed for adventurers and athletes, the Apple Watch Ultra 2 features a rugged titanium case, dual-frequency GPS, and up to 72 hours of battery life in Low Power Mode.',
            ],
            [
                'name' => 'JBL Charge 5 Bluetooth Speaker',
                'image_path' => 'https://picsum.photos/id/1021/400/300',
                'price' => 3490000,
                'category_id' => 2,
                'quantity' => 300,
                'description' => 'Compact yet powerful, the JBL Charge 5 offers deep bass, 20 hours of playtime, and IP67 water and dust resistance, making it the perfect speaker for both indoor and outdoor adventures.',
            ],
            [
                'name' => 'Lenovo Legion 7i Gen 9',
                'image_path' => 'https://picsum.photos/id/1022/400/300',
                'price' => 45990000,
                'category_id' => 3,
                'quantity' => 55,
                'description' => 'The Legion 7i Gen 9 is built for performance with Intel’s latest processors, a high-refresh display, and efficient thermal management—ideal for gaming marathons and heavy workloads.',
            ],
        ];

        foreach ($products as &$product) {
            $product['created_at'] = time();
            $product['updated_at'] = time();
            \DB::insert('products')->set($product)->execute();
        }

        echo "✔ Seeded products\n";
    }
}
