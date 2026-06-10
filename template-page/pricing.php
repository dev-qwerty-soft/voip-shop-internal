<?php
/*
Template Name: Pricing Template
*/
get_header();

$title = get_field('title');
$text = get_field('text');
?>

<main class="pricing-main">

  <?php get_template_part('template-blocks/breadcrumbs'); ?>
  <section class="pricing-plans">
    <div class="container">
      <div class="pricing-plans__top">
        <?php
        if ($title) {
          echo '<h1 class="title">' . esc_html($title) . '</h1>';
        }
        if ($text) {
          echo '<div class="pricing-plans__subtitle">' . wp_kses_post($text) . '</div>';
        }

        $i = 0;
        if (have_rows('plans_lists')): ?>
          <div class="pricing-plans__tabs-wrap">
            <div class="pricing-plans__tabs">
              <?php while (have_rows('plans_lists')):

                the_row();
                $tab = get_sub_field('tab');
                $disable_users = get_sub_field('disable_users');
                $disable_save = get_sub_field('disable_save');
                $i++;
              ?>
                <span
                  <?= $i == 1 ? 'class="active"' : '' ?>
                  <?= $disable_users ? 'data-user-disable="true"' : '' ?>
                  <?= $disable_save ? 'data-save-disable="true"' : '' ?>>
                  <?= esc_html($tab) ?>
                </span>
              <?php
              endwhile; ?>
              <div class="slider"></div>
            </div>
          </div>
        <?php endif;
        ?>
      </div>
      <?php
      $settings_class = 'pricing-plans__settings';
      $first_tab_disable_users = false;
      $first_tab_disable_save = false;

      if (have_rows('plans_lists')) {
        while (have_rows('plans_lists')) {
          the_row();
          $i++;

          if ($i === 1) {
            $first_tab_disable_users = get_sub_field('disable_users');
            $first_tab_disable_save  = get_sub_field('disable_save');
            break;
          }
        }
        reset_rows();
      }

      if ($first_tab_disable_users) {
        $settings_class .= ' users-hidden';
      }
      if ($first_tab_disable_save) {
        $settings_class .= ' save-hidden';
      }

      ?>
      <div class="<?= $settings_class; ?>">
        <div class="pricing-plans__users">
          <div class="pricing-plans__settings-title">Number of users</div>
          <div class="pricing-plans__users-tabs">
            <span class="active">1-5</span>
            <span>6-100</span>
            <span>100+</span>
            <div class="slider"></div>
          </div>
        </div>

        <div class="pricing-plans__save">
          <div class="pricing-plans__settings-title">Save up to 33% by paying annually</div>
          <div class="pricing-plans__switch">
            <input type="checkbox" id="billingSwitch">
            <label for="billingSwitch"></label>
          </div>
        </div>
      </div>

      <?php
      $i = 0;
      if (have_rows('plans_lists')): ?>
        <div class="pricing-plans__content pricing-plans__content--save">
          <?php while (have_rows('plans_lists')):

            the_row();
            $i++;

            $total_plans = 0;
            $plans_class = 'pricing-plans__list';
            $disable_users = get_sub_field('disable_users');
            $disable_save = get_sub_field('disable_save');

            if (have_rows('plans')) {
              while (have_rows('plans')) {
                the_row();
                $total_plans++;
              }
            }

            if ($total_plans == 3) {
              $plans_class .= ' pricing-plans__list--three';
            }

            if ($i == 1) {
              $plans_class .= ' active';
            }

            $enable_left_side = get_sub_field('enable_content_part');

            if ($enable_left_side) {
              $plans_class .= ' pricing-plans__list--side';
            }

          ?>
            <div class="<?= $plans_class; ?>">
              <?php

              if ($enable_left_side):
                $labels = get_sub_field('labels');
                $title_left = get_sub_field('title_left');
                $text_left = get_sub_field('text_left');

              ?>
                <div class="pricing-plans__side">
                  <?php if ($labels): ?>
                    <div class="pricing-plans__side-labels">
                      <?php
                      $labels_array = explode(',', $labels);
                      foreach ($labels_array as $label) {
                        $label = trim($label);
                        if (!empty($label)) {
                          echo '<div class="title-label">' . htmlspecialchars($label) . '</div>';
                        }
                      }
                      ?>
                    </div>
                  <?php endif; ?>
                  <?php
                  if ($title_left) echo '<h2 class="title">' . $title_left . '<h2>';
                  if ($text_left) echo '<div class="simple-text">' . $text_left . '</div>';
                  ?>
                </div>
                <div class="pricing-plans__side-plans">
                <?php endif; ?>
                <?php if (have_rows('plans')): ?>
                  <?php while (have_rows('plans')):

                    the_row();
                    $name = get_sub_field('name');
                    $label = get_sub_field('label');
                    $desc = get_sub_field('desc');
                    $offer = get_sub_field('offer');
                    $sp_1 = get_sub_field('sale_price_1');
                    $sp_2 = get_sub_field('sale_price_2');
                    $sp_3 = get_sub_field('sale_price_3');
                    $p_1 = get_sub_field('price_1');
                    $p_2 = get_sub_field('price_2');
                    $p_3 = get_sub_field('price_3');
                    $text_under_price = get_sub_field('text_under_price');
                    $term = get_sub_field('term');

                    $raw_prices = [
                      [
                        'sale' => get_sub_field('sale_price_1'),
                        'full' => get_sub_field('price_1'),
                      ],
                      [
                        'sale' => get_sub_field('sale_price_2'),
                        'full' => get_sub_field('price_2'),
                      ],
                      [
                        'sale' => get_sub_field('sale_price_3'),
                        'full' => get_sub_field('price_3'),
                      ],
                    ];

                    $prices = [];
                    if ($disable_users) {
                      $base = $raw_prices[0];
                      // If save is disabled, strip sale price so only full price shows
                      if ($disable_save) {
                        $base['sale'] = null;
                      }
                      $prices = array_fill(0, 3, $base);
                    } else {
                      foreach ($raw_prices as $idx => $price) {
                        if ($idx === 0) {
                          $resolved = $price;
                        } else {
                          $prev = $prices[$idx - 1];
                          $resolved = [
                            'sale' => $price['sale'] ?: $prev['sale'],
                            'full' => $price['full'] ?: $prev['full'],
                          ];
                        }
                        // If save is disabled, strip sale price so only full price shows
                        if ($disable_save) {
                          $resolved['sale'] = null;
                        }
                        $prices[] = $resolved;
                      }
                    }

                    $has_any_price = false;
                    foreach ($prices as $price) {
                      if (!empty($price['sale']) || !empty($price['full'])) {
                        $has_any_price = true;
                        break;
                      }
                    }

                    $list_title = get_sub_field('list_title');

                    $features = get_sub_field('features');
                  ?>
                    <div class="plan">
                      <div class="plan__top">
                        <div class="plan__title">
                          <?php
                          if ($name) {
                            echo '<div class="title">' . wp_kses_post($name) . '</div>';
                          }
                          if ($label) {
                            echo '<div class="plan__label title-label">' . esc_html($label) . '</div>';
                          }
                          ?>
                        </div>
                        <?php
                        if ($desc) {
                          echo '<div class="plan__desc">' . wp_kses_post($desc) . '</div>';
                        }
                        if ($offer) {
                          echo '<div class="plan__offer">' . wp_kses_post($offer) . '</div>';
                        }
                        if ($has_any_price): ?>
                          <div class="plan__price-wrapper" <?= $disable_save ? 'data-save-disable="true"' : '' ?>>
                            <?php foreach ($prices as $i => $price): ?>
                              <div class="plan__price <?= $i === 0 ? 'active' : '' ?>" data-index="<?= $i ?>">
                                <?php if ($price['sale']): ?>
                                  <span class="plan__price-sale">$<?= esc_html($price['sale']) ?></span>
                                <?php endif; ?>
                                <?php if ($price['full']): ?>
                                  <span class="plan__price-full">$<?= esc_html($price['full']) ?></span>
                                <?php endif; ?>
                              </div>
                            <?php endforeach; ?>
                            <?php
                            if ($term) echo '<div class="plan__price-term">' . $term . '</div>';
                            if ($text_under_price) echo '<div class="plan__price-text">' . $text_under_price . '</div>';
                            ?>
                          </div>
                        <?php endif;
                        ?>
                        <div class="plan__list">
                          <?php
                          if ($list_title) {
                            echo '<div class="plan__list-title">' . esc_html($list_title) . '</div>';
                          }
                          if (have_rows('list')): ?>
                            <?php while (have_rows('list')):

                              the_row();
                              $text = get_sub_field('text');
                              $label = get_sub_field('label');
                            ?>
                              <div class="plan__list-item">
                                <?= displaySvg('src/svg/green-check.svg'); ?>
                                <?= wp_kses_post($text); ?>
                                <?php if ($label) {
                                  echo '<div class="plan__list-label">' . esc_html($label) . '</div>';
                                } ?>
                              </div>
                              <?php if (have_rows('sub_list')): ?>
                                <div class="plan__list-sub">
                                  <?php while (have_rows('sub_list')):

                                    the_row();
                                    $text = get_sub_field('text');
                                  ?>
                                    <span><?= wp_kses_post($text); ?></span>
                                  <?php
                                  endwhile; ?>
                                </div>
                              <?php endif; ?>
                            <?php
                            endwhile; ?>
                          <?php endif;
                          ?>
                        </div>
                        <?php if ($features):
                          $f_title = $features['title']; ?>
                          <div class="plan__features">
                            <div class="plan__features-title">
                              <?= displaySvg('src/svg/stars.svg') ?>
                              <?= esc_html($f_title) ?>
                            </div>

                            <?php if (!empty($features['list'])): ?>
                              <div class="plan__features-list">
                                <?php foreach ($features['list'] as $item):

                                  $text = $item['text'] ?? '';
                                  $label = $item['label'] ?? '';
                                  $sub_list = $item['sub_list'] ?? [];
                                ?>
                                  <div class="plan__features-item">
                                    <?= displaySvg('src/svg/check-success.svg'); ?>
                                    <?= esc_html($text); ?>

                                    <?php if ($label): ?>
                                      <div class="plan__features-label"><?= esc_html($label); ?></div>
                                    <?php endif; ?>

                                  </div>
                                  <?php if (!empty($sub_list)): ?>
                                    <div class="plan__features-sub">
                                      <?php foreach ($sub_list as $sub): ?>
                                        <span><?= esc_html($sub['text'] ?? '') ?></span>
                                      <?php endforeach; ?>
                                    </div>
                                  <?php endif; ?>
                                <?php
                                endforeach; ?>
                              </div>
                            <?php endif; ?>
                          </div>
                        <?php
                        endif; ?>
                      </div>
                      <div class="plan__bottom">
                        <?php
                        $buttons = [];

                        // Check buttons
                        if ($btn1 = get_sub_field('button_1')) {
                          $buttons[] = [
                            'url' => $btn1['url'] ?? '#',
                            'title' => $btn1['title'] ?? '',
                            'target' => $btn1['target'] ?? '_self',
                            'class' => 'btn',
                          ];
                        }

                        if ($btn2 = get_sub_field('button_2')) {
                          $buttons[] = [
                            'url' => $btn2['url'] ?? '#',
                            'title' => $btn2['title'] ?? '',
                            'target' => $btn2['target'] ?? '_self',
                            'class' => 'btn btn--light',
                          ];
                        }

                        if (!empty($buttons)):
                          $btn_class = 'plan__buttons' . (count($buttons) === 1 ? ' plan__buttons--single' : ''); ?>
                          <div class="<?php echo esc_attr($btn_class); ?>">
                            <?php foreach ($buttons as $btn): ?>
                              <a class="<?php echo esc_attr($btn['class']); ?>"
                                href="<?php echo esc_url($btn['url']); ?>"
                                target="<?php echo esc_attr($btn['target']); ?>">
                                <?php echo esc_html($btn['title']); ?>
                              </a>
                            <?php endforeach; ?>
                          </div>
                        <?php
                        endif;
                        $link = get_sub_field('footer_link');
                        if ($link):

                          $link_url = $link['url'];
                          $link_title = $link['title'];
                          $link_target = $link['target'] ? $link['target'] : '_self';
                        ?>
                          <a class="plan__bottom-link" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
                        <?php
                        endif;
                        ?>
                      </div>
                    </div>
                  <?php
                  endwhile; ?>
                <?php endif; ?>
                <?php if ($enable_left_side): ?>
                </div>
              <?php endif; ?>
            </div>
          <?php
          endwhile; ?>
        </div>
      <?php endif;
      ?>
      <div class="pricing-plans__shape"></div>
    </div>
  </section>

</main>

<?php get_footer(); ?>