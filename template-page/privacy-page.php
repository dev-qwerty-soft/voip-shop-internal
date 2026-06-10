<?php
/*
Template Name: Privacy Policy Template
*/
get_header(); ?>
<main>
    <section class="privacy-policy-section">
        <div class="container">
            <div class="privacy-policy-section__top">
                <h1 class="title" data-aos="fade-up" data-aos-delay="100"><?= trim(get_the_title()) ?></h1>
                <p class="paragraph simple-text" data-aos="fade-up" data-aos-delay="200"><?php esc_html_e('Last Updated', 'voip-theme'); ?> <?= get_the_modified_date() ?></p>
            </div>
            <div class="privacy-policy-section__blocks">
                <?php
                $privacyPolicyBlock = get_field('privacy_policy_block');
                if ($privacyPolicyBlock && is_array($privacyPolicyBlock) && !empty($privacyPolicyBlock)) {
                  foreach ($privacyPolicyBlock as $index => $item) {
                    $i = $index + 1;
                    $title = $item['privacy_policy_block_title'];
                    $content = $item['privacy_policy_block_content'];
                    if ($title !== '' && $content !== '') {
                      echo "<div class='privacy-policy-section__block'>
                                <h2 class='title' data-aos='fade-up' data-aos-delay='300'>$i. " .  esc_html($title) . "</h2>
                                <div class='content' data-aos='fade-up' data-aos-delay='400'>$content</div>
                            </div>";
                    }
                  }
                }
                ?>
            </div>
        </div>
    </section>
    <?php get_template_part('template-blocks/action'); ?>
</main>
<?php get_footer(); ?>
