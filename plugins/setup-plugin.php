<?php
/**
 * Plugin Name: TechShop Setup
 * Description: Creates products and favorites tables, seeds 10 products.
 * Version: 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

register_activation_hook( __FILE__, 'ecommerce_setup_activate' );

function ecommerce_setup_activate() {
    global $wpdb;
    $charset = $wpdb->get_charset_collate();

    $products_table  = $wpdb->prefix . 'products';
    $favorites_table = $wpdb->prefix . 'favorites';

    $sql_products = "CREATE TABLE IF NOT EXISTS $products_table (
        id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
        name        VARCHAR(255) NOT NULL, 
        img_src     VARCHAR(255) NOT NULL,
        category    VARCHAR(100) NOT NULL,
        price       DECIMAL(10,2) NOT NULL,
        description TEXT NOT NULL,
        PRIMARY KEY (id)
    ) $charset;";

    $sql_favorites = "CREATE TABLE IF NOT EXISTS $favorites_table (
        id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id    BIGINT UNSIGNED NOT NULL,
        product_id INT UNSIGNED NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY unique_favorite (user_id, product_id),
        KEY idx_user_id    (user_id),
        KEY idx_product_id (product_id)
    ) $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql_products );
    dbDelta( $sql_favorites );

    ecommerce_seed_products();
}

function ecommerce_seed_products() {
    global $wpdb;
    $table = $wpdb->prefix . 'products';

    $existing = $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
    if ( $existing > 0 ) return;

    $products = [
        // Cellphones (4)
        [
            'name'        => 'Samsung Galaxy S24 Ultra',
            'img_src'     => 'https://prod-api.mediaexpert.pl/api/images/gallery/thumbnails/images/61/6180562/Smartfon-SAMSUNG-Galaxy-S24-Ultra-Czarny-logotyp.jpg',
            'category'    => 'Cellphone',
            'price'       => 1299.99,
            'description' => '6.8-inch Dynamic AMOLED display, Snapdragon 8 Gen 3, 200MP camera, 5000mAh battery, built-in S Pen.',
        ],
        [
            'name'        => 'Apple iPhone 15 Pro',
            'img_src'     => 'https://prod-api.mediaexpert.pl/api/images/gallery/thumbnails/images/58/5860220/Smartfon-APPLE-iPhone-15-128GB-5G-6-1-Czarny-front-tyl-3.jpg',
            'category'    => 'Cellphone',
            'price'       => 1099.99,
            'description' => '6.1-inch Super Retina XDR, A17 Pro chip, 48MP main camera, titanium frame, USB-C.',
        ],
        [
            'name'        => 'GOOGLE Pixel 10a 5G',
            'img_src'     => 'https://prod-api.mediaexpert.pl/api/images/gallery/thumbnails/images/95/9506900/Smartfon-GOOGLE-Pixel-10a-5G-Obsydian-gemini.jpg',
            'category'    => 'Cellphone',
            'price'       => 699.99,
            'description' => '6.2-inch Actua display, Google Tensor G3, 50MP camera with Magic Eraser, 7 years of OS updates.',
        ],
        [
            'name'        => 'XIAOMI Redmi Note 14 Pro 5G',
            'img_src'     => 'https://prod-api.mediaexpert.pl/api/images/gallery/thumbnails/images/72/7263228/Smartfon-XIAOMI-Redmi-Note-14-Pro-5G-8-256GB-6-67-120Hz-Czarny-front-tyl.jpg',
            'category'    => 'Cellphone',
            'price'       => 799.99,
            'description' => '6.36-inch AMOLED 120Hz, Snapdragon 8 Gen 3, Leica-tuned triple camera, 4610mAh with 90W charging.',
        ],
        // Laptops (3)
        [
            'name'        => 'Laptop APPLE MacBook Neo 2026 13"',
            'img_src'     => 'https://prod-api.mediaexpert.pl/api/images/gallery/thumbnails/images/97/9705284/MacBook_13-in_Touch_ID_A18_Pro_Indigo_PDP_Image_Position_1__pl-PL.jpg',
            'category'    => 'Laptop',
            'price'       => 1999.99,
            'description' => 'M3 Pro chip, 18GB RAM, 512GB SSD, Liquid Retina XDR display, up to 18 hours battery life.',
        ],
        [
            'name'        => 'Laptop DELL XPS 9315-9164',
            'img_src'     => 'https://prod-api.mediaexpert.pl/api/images/gallery/thumbnails/images/42/4242284/Laptop-DELL-XPS-9315-9164-01.jpg',
            'category'    => 'Laptop',
            'price'       => 1749.99,
            'description' => '15.6-inch OLED touch display, Intel Core i7-13700H, 32GB RAM, 1TB SSD, NVIDIA RTX 4060.',
        ],
        [
            'name'        => 'Lenovo ThinkPad X1 Carbon',
            'img_src'     => 'https://prod-api.mediaexpert.pl/api/images/gallery/thumbnails/images/73/7370272/2102552.jpg',
            'category'    => 'Laptop',
            'price'       => 1549.99,
            'description' => '14-inch IPS display, Intel Core i7-1365U, 16GB RAM, 512GB SSD, under 1.12kg, MIL-SPEC durability.',
        ],
        // Headphones (3)
        [
            'name'        => 'Sony WH-1000XM5',
            'img_src'     => 'https://prod-api.mediaexpert.pl/api/images/gallery/thumbnails/images/37/3784350/Sluchawki-nauszne-SONY-WH-1000XM5-00-1.jpg',
            'category'    => 'Headphones',
            'price'       => 349.99,
            'description' => 'Industry-leading noise cancelling, 30-hour battery, multipoint Bluetooth, crystal-clear call quality.',
        ],
        [
            'name'        => 'Apple AirPods Max 2',
            'img_src'     => 'https://prod-api.mediaexpert.pl/api/images/gallery/thumbnails/images/97/9787478/AIRPODS-MAX-2-MIDNIGHT-1.jpg',
            'category'    => 'Headphones',
            'price'       => 549.99,
            'description' => 'Over-ear design, H1 chip, Adaptive Transparency, Personalized Spatial Audio, 20-hour battery.',
        ],
        [
            'name'        => 'Bose QuietComfort ANC',
            'img_src'     => 'https://prod-api.mediaexpert.pl/api/images/gallery/thumbnails/images/91/9187554/Sluchawki-nauszne-BOSE-QuietComfort-ANC-Lodowy-blekit-1.jpg',
            'category'    => 'Headphones',
            'price'       => 279.99,
            'description' => 'World-class noise cancellation, TriPort acoustic architecture, 24-hour battery, foldable design.',
        ],
    ];

    foreach ( $products as $product ) {
        $wpdb->insert( $table, $product, [ '%s', '%s', '%s', '%f', '%s' ] );
    }
}

// ── AJAX: toggle favorite ────────────────────────────────────────────────────
add_action('wp_ajax_toggle_favorite', 'techshop_toggle_favorite');

function techshop_toggle_favorite() {
    check_ajax_referer('fav_nonce', 'nonce');
    global $wpdb;
    $user_id    = get_current_user_id();
    $product_id = intval($_POST['product_id']);
    $action     = sanitize_text_field($_POST['fav_action']);
    $table      = $wpdb->prefix . 'favorites';

    if ($action === 'add') {
        $wpdb->insert($table, ['user_id' => $user_id, 'product_id' => $product_id]);
    } else {
        $wpdb->delete($table, ['user_id' => $user_id, 'product_id' => $product_id]);
    }
    wp_send_json_success(['action' => $action]);
}