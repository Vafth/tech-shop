<?php
/**
 * Template Name: Shop
 */

global $wpdb;

$products = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}products ORDER BY category, id" );

$saved_ids = [];
if ( is_user_logged_in() ) {
    $uid  = get_current_user_id();
    $rows = $wpdb->get_results( $wpdb->prepare(
        "SELECT product_id FROM {$wpdb->prefix}favorites WHERE user_id = %d", $uid
    ) );
    $saved_ids = wp_list_pluck( $rows, 'product_id' );
}

// Favorites count per product
$counts_raw = $wpdb->get_results(
    "SELECT product_id, COUNT(*) as cnt FROM {$wpdb->prefix}favorites GROUP BY product_id", OBJECT_K
);

get_header();
?>
<div class="page-content">
<div class="shop-wrap">
  <h1>All Products</h1>
  <div class="product-grid">
    <?php foreach ( $products as $p ) :
      $is_saved  = in_array( $p->id, $saved_ids );
      $save_cnt  = isset( $counts_raw[ $p->id ] ) ? (int) $counts_raw[ $p->id ]->cnt : 0;
      $product_url = home_url( '/product/?product_id=' . $p->id );
    ?>
    <div class="card">
      <div class="card-img">
        <img src="<?= esc_url( $p->img_src ) ?>" alt="<?= esc_html( $p->name ) ?>">
      </div>
      <div class="card-body">
        <span class="card-category"><?= esc_html( $p->category ) ?></span>
        <span class="card-name"><?= esc_html( $p->name ) ?></span>
        <span class="card-price">$<?= number_format( $p->price, 2 ) ?></span>
        <span class="card-saves">&#9829; <?= $save_cnt ?> saved</span>
      </div>
      <div class="card-actions">
        <a class="btn btn-info btn-full" href="<?= esc_url( $product_url ) ?>">View</a>

        <?php if ( is_user_logged_in() ) : ?>
          <button 
            class="btn <?= $is_saved ? 'btn-remove' : 'btn-add' ?> btn-full"
            data-toggle-fav
            data-product-id="<?= $p->id ?>"
            data-fav-action="<?= $is_saved ? 'remove' : 'add' ?>">
            <?= $is_saved ? '&#9829; Remove' : '&#9825; Save' ?>
          </button>
        <?php else : ?>
          <a class="btn btn-add btn-full" href="<?= home_url('/login') ?>">&#9825; Save</a>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
</div>

<?php get_footer(); ?>