<?php
if (function_exists('yoast_breadcrumb') && !is_front_page()) { ?>
  <section class="breadcump">
    <div class="container">
      <?php yoast_breadcrumb('<p id="breadcrumbs">', '</p>'); ?>
    </div>
  </section>
<?php }
?>
