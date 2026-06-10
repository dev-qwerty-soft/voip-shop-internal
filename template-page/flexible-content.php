<?php
/*
Template Name: Flexible Content Template
*/
get_header(); ?>
<main class="flexible-content">
  
  <?php if (function_exists('have_rows') && have_rows('flexible-content')):
    while (have_rows('flexible-content')):
      the_row();
      get_template_part('flexible-content-parts/' . get_row_layout());
    endwhile;
  endif; ?>
</main>
<?php get_footer(); ?>
