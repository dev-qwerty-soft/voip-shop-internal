<?php
$sch_title = get_field('schedule_title', 'option');
$sch_text = get_field('schedule_text', 'option');
$sch_img = get_field('schedule_img', 'option');
?>

<?php if ($sch_title || $sch_img || $sch_text): ?>
    <div class="schedule-modal-overlay" id="scheduleModal">
        <div class="schedule-modal">
            <div class="schedule-modal__img">
                <?php if ($sch_img): ?>
                    <img
                        src="<?= esc_url($sch_img['url']); ?>"
                        alt="<?= esc_attr($sch_img['alt'] ?: ''); ?>">
                <?php endif; ?>
                <button class="schedule-modal__close" id="scheduleModalClose">
                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 15.75L15.75 0.75M0.75 0.75L15.75 15.75" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
            <?php
            if ($sch_title) echo '<h2 class="title schedule-modal__title">' . esc_html($sch_title) . '</h2>';
            if ($sch_text) echo '<p class="schedule-modal__text simple-text">' . esc_html($sch_text) . '</p>'; ?>
            <div class="schedule-modal__buttons">
                <?php
                $link = get_field('schedule_btn', 'option');
                if ($link):
                    $link_url = $link['url'];
                    $link_title = $link['title'];
                    $link_target = $link['target'] ? $link['target'] : '_self';
                ?>
                    <button class="btn" onclick="Calendly.initPopupWidget({url: 'https://calendly.com/harry-voipx3/15min'});return false;" id="scheduleModalAccept">
                        <?php echo esc_html($link_title); ?>
                    </button>
                <?php endif; ?>

                <?php
                $link = get_field('schedule_btn_2', 'option');
                if ($link):
                    $link_url = $link['url'];
                    $link_title = $link['title'];
                    $link_target = $link['target'] ? $link['target'] : '_self';
                ?>
                    <a class="btn btn--light" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>