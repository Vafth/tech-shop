<?php wp_head(); ?>
<nav class="ec-nav">
  <a class="nav-brand" href="<?= home_url() ?>">TechShop</a>
  <a href="<?= home_url('/shop') ?>">Shop</a>
  <?php if ( is_user_logged_in() ) : ?>
    <a href="<?= home_url('/favorites') ?>">Favorites</a>
    <a href="<?= wp_logout_url( home_url('/shop') ) ?>">Logout (<?= wp_get_current_user()->user_login ?>)</a>
  <?php else : ?>
    <a href="<?= home_url('/login') ?>">Login</a>
  <?php endif; ?>
</nav>