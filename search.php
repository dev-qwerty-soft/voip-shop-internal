<?php get_header(); ?>
<main class="search-results">
    <div class="container">
        <header class="search-results__header">
            <h1 class="search-results__title">
                <?php printf(esc_html__('Search Results for: %s', 'custom-theme'), '<span class="search-query">' . get_search_query() . '</span>'); ?>
            </h1>
            <?php if (have_posts()): ?>
                <p class="search-results__count">
                    <?php printf(esc_html(_n('Found %d result', 'Found %d results', $wp_query->found_posts, 'custom-theme')), number_format_i18n($wp_query->found_posts)); ?>
                </p>
            <?php endif; ?>
        </header>
        <?php if (have_posts()): ?>
            <div class="search-results__content">
                <?php while (have_posts()):
                  the_post(); ?>
                    <article class="search-result">
                        <h2 class="search-result__title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                        <div class="search-result__meta">
                            <span class="post-type"><?php echo get_post_type_object(get_post_type())->labels->singular_name; ?></span>
                            <time datetime="<?php echo get_the_date('c'); ?>">
                                <?php echo get_the_date(); ?>
                            </time>
                        </div>
                        <div class="search-result__excerpt">
                            <?php the_excerpt(); ?>
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
            <div class="search-results__empty">
                <h2><?php esc_html_e('No Results Found', 'custom-theme'); ?></h2>
                <p><?php esc_html_e('Sorry, no content matched your search criteria. Try different keywords.', 'custom-theme'); ?></p>
                <div class="search-form-wrapper">
                    <?php get_search_form(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
