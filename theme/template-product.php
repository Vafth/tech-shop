<?php
/**
 * Template Name: Product
 */

global $wpdb;

$product_id = isset( $_GET['product_id'] ) ? intval( $_GET['product_id'] ) : 0;
$product    = $product_id
    ? $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}products WHERE id = %d", $product_id ) )
    : null;

if ( ! $product ) {
    wp_redirect( home_url( '/shop' ) );
    exit;
}

// Handle add / remove favorite
if ( isset( $_POST['fav_action'] ) && is_user_logged_in() ) {
    check_admin_referer( 'fav_nonce' );
    $uid   = get_current_user_id();
    $table = $wpdb->prefix . 'favorites';

    if ( $_POST['fav_action'] === 'add' ) {
        $wpdb->insert( $table, [ 'user_id' => $uid, 'product_id' => $product_id ] );
    } elseif ( $_POST['fav_action'] === 'remove' ) {
        $wpdb->delete( $table, [ 'user_id' => $uid, 'product_id' => $product_id ] );
    }
    wp_redirect( get_permalink() . '?product_id=' . $product_id );
    exit;
}

$is_saved  = false;
if ( is_user_logged_in() ) {
    $uid      = get_current_user_id();
    $is_saved = (bool) $wpdb->get_var( $wpdb->prepare(
        "SELECT id FROM {$wpdb->prefix}favorites WHERE user_id = %d AND product_id = %d",
        $uid, $product_id
    ) );
}

$save_count = (int) $wpdb->get_var( $wpdb->prepare(
    "SELECT COUNT(*) FROM {$wpdb->prefix}favorites WHERE product_id = %d", $product_id
) );

get_header();
?>
<div class="page-content">
<div class="product-wrap">
  <div class="breadcrumb">
    <a href="<?= home_url('/shop') ?>">Shop</a>
    <span>›</span>
    <?= esc_html( $product->category ) ?>
    <span>›</span>
    <?= esc_html( $product->name ) ?>
  </div>

  <div class="product-card">
    <div class="product-img">
      <img src="<?= esc_url( $product->img_src ) ?>" alt="<?= esc_html( $product->name ) ?>">
    </div>
    <div class="product-info">
      <span class="product-category"><?= esc_html( $product->category ) ?></span>
      <h1 class="product-name"><?= esc_html( $product->name ) ?></h1>
      <span class="product-price">$<?= number_format( $product->price, 2 ) ?></span>
      <p class="product-desc"><?= esc_html( $product->description ) ?></p>
      <span class="product-saves">&#9829; <?= $save_count ?> people saved this</span>

      <div class="product-actions">

        <?php if ( is_user_logged_in() ) : ?>
          <form method="post">
            <?php wp_nonce_field( 'fav_nonce' ); ?>
            <input type="hidden" name="product_id" value="<?= $product->id ?>">
            <input type="hidden" name="fav_action" value="<?= $is_saved ? 'remove' : 'add' ?>">
            <button type="submit" class="btn <?= $is_saved ? 'btn-remove' : 'btn-add' ?>">
              <?= $is_saved ? '&#9829; Remove from Favorites' : '&#9825; Add to Favorites' ?>
            </button>
          </form>
        <?php else : ?>
          <a class="btn btn-add" href="<?= home_url('/login') ?>">&#9825; Save to Favorites</a>
        <?php endif; ?>

        <div class="tooltip-wrap">
          <button class="btn btn-buy" disabled>Buy Now</button>
          <span class="tooltip">Purchasing is currently unavailable</span>
        </div>

      </div>
    </div>
  </div>
</div>
</div>

<?php get_footer(); ?>