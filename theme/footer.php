<?php wp_footer(); ?>
<?php if ( !is_page('login') ) : ?>
  <footer class="ec-footer">
    <div class="footer-links">
      <a href="<?= home_url('/shop') ?>">Shop</a>
      <a href="#">About</a>
      <a href="#">Contact</a>
    </div>
    <p class="footer-copy">© <?= date('Y') ?> TechShop</p>
  </footer>
<?php else : ?>
  <footer class="ec-footer ec-footer--minimal">
    <p class="footer-copy">© <?= date('Y') ?> TechShop</p>
  </footer>
<?php endif; ?>