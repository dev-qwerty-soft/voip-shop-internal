<?php
if (!function_exists('get_sub_field')) {
    return;
}
$title = get_sub_field('title');
$subtitle = get_sub_field('subtitle');
$box = get_sub_field('box');
?>

<section class="hero-call">
    <div class="container">
        <div class="breadcrumbs">
            <div class="container">
                <?php if (function_exists('yoast_breadcrumb')) {
                    yoast_breadcrumb('<nav class="breadcrumbs__nav">', '</nav>');
                } ?>
            </div>
        </div>
        <?php if ($title) {
            echo '<h1 class="title title-gradient" data-aos="fade-up" data-aos-delay="200">' . wp_kses_post($title) . '</h1>';
        } ?>
        <?php if ($subtitle) {
            echo '<div class="subtitle simple-text" data-aos="fade-up" data-aos-delay="400">' . wp_kses_post($subtitle) . '</div>';
        } ?>
        <?php if ($box):
            $b_title = $box['title'];
            $b_text = $box['text'];
            $b_title_under = $box['title_under'];
            $link = $box['button'];
            $bg_action = $box['action_bg'];
        ?>
            <div class="hero-call__box" data-aos="fade-up" data-aos-delay="600">
                <div class="hero-call__box-inner">
                    <?php
                    if ($b_title) echo '<div class="hero-call__box-title">' . esc_html($b_title) . '</div>';
                    if ($b_text) echo '<div class="hero-call__box-text simple-text">' . wp_kses_post($b_text) . '</div>';
                    ?>
                </div>
                <div class="hero-call__box-action"
                    <?php if ($bg_action) echo 'style="background-image: url(' . esc_html($bg_action) . ');"'; ?>>
                    <?php
                    if ($b_title_under) echo '<div class="hero-call__box-title--under">' . esc_html($b_title_under) . '</div>'; ?>
                    <div class="hero-call__box-buttons">
                        <?php
                        if ($link):
                            $link_url = $link['url'];
                            $link_title = $link['title'];
                            $link_target = $link['target'] ? $link['target'] : '_self';
                        ?>
                            <button class="btn" onclick="Calendly.initPopupWidget({url: 'https://calendly.com/harry-voipx3/15min'});return false;"><?php echo esc_html($link_title); ?></button>
                        <?php endif; ?>
                        <?php
                        $link = $box['button_2'];
                        if ($link):
                            $link_url = $link['url'];
                            $link_title = $link['title'];
                            $link_target = $link['target'] ? $link['target'] : '_self';
                        ?>
                            <button class="btn btn--light" onclick="Calendly.initPopupWidget({url: 'https://calendly.com/harry-voipx3/15min'});return false;"><?php echo esc_html($link_title); ?></button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="hero-call__bg">
        <svg width="1440" height="671" viewBox="0 0 1440 671" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_3002_7537)">
                <g filter="url(#filter0_f_3002_7537)">
                    <path d="M1159.18 491.471C1159.18 582.507 1000.65 573.487 751.299 573.487C501.952 573.487 280.672 582.507 280.672 491.471C280.672 400.434 479.085 241 728.433 241C977.78 241 1159.18 400.434 1159.18 491.471Z" fill="url(#paint0_linear_3002_7537)" />
                </g>
                <g filter="url(#filter1_f_3002_7537)">
                    <path d="M875.861 443.287C875.861 466.121 819.583 463.858 731.065 463.858C642.547 463.858 563.992 466.121 563.992 443.287C563.992 420.452 634.429 380.462 722.947 380.462C811.466 380.462 875.861 420.452 875.861 443.287Z" fill="#807DFE" />
                </g>
                <g filter="url(#filter2_f_3002_7537)">
                    <ellipse cx="716.314" cy="1189.04" rx="1024.24" ry="781.128" fill="#1439CC" />
                    <path d="M716.313 408.465C999.039 408.465 1254.98 495.863 1440.22 637.14C1625.47 778.417 1740 973.55 1740 1189.04C1740 1404.54 1625.47 1599.67 1440.22 1740.95C1254.98 1882.23 999.039 1969.62 716.313 1969.62C433.588 1969.62 177.649 1882.23 -7.59766 1740.95C-192.843 1599.67 -307.373 1404.54 -307.373 1189.04C-307.373 973.55 -192.843 778.417 -7.59766 637.14C177.649 495.863 433.588 408.465 716.313 408.465Z" stroke="url(#paint1_linear_3002_7537)" stroke-width="1.09813" />
                </g>
                <g filter="url(#filter3_f_3002_7537)">
                    <ellipse cx="719.925" cy="1349.39" rx="1218.92" ry="929.607" fill="#040813" />
                    <path d="M719.925 420.335C1056.41 420.335 1361.02 524.352 1581.5 692.498C1801.98 860.644 1938.3 1092.9 1938.3 1349.39C1938.3 1605.89 1801.98 1838.14 1581.5 2006.29C1361.02 2174.43 1056.41 2278.45 719.925 2278.45C383.438 2278.45 78.8257 2174.43 -141.652 2006.29C-362.13 1838.14 -498.451 1605.89 -498.451 1349.39C-498.451 1092.9 -362.13 860.644 -141.652 692.498C78.8257 524.352 383.438 420.335 719.925 420.335Z" stroke="url(#paint2_linear_3002_7537)" stroke-width="1.09813" />
                </g>
                <g filter="url(#filter4_f_3002_7537)" style="mix-blend-mode:plus-lighter">
                    <path d="M717.099 408.199C1040.34 408.199 1332.96 508.121 1544.76 669.648C1756.55 831.174 1887.51 1054.28 1887.51 1300.68C1887.51 1547.07 1756.56 1770.18 1544.76 1931.7C1332.96 2093.23 1040.34 2193.15 717.099 2193.15C393.857 2193.15 101.237 2093.23 -110.561 1931.7C-322.358 1770.18 -453.31 1547.07 -453.311 1300.68C-453.311 1054.28 -322.358 831.174 -110.561 669.648C101.237 508.121 393.857 408.199 717.099 408.199Z" stroke="url(#paint3_linear_3002_7537)" stroke-width="1.09813" />
                </g>
                <g filter="url(#filter5_f_3002_7537)" style="mix-blend-mode:plus-lighter">
                    <path d="M717.099 408.199C1040.34 408.199 1332.96 508.121 1544.76 669.648C1756.55 831.174 1887.51 1054.28 1887.51 1300.68C1887.51 1547.07 1756.56 1770.18 1544.76 1931.7C1332.96 2093.23 1040.34 2193.15 717.099 2193.15C393.857 2193.15 101.237 2093.23 -110.561 1931.7C-322.358 1770.18 -453.31 1547.07 -453.311 1300.68C-453.311 1054.28 -322.358 831.174 -110.561 669.648C101.237 508.121 393.857 408.199 717.099 408.199Z" stroke="url(#paint4_linear_3002_7537)" stroke-width="1.09813" />
                </g>
                <g filter="url(#filter6_f_3002_7537)" style="mix-blend-mode:plus-lighter">
                    <ellipse cx="717.099" cy="1300.68" rx="1170.96" ry="893.025" fill="url(#paint5_linear_3002_7537)" fill-opacity="0.1" />
                    <path d="M717.099 408.199C1040.34 408.199 1332.96 508.121 1544.76 669.648C1756.55 831.174 1887.51 1054.28 1887.51 1300.68C1887.51 1547.07 1756.56 1770.18 1544.76 1931.7C1332.96 2093.23 1040.34 2193.15 717.099 2193.15C393.857 2193.15 101.237 2093.23 -110.561 1931.7C-322.358 1770.18 -453.31 1547.07 -453.311 1300.68C-453.311 1054.28 -322.358 831.174 -110.561 669.648C101.237 508.121 393.857 408.199 717.099 408.199Z" stroke="url(#paint6_linear_3002_7537)" stroke-width="1.09813" />
                </g>
                <g filter="url(#filter7_f_3002_7537)" style="mix-blend-mode:plus-lighter">
                    <path d="M717.099 408.199C1040.34 408.199 1332.96 508.121 1544.76 669.648C1756.55 831.174 1887.51 1054.28 1887.51 1300.68C1887.51 1547.07 1756.56 1770.18 1544.76 1931.7C1332.96 2093.23 1040.34 2193.15 717.099 2193.15C393.857 2193.15 101.237 2093.23 -110.561 1931.7C-322.358 1770.18 -453.31 1547.07 -453.311 1300.68C-453.311 1054.28 -322.358 831.174 -110.561 669.648C101.237 508.121 393.857 408.199 717.099 408.199Z" stroke="url(#paint7_linear_3002_7537)" stroke-width="1.09813" />
                </g>
                <g filter="url(#filter8_f_3002_7537)" style="mix-blend-mode:plus-lighter">
                    <path d="M717.099 408.199C1040.34 408.199 1332.96 508.121 1544.76 669.648C1756.55 831.174 1887.51 1054.28 1887.51 1300.68C1887.51 1547.07 1756.56 1770.18 1544.76 1931.7C1332.96 2093.23 1040.34 2193.15 717.099 2193.15C393.857 2193.15 101.237 2093.23 -110.561 1931.7C-322.358 1770.18 -453.31 1547.07 -453.311 1300.68C-453.311 1054.28 -322.358 831.174 -110.561 669.648C101.237 508.121 393.857 408.199 717.099 408.199Z" stroke="url(#paint8_linear_3002_7537)" stroke-width="1.09813" />
                </g>
            </g>
            <defs>
                <filter id="filter0_f_3002_7537" x="6.13922" y="-33.5327" width="1427.57" height="881.799" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix" />
                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                    <feGaussianBlur stdDeviation="137.266" result="effect1_foregroundBlur_3002_7537" />
                </filter>
                <filter id="filter1_f_3002_7537" x="454.179" y="270.649" width="531.493" height="303.084" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix" />
                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                    <feGaussianBlur stdDeviation="54.9065" result="effect1_foregroundBlur_3002_7537" />
                </filter>
                <filter id="filter2_f_3002_7537" x="-321.099" y="394.739" width="2074.82" height="1588.61" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix" />
                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                    <feGaussianBlur stdDeviation="6.58878" result="effect1_foregroundBlur_3002_7537" />
                </filter>
                <filter id="filter3_f_3002_7537" x="-512.178" y="406.609" width="2464.21" height="1885.57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix" />
                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                    <feGaussianBlur stdDeviation="6.58878" result="effect1_foregroundBlur_3002_7537" />
                </filter>
                <filter id="filter4_f_3002_7537" x="-467.037" y="394.472" width="2368.27" height="1812.41" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix" />
                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                    <feGaussianBlur stdDeviation="6.58878" result="effect1_foregroundBlur_3002_7537" />
                </filter>
                <filter id="filter5_f_3002_7537" x="-467.037" y="394.472" width="2368.27" height="1812.41" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix" />
                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                    <feGaussianBlur stdDeviation="6.58878" result="effect1_foregroundBlur_3002_7537" />
                </filter>
                <filter id="filter6_f_3002_7537" x="-462.644" y="398.865" width="2359.48" height="1803.62" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix" />
                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                    <feGaussianBlur stdDeviation="4.39252" result="effect1_foregroundBlur_3002_7537" />
                </filter>
                <filter id="filter7_f_3002_7537" x="-458.252" y="403.257" width="2350.7" height="1794.84" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix" />
                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                    <feGaussianBlur stdDeviation="2.19626" result="effect1_foregroundBlur_3002_7537" />
                </filter>
                <filter id="filter8_f_3002_7537" x="-458.252" y="403.257" width="2350.7" height="1794.84" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix" />
                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                    <feGaussianBlur stdDeviation="2.19626" result="effect1_foregroundBlur_3002_7537" />
                </filter>
                <linearGradient id="paint0_linear_3002_7537" x1="719.924" y1="241" x2="719.924" y2="573.734" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#01033E" />
                    <stop offset="1" stop-color="#050914" />
                </linearGradient>
                <linearGradient id="paint1_linear_3002_7537" x1="716.314" y1="407.917" x2="716.314" y2="605.551" gradientUnits="userSpaceOnUse">
                    <stop stop-color="white" />
                    <stop offset="1" stop-opacity="0" />
                </linearGradient>
                <linearGradient id="paint2_linear_3002_7537" x1="719.925" y1="419.786" x2="719.925" y2="654.988" gradientUnits="userSpaceOnUse">
                    <stop stop-color="white" />
                    <stop offset="1" stop-opacity="0" />
                </linearGradient>
                <linearGradient id="paint3_linear_3002_7537" x1="717.099" y1="407.65" x2="716.626" y2="647.648" gradientUnits="userSpaceOnUse">
                    <stop stop-color="white" />
                    <stop offset="1" stop-opacity="0" />
                </linearGradient>
                <linearGradient id="paint4_linear_3002_7537" x1="717.099" y1="407.65" x2="716.626" y2="514.225" gradientUnits="userSpaceOnUse">
                    <stop stop-color="white" />
                    <stop offset="1" stop-opacity="0" />
                </linearGradient>
                <linearGradient id="paint5_linear_3002_7537" x1="717.099" y1="407.65" x2="717.099" y2="889.668" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#040813" />
                    <stop offset="1" />
                </linearGradient>
                <linearGradient id="paint6_linear_3002_7537" x1="717.099" y1="407.65" x2="716.626" y2="496.106" gradientUnits="userSpaceOnUse">
                    <stop stop-color="white" />
                    <stop offset="1" stop-opacity="0" />
                </linearGradient>
                <linearGradient id="paint7_linear_3002_7537" x1="717.099" y1="407.65" x2="716.626" y2="475.241" gradientUnits="userSpaceOnUse">
                    <stop stop-color="white" />
                    <stop offset="1" stop-opacity="0" />
                </linearGradient>
                <linearGradient id="paint8_linear_3002_7537" x1="717.099" y1="407.65" x2="716.626" y2="462.064" gradientUnits="userSpaceOnUse">
                    <stop stop-color="white" />
                    <stop offset="1" stop-opacity="0" />
                </linearGradient>
                <clipPath id="clip0_3002_7537">
                    <rect width="1440" height="671" fill="white" />
                </clipPath>
            </defs>
        </svg>

    </div>
    <div class="hero-call__star">
        <svg xmlns="http://www.w3.org/2000/svg" width="1440" height="800" viewBox="0 0 1440 800" fill="none">
            <g filter="url(#filter0_f_1902_9883)" style="mix-blend-mode:plus-lighter">
                <g clip-path="url(#paint0_angular_1902_9883_clip_path)" data-figma-skip-parse="true">
                    <g transform="matrix(-1.84296 3.99363 -2.97381 -1.37234 2312.29 -3370.24)">
                        <foreignObject x="-1039.58" y="-1039.58" width="2079.16" height="2079.16">
                            <div xmlns="http://www.w3.org/1999/xhtml" style="background:conic-gradient(from 90deg,rgba(20, 57, 204, 0.5) 0deg,rgba(20, 57, 204, 0.25) 16.9322deg,rgba(20, 57, 204, 0.2) 30.4561deg,rgba(20, 57, 204, 0.0784) 41.383deg,rgba(20, 57, 204, 0.2) 348.768deg,rgba(20, 57, 204, 0.8) 353.261deg,rgba(20, 57, 204, 0.5) 360deg);height:100%;width:100%;opacity:1"></div>
                        </foreignObject>
                    </g>
                </g>
                <path d="M2542.11 -3300.51L2054.68 -2125.96L2733.08 -3201.57L2125.55 -2084.4L2912.66 -3083.21L2191.68 -2035.66L3078.89 -2946.72L2252.36 -1980.28L3229.94 -2793.61L2306.92 -1918.86L3364.15 -2625.54L2354.75 -1852.07L3480.07 -2444.37L2395.34 -1780.64L3576.41 -2252.07L2428.25 -1705.37L3652.12 -2050.76L2453.1 -1627.06L3706.37 -1842.64L2469.64 -1546.59L3738.58 -1629.98L2477.67 -1464.83L3748.37 -1415.13L2477.11 -1382.68L3735.66 -1200.42L2467.97 -1301.04L3700.57 -988.224L2450.35 -1220.8L3643.5 -780.856L2424.43 -1142.84L3565.06 -580.59L2390.51 -1068.02L3466.11 -389.62L2348.95 -997.152L3347.75 -210.039L2300.21 -931.018L3211.26 -43.8131L2244.82 -870.341L3058.15 107.235L2183.4 -815.786L2890.09 241.451L2116.61 -767.95L2708.91 357.364L2045.19 -727.357L2516.62 453.704L1969.91 -694.453L2315.31 529.417L1891.61 -669.598L2107.18 583.671L1811.14 -653.063L1894.53 615.874L1729.38 -645.031L1679.67 625.672L1647.23 -645.588L1464.97 612.957L1565.58 -654.73L1252.77 577.869L1485.34 -672.356L1045.4 520.793L1374.13 -727.357L736.075 520.793L1303.24 -767.95L347.406 745.039L1237.8 -807.396L329.789 353.984L1195.56 -822.494L308.358 88.5622L1134.89 -877.878L157.31 -64.5513L1080.33 -939.302L23.0942 -232.615L1032.49 -1006.09L-92.819 -413.787L991.902 -1077.52L-189.159 -606.083L958.998 -1152.79L-264.872 -807.396L934.142 -1231.09L-319.127 -1015.52L917.608 -1311.57L-351.329 -1228.18L909.575 -1393.33L-361.127 -1443.03L910.133 -1475.48L-348.412 -1657.73L919.275 -1557.12L-313.325 -1869.93L936.901 -1637.36L-256.249 -2077.3L962.818 -1715.32L-177.809 -2277.57L996.741 -1790.14L-78.8661 -2468.54L1038.3 -1861.01L39.4969 -2648.12L1087.04 -1927.14L175.983 -2814.34L1142.42 -1987.82L329.096 -2965.39L1203.85 -2042.37L497.16 -3099.61L1270.64 -2090.21L678.332 -3215.52L1342.06 -2130.8L870.628 -3311.86L1417.34 -2163.7L1071.94 -3387.57L1495.64 -2188.56L1280.06 -3441.83L1576.11 -2205.09L1492.72 -3474.03L1657.87 -2213.13L1707.58 -3483.83L1740.02 -2212.57L1922.28 -3471.11L1821.66 -2203.43L2134.48 -3436.03L1901.9 -2185.8L2341.85 -3378.95L1979.86 -2159.88L2542.11 -3300.51Z" data-figma-gradient-fill="{&quot;type&quot;:&quot;GRADIENT_ANGULAR&quot;,&quot;stops&quot;:[{&quot;color&quot;:{&quot;r&quot;:0.078431375324726105,&quot;g&quot;:0.22352941334247589,&quot;b&quot;:0.80000001192092896,&quot;a&quot;:0.50},&quot;position&quot;:0.0},{&quot;color&quot;:{&quot;r&quot;:0.078431375324726105,&quot;g&quot;:0.22352941334247589,&quot;b&quot;:0.80000001192092896,&quot;a&quot;:0.250},&quot;position&quot;:0.047033976763486862},{&quot;color&quot;:{&quot;r&quot;:0.078431375324726105,&quot;g&quot;:0.22352941334247589,&quot;b&quot;:0.80000001192092896,&quot;a&quot;:0.20000000298023224},&quot;position&quot;:0.084600381553173065},{&quot;color&quot;:{&quot;r&quot;:0.078431375324726105,&quot;g&quot;:0.22352941334247589,&quot;b&quot;:0.80000001192092896,&quot;a&quot;:0.078431375324726105},&quot;position&quot;:0.11495278775691986},{&quot;color&quot;:{&quot;r&quot;:0.078431375324726105,&quot;g&quot;:0.22352941334247589,&quot;b&quot;:0.80000001192092896,&quot;a&quot;:0.20000000298023224},&quot;position&quot;:0.96880066394805908},{&quot;color&quot;:{&quot;r&quot;:0.078431375324726105,&quot;g&quot;:0.22352941334247589,&quot;b&quot;:0.80000001192092896,&quot;a&quot;:0.80000001192092896},&quot;position&quot;:0.98128062486648560},{&quot;color&quot;:{&quot;r&quot;:0.078431375324726105,&quot;g&quot;:0.22352941334247589,&quot;b&quot;:0.80000001192092896,&quot;a&quot;:0.50},&quot;position&quot;:1.0}],&quot;stopsVar&quot;:[{&quot;color&quot;:{&quot;r&quot;:0.078431375324726105,&quot;g&quot;:0.22352941334247589,&quot;b&quot;:0.80000001192092896,&quot;a&quot;:0.50},&quot;position&quot;:0.0},{&quot;color&quot;:{&quot;r&quot;:0.078431375324726105,&quot;g&quot;:0.22352941334247589,&quot;b&quot;:0.80000001192092896,&quot;a&quot;:0.250},&quot;position&quot;:0.047033976763486862},{&quot;color&quot;:{&quot;r&quot;:0.078431375324726105,&quot;g&quot;:0.22352941334247589,&quot;b&quot;:0.80000001192092896,&quot;a&quot;:0.20000000298023224},&quot;position&quot;:0.084600381553173065},{&quot;color&quot;:{&quot;r&quot;:0.078431375324726105,&quot;g&quot;:0.22352941334247589,&quot;b&quot;:0.80000001192092896,&quot;a&quot;:0.078431375324726105},&quot;position&quot;:0.11495278775691986},{&quot;color&quot;:{&quot;r&quot;:0.078431375324726105,&quot;g&quot;:0.22352941334247589,&quot;b&quot;:0.80000001192092896,&quot;a&quot;:0.20000000298023224},&quot;position&quot;:0.96880066394805908},{&quot;color&quot;:{&quot;r&quot;:0.078431375324726105,&quot;g&quot;:0.22352941334247589,&quot;b&quot;:0.80000001192092896,&quot;a&quot;:0.80000001192092896},&quot;position&quot;:0.98128062486648560},{&quot;color&quot;:{&quot;r&quot;:0.078431375324726105,&quot;g&quot;:0.22352941334247589,&quot;b&quot;:0.80000001192092896,&quot;a&quot;:0.50},&quot;position&quot;:1.0}],&quot;transform&quot;:{&quot;m00&quot;:-3685.9162597656250,&quot;m01&quot;:-5947.62597656250,&quot;m02&quot;:7129.060058593750,&quot;m10&quot;:7987.268066406250,&quot;m11&quot;:-2744.6745605468750,&quot;m12&quot;:-5991.54003906250},&quot;opacity&quot;:1.0,&quot;blendMode&quot;:&quot;NORMAL&quot;,&quot;visible&quot;:true}" />
            </g>
            <defs>
                <filter id="filter0_f_1902_9883" x="-461.125" y="-3583.83" width="4309.5" height="4428.87" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix" />
                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                    <feGaussianBlur stdDeviation="50" result="effect1_foregroundBlur_1902_9883" />
                </filter>
                <clipPath id="paint0_angular_1902_9883_clip_path">
                    <path d="M2542.11 -3300.51L2054.68 -2125.96L2733.08 -3201.57L2125.55 -2084.4L2912.66 -3083.21L2191.68 -2035.66L3078.89 -2946.72L2252.36 -1980.28L3229.94 -2793.61L2306.92 -1918.86L3364.15 -2625.54L2354.75 -1852.07L3480.07 -2444.37L2395.34 -1780.64L3576.41 -2252.07L2428.25 -1705.37L3652.12 -2050.76L2453.1 -1627.06L3706.37 -1842.64L2469.64 -1546.59L3738.58 -1629.98L2477.67 -1464.83L3748.37 -1415.13L2477.11 -1382.68L3735.66 -1200.42L2467.97 -1301.04L3700.57 -988.224L2450.35 -1220.8L3643.5 -780.856L2424.43 -1142.84L3565.06 -580.59L2390.51 -1068.02L3466.11 -389.62L2348.95 -997.152L3347.75 -210.039L2300.21 -931.018L3211.26 -43.8131L2244.82 -870.341L3058.15 107.235L2183.4 -815.786L2890.09 241.451L2116.61 -767.95L2708.91 357.364L2045.19 -727.357L2516.62 453.704L1969.91 -694.453L2315.31 529.417L1891.61 -669.598L2107.18 583.671L1811.14 -653.063L1894.53 615.874L1729.38 -645.031L1679.67 625.672L1647.23 -645.588L1464.97 612.957L1565.58 -654.73L1252.77 577.869L1485.34 -672.356L1045.4 520.793L1374.13 -727.357L736.075 520.793L1303.24 -767.95L347.406 745.039L1237.8 -807.396L329.789 353.984L1195.56 -822.494L308.358 88.5622L1134.89 -877.878L157.31 -64.5513L1080.33 -939.302L23.0942 -232.615L1032.49 -1006.09L-92.819 -413.787L991.902 -1077.52L-189.159 -606.083L958.998 -1152.79L-264.872 -807.396L934.142 -1231.09L-319.127 -1015.52L917.608 -1311.57L-351.329 -1228.18L909.575 -1393.33L-361.127 -1443.03L910.133 -1475.48L-348.412 -1657.73L919.275 -1557.12L-313.325 -1869.93L936.901 -1637.36L-256.249 -2077.3L962.818 -1715.32L-177.809 -2277.57L996.741 -1790.14L-78.8661 -2468.54L1038.3 -1861.01L39.4969 -2648.12L1087.04 -1927.14L175.983 -2814.34L1142.42 -1987.82L329.096 -2965.39L1203.85 -2042.37L497.16 -3099.61L1270.64 -2090.21L678.332 -3215.52L1342.06 -2130.8L870.628 -3311.86L1417.34 -2163.7L1071.94 -3387.57L1495.64 -2188.56L1280.06 -3441.83L1576.11 -2205.09L1492.72 -3474.03L1657.87 -2213.13L1707.58 -3483.83L1740.02 -2212.57L1922.28 -3471.11L1821.66 -2203.43L2134.48 -3436.03L1901.9 -2185.8L2341.85 -3378.95L1979.86 -2159.88L2542.11 -3300.51Z" />
                </clipPath>
            </defs>
        </svg>
    </div>
</section>