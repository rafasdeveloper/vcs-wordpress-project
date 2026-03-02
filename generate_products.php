<?php
/**
 * WordPress Product Generator Script
 * 
 * This script fetches existing products from the database using wp-cli,
 * generates 24 random products per category, and uploads them to WordPress.
 * 
 * Usage: php generate_products.php
 */

// Configuration
$PRODUCTS_PER_CATEGORY = 24;
$SCRIPT_DIR = __DIR__;

// Product templates for different categories
$PRODUCT_TEMPLATES = [
    'clothing' => [
        'names' => ['Vintage T-Shirt', 'Classic Hoodie', 'Denim Jacket', 'Leather Vest', 'Band Tee', 'Graphic Shirt', 'Punk Rock Shirt', 'Metal Band Hoodie', 'Rock Star Jacket', 'Concert Tee'],
        'descriptions' => [
            'High-quality vintage-inspired clothing perfect for any rock concert.',
            'Classic design with premium materials for ultimate comfort.',
            'Authentic style that captures the essence of rock culture.',
            'Handcrafted with attention to detail and rock aesthetics.',
            'Limited edition piece with unique design elements.'
        ],
        'price_range' => [15.00, 89.99]
    ],
    'accessories' => [
        'names' => ['Leather Bracelet', 'Metal Chain', 'Studded Belt', 'Rock Bandana', 'Concert Wristband', 'Guitar Pick Necklace', 'Skull Ring', 'Spiked Collar', 'Band Patch', 'Concert Ticket Holder'],
        'descriptions' => [
            'Authentic rock accessory that completes any outfit.',
            'Handcrafted with premium materials for lasting quality.',
            'Unique design that stands out in any crowd.',
            'Perfect addition to your rock collection.',
            'Limited edition accessory with exclusive styling.'
        ],
        'price_range' => [8.00, 45.00]
    ],
    'posters' => [
        'names' => ['Vintage Concert Poster', 'Band Poster', 'Rock Legend Print', 'Concert Memory Poster', 'Classic Rock Art', 'Music Festival Poster', 'Band Photo Print', 'Concert Scene Art', 'Rock History Print', 'Limited Edition Poster'],
        'descriptions' => [
            'High-quality print perfect for any music lover\'s wall.',
            'Vintage-inspired design that captures rock history.',
            'Limited edition poster with authentic rock aesthetics.',
            'Perfect addition to your music memorabilia collection.',
            'Hand-numbered limited edition with certificate of authenticity.'
        ],
        'price_range' => [12.00, 35.00]
    ],
    'vinyl' => [
        'names' => ['Classic Rock Vinyl', 'Limited Edition LP', 'Vintage Record', 'Collector\'s Vinyl', 'Rock Legend Album', 'Concert Recording', 'Rare Pressing', 'Special Edition Vinyl', 'Rock History LP', 'Exclusive Release'],
        'descriptions' => [
            'High-quality vinyl pressing with premium sound quality.',
            'Limited edition release with exclusive artwork.',
            'Vintage pressing in excellent condition.',
            'Collector\'s item with unique packaging.',
            'Rare find that belongs in any serious collection.'
        ],
        'price_range' => [25.00, 120.00]
    ],
    'default' => [
        'names' => ['Rock Product', 'Music Item', 'Concert Merch', 'Band Product', 'Rock Accessory', 'Music Memorabilia', 'Concert Item', 'Rock Collection Piece', 'Music Product', 'Band Merchandise'],
        'descriptions' => [
            'High-quality product perfect for any music lover.',
            'Authentic item that captures the rock spirit.',
            'Limited edition piece with unique design.',
            'Perfect addition to your music collection.',
            'Handcrafted with attention to detail.'
        ],
        'price_range' => [10.00, 75.00]
    ]
];

// Adjective arrays for product names
$ADJECTIVES = ['Vintage', 'Classic', 'Limited Edition', 'Exclusive', 'Premium', 'Authentic', 'Handcrafted', 'Rare', 'Collectors', 'Special Edition', 'Rock', 'Metal', 'Punk', 'Gothic', 'Alternative', 'Indie', 'Underground', 'Mainstream', 'Elite', 'Legendary'];

// Color arrays for product variations
$COLORS = ['Black', 'White', 'Red', 'Blue', 'Green', 'Purple', 'Orange', 'Yellow', 'Pink', 'Brown', 'Grey', 'Silver', 'Gold', 'Navy', 'Maroon', 'Teal', 'Crimson', 'Emerald', 'Sapphire', 'Amber'];

/**
 * Execute wp-cli command and return output
 */
function execute_wp_cli($command) {
    global $SCRIPT_DIR;
    
    $full_command = "cd {$SCRIPT_DIR} && wp --allow-root {$command}";
    $output = shell_exec($full_command . " 2>&1");
    
    if ($output === null) {
        throw new Exception("Failed to execute wp-cli command: {$command}");
    }
    
    return trim($output);
}

/**
 * Get all categories from WordPress
 */
function get_categories() {
    $output = execute_wp_cli("term list product_cat --format=json");
    $categories = json_decode($output, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Failed to parse categories JSON: " . json_last_error_msg());
    }
    
    // Filter out Uncategorized and empty categories
    return array_filter($categories, function($category) {
        return !empty($category['name']) && 
               strtolower($category['name']) !== 'uncategorized' &&
               $category['count'] !== '0';
    });
}

/**
 * Get existing products to avoid duplicates
 */
function get_existing_products() {
    $output = execute_wp_cli("post list --post_type=product --format=json --fields=ID,post_title");
    $products = json_decode($output, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Failed to parse products JSON: " . json_last_error_msg());
    }
    
    return $products ?: [];
}

/**
 * Generate random product name
 */
function generate_product_name($category_name, $template) {
    global $ADJECTIVES, $COLORS;
    
    $category_lower = strtolower($category_name);
    $base_names = $template['names'] ?? $PRODUCT_TEMPLATES['default']['names'];
    
    $base_name = $base_names[array_rand($base_names)];
    $adjective = $ADJECTIVES[array_rand($ADJECTIVES)];
    $color = $COLORS[array_rand($COLORS)];
    
    // 50% chance to include color
    if (rand(0, 1) === 1) {
        return "{$adjective} {$color} {$base_name}";
    } else {
        return "{$adjective} {$base_name}";
    }
}

/**
 * Generate random product description
 */
function generate_product_description($template) {
    $descriptions = $template['descriptions'] ?? $PRODUCT_TEMPLATES['default']['descriptions'];
    $base_description = $descriptions[array_rand($descriptions)];
    
    // Add some variety to descriptions
    $variations = [
        "Perfect for any rock concert or music event.",
        "Made with premium materials for lasting quality.",
        "Limited edition piece with unique design elements.",
        "Handcrafted with attention to detail and authenticity.",
        "Exclusive item that stands out from the crowd."
    ];
    
    $variation = $variations[array_rand($variations)];
    
    return "{$base_description} {$variation}";
}

/**
 * Generate random price within category range
 */
function generate_product_price($template) {
    $price_range = $template['price_range'] ?? $PRODUCT_TEMPLATES['default']['price_range'];
    $min_price = $price_range[0];
    $max_price = $price_range[1];
    
    // Generate price with 2 decimal places
    $price = round($min_price + (mt_rand() / mt_getrandmax()) * ($max_price - $min_price), 2);
    
    return number_format($price, 2, '.', '');
}

/**
 * Generate product slug from name
 */
function generate_product_slug($name) {
    $slug = strtolower($name);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    $slug = trim($slug, '-');
    
    // Add random number to ensure uniqueness
    $slug .= '-' . rand(1000, 9999);
    
    return $slug;
}

/**
 * Clean product name for safe shell usage
 */
function clean_product_name($name) {
    // Remove or replace problematic characters
    $name = str_replace("'", '', $name); // Remove apostrophes
    $name = str_replace('"', '', $name); // Remove quotes
    $name = str_replace('&', 'and', $name); // Replace & with and
    $name = preg_replace('/[^\w\s-]/', '', $name); // Keep only alphanumeric, spaces, and hyphens
    $name = trim($name);
    
    // Ensure name is not empty
    if (empty($name)) {
        $name = 'Product ' . rand(1000, 9999);
    }
    
    return $name;
}

/**
 * Create a product in WordPress
 */
function create_product($product_data) {
    // Clean the product name for safe shell usage
    $clean_title = clean_product_name($product_data['title']);
    $title = escapeshellarg($clean_title);
    $content = escapeshellarg($product_data['description']);
    $slug = escapeshellarg($product_data['slug']);
    $price = escapeshellarg($product_data['price']);
    $category = escapeshellarg($product_data['category']);
    
    // Create the product post
    $post_command = "post create --post_type=product --post_title={$title} --post_content={$content} --post_name={$slug} --post_status=publish";
    $output = execute_wp_cli($post_command);
    
    // Check if the command was successful
    if (strpos($output, 'Success: Created post') === false) {
        throw new Exception("Failed to create product: {$clean_title}");
    }
    
    // Extract the post ID from the success message
    if (preg_match('/Success: Created post (\d+)\./', $output, $matches)) {
        $product_id = $matches[1];
    } else {
        throw new Exception("Could not extract post ID from output: {$output}");
    }
    
    // Set product meta (price, etc.)
    execute_wp_cli("post meta update {$product_id} _price {$price}");
    execute_wp_cli("post meta update {$product_id} _regular_price {$price}");
    execute_wp_cli("post meta update {$product_id} _sale_price ''");
    execute_wp_cli("post meta update {$product_id} _manage_stock 'no'");
    execute_wp_cli("post meta update {$product_id} _stock_status 'instock'");
    execute_wp_cli("post meta update {$product_id} _visibility 'visible'");
    
    // Assign category
    execute_wp_cli("post term set {$product_id} product_cat {$category}");
    
    return $product_id;
}

/**
 * Main execution function
 */
function main() {
    global $PRODUCTS_PER_CATEGORY, $PRODUCT_TEMPLATES;
    
    echo "🎵 Starting WordPress Product Generator...\n\n";
    
    try {
        // Get categories
        echo "📂 Fetching categories...\n";
        $categories = get_categories();
        echo "Found " . count($categories) . " categories\n\n";
        
        // Get existing products
        echo "📦 Fetching existing products...\n";
        $existing_products = get_existing_products();
        echo "Found " . count($existing_products) . " existing products\n\n";
        
        $total_created = 0;
        
        foreach ($categories as $category) {
            $category_name = $category['name'];
            $category_slug = $category['slug'];
            
            echo "🎯 Processing category: {$category_name}\n";
            
            // Determine template based on category name
            $category_lower = strtolower($category_name);
            $template = $PRODUCT_TEMPLATES['default'];
            
            foreach ($PRODUCT_TEMPLATES as $key => $cat_template) {
                if ($key !== 'default' && strpos($category_lower, $key) !== false) {
                    $template = $cat_template;
                    break;
                }
            }
            
            // Generate products for this category
            for ($i = 1; $i <= $PRODUCTS_PER_CATEGORY; $i++) {
                $product_name = generate_product_name($category_name, $template);
                $product_description = generate_product_description($template);
                $product_price = generate_product_price($template);
                $product_slug = generate_product_slug($product_name);
                
                $product_data = [
                    'title' => $product_name,
                    'description' => $product_description,
                    'slug' => $product_slug,
                    'price' => $product_price,
                    'category' => $category_slug
                ];
                
                try {
                    $product_id = create_product($product_data);
                    echo "  ✅ Created: {$product_name} (£{$product_price}) [ID: {$product_id}]\n";
                    $total_created++;
                } catch (Exception $e) {
                    echo "  ❌ Failed to create: {$product_name} - {$e->getMessage()}\n";
                }
                
                // Small delay to avoid overwhelming the system
                usleep(100000); // 0.1 second
            }
            
            echo "  📊 Completed {$PRODUCTS_PER_CATEGORY} products for {$category_name}\n\n";
        }
        
        echo "🎉 Product generation completed!\n";
        echo "📈 Total products created: {$total_created}\n";
        echo "📊 Products per category: {$PRODUCTS_PER_CATEGORY}\n";
        
        // Clear any caches
        echo "\n🧹 Clearing caches...\n";
        execute_wp_cli("cache flush");
        echo "✅ Caches cleared\n";
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        exit(1);
    }
}

// Run the script
main(); 