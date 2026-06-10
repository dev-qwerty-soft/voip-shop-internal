<?php get_header(); ?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php while (have_posts()):
          the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <div class="entry-meta">
                        <?php printf(esc_html__('Published on %s', 'custombasetheme'), '<span class="posted-on">' . get_the_date() . '</span>'); ?>
                    </div>
                </header>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
                <footer class="entry-footer">
                    <?php the_tags('<span class="tags-links">' . esc_html__('Tags: ', 'custombasetheme'), ', ', '</span>'); ?>
                </footer>
            </article>
            <?php if (comments_open() || get_comments_number()):
              comments_template();
            endif; ?>
        <?php
        endwhile; ?>
    </main>
</div>
<?php get_footer(); ?>
