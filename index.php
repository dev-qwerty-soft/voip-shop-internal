<?php get_header(); ?>
<main class="main-content">
    <div class="container">
        <?php if (have_posts()): ?>
            <header class="page-header">
                <h1 class="page-title">
                    <?php if (is_home() && !is_front_page()) {
                      single_post_title();
                    } elseif (is_category()) {
                      single_cat_title();
                    } elseif (is_tag()) {
                      single_tag_title();
                    } elseif (is_author()) {
                      the_author();
                    } elseif (is_archive()) {
                      the_archive_title();
                    } else {
                      _e('Latest Posts', 'custom-theme');
                    } ?>
                </h1>
                <?php if (is_archive() && get_the_archive_description()): ?>
                    <div class="page-description">
                        <?php the_archive_description(); ?>
                    </div>
                <?php endif; ?>
            </header>
            <div class="posts-grid">
                <?php while (have_posts()):
                  the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                        <?php if (has_post_thumbnail()): ?>
                            <div class="post-card__image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('card-image'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="post-card__content">
                            <h2 class="post-card__title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            <div class="post-card__meta">
                                <time datetime="<?php echo get_the_date('c'); ?>">
                                    <?php echo get_the_date(); ?>
                                </time>
                                <?php if (get_the_category()): ?>
                                    <span class="post-category">
                                        <?php the_category(', '); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="post-card__excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="post-card__link">
                                <?php _e('Read More', 'custom-theme'); ?>
                            </a>
                        </div>
                    </article>
                <?php
                endwhile; ?>
            </div>
            <?php the_posts_pagination([
              'prev_text' => __('Previous', 'custom-theme'),
              'next_text' => __('Next', 'custom-theme'),
            ]); ?>
        <?php else: ?>
            <div class="no-content">
                <h1><?php _e('Nothing Found', 'custom-theme'); ?></h1>
                <p><?php _e('It looks like nothing was found at this location. Try using the search form.', 'custom-theme'); ?></p>
                <div class="search-form-wrapper">
                    <?php get_search_form(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
