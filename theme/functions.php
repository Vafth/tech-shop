<?php

# ── styles ────────────────────────────────────────────────────────────────────
function techshop_enqueue_styles() {
    wp_enqueue_style( 'techshop-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'techshop_enqueue_styles' );

# ── scripts ───────────────────────────────────────────────────────────────────
function techshop_enqueue_scripts() {
    wp_enqueue_script('techshop-main', get_stylesheet_directory_uri() . '/main.js', [], null, true);
    wp_localize_script('techshop-main', 'wpAjax', [
        'url'   => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('fav_nonce')
    ]);
}
add_action('wp_enqueue_scripts', 'techshop_enqueue_scripts');


add_action('template_redirect', function() {
    if (is_front_page() && !is_page('shop')) {
        wp_redirect(home_url('/shop/'), 301);
        exit;
    }
});

add_action('init', function() {
    if (is_admin() && !current_user_can('manage_options') && !wp_doing_ajax()) {
        wp_redirect(home_url('/shop/'));
        exit;
    }
});

add_action('wp_head', function() {
    echo '<link rel="icon" href="' . get_stylesheet_directory_uri() . '/favicon.ico" />';
});

add_filter('show_admin_bar', function($show) {
    return current_user_can('manage_options') ? $show : false;
});