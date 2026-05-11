<?php
/**
 * Template Name: Favorites
 */

if ( ! is_user_logged_in() ) {
    wp_redirect( home_url( '/login' ) );
    exit;
}

global $wpdb;
$uid = get_current_user_id();

$favorites = $wpdb->get_results( $wpdb->prepare(
    "SELECT p.*, f.created_at AS saved_at
     FROM {$wpdb->prefix}favorites f
     JOIN {$wpdb->prefix}products p ON p.id = f.product_id
     WHERE f.user_id = %d
     ORDER BY f.created_at DESC",
    $uid
) );

get_header();
?>
<div class="page-content">
<div class="fav-wrap">
  <h1>My Favorites</h1>
  <p class="fav-subtitle"><?= count( $favorites ) ?> saved product<?= count( $favorites ) !== 1 ? 's' : '' ?></p>

  <?php if ( empty( $favorites ) ) : ?>
    <div class="empty-state">
      <p>You haven't saved anything yet.</p>
      <a href="<?= home_url('/shop') ?>">Browse the shop →</a>
    </div>

  <?php else : ?>
    <div class="fav-list">
      <?php foreach ( $favorites as $p ) :
        $product_url = home_url( '/product/?product_id=' . $p->id );
        $saved_date  = date( 'M j, Y', strtotime( $p->saved_at ) );
      ?>
      <div class="fav-item">
        <div class="fav-img">
          <img src="<?= esc_url( $p->img_src ) ?>" alt="<?= esc_html( $p->name ) ?>">
        </div>
        <div class="fav-body">
          <span class="fav-category"><?= esc_html( $p->category ) ?></span>
          <span class="fav-name"><?= esc_html( $p->name ) ?></span>
          <span class="fav-price">$<?= number_format( $p->price, 2 ) ?></span>
          <span class="fav-saved">Saved on <?= $saved_date ?></span>
        </div>
        <div class="fav-actions">
          <a class="btn btn-info" href="<?= esc_url( $product_url ) ?>">View</a>

          <div class="tooltip-wrap">
            <button class="btn btn-buy" disabled>Buy Now</button>
            <span class="tooltip">Purchasing is currently unavailable</span>
          </div>
          <button
            class="btn btn-remove"
            data-toggle-fav
            data-product-id="<?= $p->id ?>"
            data-fav-action="remove"
            data-fav-item>
            &#9829; Remove
          </button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
</div>

<?php get_footer(); ?>