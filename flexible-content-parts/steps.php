<?php
if (!function_exists('get_sub_field')) {
    return;
}
$label = get_sub_field('label');
$title = get_sub_field('title');
$img = get_sub_field('img');
$img = get_sub_field('img');
$group_1 = get_sub_field('group_1');
$group_2 = get_sub_field('group_2');
?>

<section class="steps">
    <div class="container">
        <?php
        if ($label) {
            echo '<div class="title-label" data-aos="fade-up">' . esc_html($label) . '</div>';
        }
        if ($title) {
            echo '<div class="title" data-aos="fade-up" data-aos-delay="200">' . wp_kses_post($title) . '</div>';
        }
        ?>
        <div class="steps__grid">
            <?php if ($group_1):
                $desc_1 = $group_1['description'];
                $list_1 = $group_1['list'];
            ?>
                <div class="steps__col" data-aos="fade-up" data-aos-delay="400">
                    <?php if (!empty($list_1)): ?>
                        <div class="steps__col-list">
                            <?php foreach ($list_1 as $item):
                                $icon = $item['icon'];
                                $text = $item['text'];
                            ?>
                                <div class="steps__item">
                                    <?php if ($icon): ?>
                                        <div class="steps__item-icon">
                                            <img src="<?= esc_url($icon['url']); ?>" alt="<?= esc_attr($icon['alt']); ?>">
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($text): ?>
                                        <div class="steps__item-text"><?= $text; ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($desc_1): ?>
                        <div class="steps__col-desc"><?= $desc_1; ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if ($group_2):
                $desc_2 = $group_2['description'];
                $list_2 = $group_2['list'];
            ?>
                <div class="steps__col" data-aos="fade-up" data-aos-delay="600">
                    <?php if (!empty($list_2)): ?>
                        <div class="steps__col-list">
                            <?php foreach ($list_2 as $item):
                                $icon = $item['icon'];
                                $text = $item['text'];
                            ?>
                                <div class="steps__item">
                                    <?php if ($icon): ?>
                                        <div class="steps__item-icon">
                                            <img src="<?= esc_url($icon['url']); ?>" alt="<?= esc_attr($icon['alt']); ?>">
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($text): ?>
                                        <div class="steps__item-text"><?= $text; ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($desc_2): ?>
                        <div class="steps__col-desc"><?= $desc_2; ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if ($img): ?>
                <div class="steps__image" data-aos="fade-up" data-aos-delay="800">
                    <img
                        src="<?= esc_url($img['url']); ?>"
                        alt="<?= esc_attr($img['alt'] ?: ''); ?>">
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>