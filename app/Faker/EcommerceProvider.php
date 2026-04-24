<?php

declare(strict_types=1);

namespace App\Faker;

use Faker\Provider\Base;

class EcommerceProvider extends Base
{
    protected static array $categories = [
        'Electronics' => [
            'titles' => ['Smartphone', 'Laptop', 'Smartwatch', 'Headphones', 'Tablet'],
            'brands' => ['Apple', 'Samsung', 'Sony', 'Dell', 'HP', 'Xiaomi', 'Google'],
            'specs' => [
                'Display' => ['6.1 inch', '6.7 inch', '13.3 inch', '15.6 inch', 'AMOLED', 'IPS LCD'],
                'Processor' => ['A16 Bionic', 'Snapdragon 8 Gen 2', 'Intel i7', 'AMD Ryzen 7', 'M2 Chip'],
                'Battery' => ['4000mAh', '5000mAh', '60Wh', '80Wh'],
            ],
            'variants' => [
                'RAM' => ['8GB', '16GB', '32GB'],
                'Storage' => ['128GB', '256GB', '512GB', '1TB'],
                'Color' => ['Space Gray', 'Silver', 'Phantom Black', 'Midnight Blue'],
            ],
        ],
        'Fashion' => [
            'titles' => ['T-Shirt', 'Jeans', 'Sneakers', 'Hoodie', 'Dress'],
            'brands' => ['Nike', 'Adidas', 'Zara', 'H&M', 'Levis', 'Gucci'],
            'specs' => [
                'Material' => ['100% Cotton', 'Polyester', 'Denim', 'Leather'],
                'Gender' => ['Unisex', 'Men', 'Women', 'Kids'],
                'Season' => ['Summer', 'Winter', 'All Season'],
            ],
            'variants' => [
                'Size' => ['S', 'M', 'L', 'XL', 'XXL', '38', '40', '42'],
                'Color' => ['Red', 'Blue', 'Black', 'White', 'Green'],
            ],
        ],
        'Home & Living' => [
            'titles' => ['Coffee Table', 'Sofa', 'Dining Chair', 'Desk Lamp', 'Bookshelf'],
            'brands' => ['IKEA', 'Wayfair', 'Ashley Furniture', 'Herman Miller'],
            'specs' => [
                'Material' => ['Wood', 'Metal', 'Fabric', 'Glass'],
                'Style' => ['Modern', 'Minimalist', 'Industrial', 'Vintage'],
            ],
            'variants' => [
                'Color' => ['Oak', 'Walnut', 'White', 'Black', 'Gray'],
                'Size' => ['Small', 'Medium', 'Large'],
            ],
        ],
    ];

    public function ecommerceCategory(): string
    {
        return static::randomKey(static::$categories);
    }

    public function ecommerceBrand(string $categoryName): string
    {
        return static::randomElement(static::$categories[$categoryName]['brands']);
    }

    public function ecommerceProductTitle(string $categoryName): string
    {
        return static::randomElement(static::$categories[$categoryName]['titles']).' '.static::bothify('??-####');
    }

    public function ecommerceSpecifications(string $categoryName): array
    {
        $specs = [];
        foreach (static::$categories[$categoryName]['specs'] as $key => $values) {
            $specs[$key] = static::randomElement($values);
        }

        return $specs;
    }

    public function ecommerceVariantProperties(string $categoryName): array
    {
        $properties = [];
        $variantTypes = static::$categories[$categoryName]['variants'];

        // Pick 2-3 variant types to mix
        $keys = static::randomElements(array_keys($variantTypes), static::numberBetween(1, count($variantTypes)));

        foreach ($keys as $key) {
            $properties[$key] = static::randomElement($variantTypes[$key]);
        }

        return $properties;
    }
}
