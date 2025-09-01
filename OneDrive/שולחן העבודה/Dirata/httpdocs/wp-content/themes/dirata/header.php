<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <!-- קריאה לספריית select2 -->
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- קריאה לספריית photoswipe -->

    <!-- <link rel="stylesheet" href="https://unpkg.com/photoswipe@5/dist/photoswipe.css" />
    <script src="https://unpkg.com/photoswipe@5/dist/photoswipe.umd.min.js"></script>
    <script src="https://unpkg.com/photoswipe@5/dist/photoswipe-lightbox.umd.min.js"></script> -->



    

    <meta charset="utf-8">
    <meta name="description" content="HTML5 Template">
    <meta name="keywords" content="app, landing, corporate, Creative, Html Template, Template">
    <meta name="author" content="shal3v">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Title -->
    <title><?php bloginfo('name'); ?><?php wp_title('|'); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/assets/img/logo.svg" type="image/png">

    <!-- Enqueue WordPress Styles and Scripts -->
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <!-- Header start -->
    <header>
        <div class="container container-header" style="padding-right: 2vw; padding-left: 2vw;">
            <nav class="d-flex align-items-center justify-content-between">
                <a class="logo" href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.svg" alt="logo">
                </a>
                <!-- <ul>
                    <li>
                        <a href="<?php echo site_url('/apartments-search/'); ?>">
                            מאגר דירתא
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            איך קונים דירה
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            איך מוכרים דירה
                        </a>
                    </li>
                </ul> -->
                <ul class="main-menu">
                    <li>
                        <a href="<?php echo site_url('/apartments-search/'); ?>">מאגר דירתא</a>
                    </li>
                    <li class="has-submenu">
                    <a href="#">איך קונים דירה
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/sub-menu-icon.svg" alt="אייקון תפריט" class="submenu-icon">
                    </a>
                        <ul class="submenu">
                            <li><a href="/about-dirata">כך מוצאים דירה בקלות</a></li>
                            <li><a href="/apartments-search">בחירת איזור חיפוש</a></li>
                            <li class="has-submenu-2">
                            <a href="#">
                                3 שלבים לקניית דירה
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/sub-menu-icon.svg" alt="אייקון תפריט" class="submenu-icon">
                            </a>
                                <ul class="submenu-2">
                                    <li><a href="<?php echo site_url('/כך-מחפשים-נכון/'); ?>">כך מחפשים נכון</a></li>
                                    <li><a href="<?php echo site_url('/להגיע-מוכנים-לתשלום/'); ?>">להגיע מוכנים לתשלום</a></li>
                                    <li><a href="<?php echo site_url('/מה-שחשוב-לדעת-ברכישה/'); ?>">מה שחשוב לדעת ברכישה</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                    <a href="#">איך מוכרים דירה
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/sub-menu-icon.svg" alt="אייקון תפריט" class="submenu-icon">
                    </a>
                        <ul class="submenu">
                            <li><a href="/about-dirata">כך מוכרים דירה בקלות</a></li>
                            <li><a href="/profile-page/?tab=publish" style="display: none;">פרסום דירה במאגר</a></li>
                            <li class="has-submenu-2">
                            <a href="#">
                                מה שחשוב בתהליך המכירה
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/sub-menu-icon.svg" alt="אייקון תפריט" class="submenu-icon">
                            </a>

                                <ul class="submenu-2">
                                <li><a href="<?php echo site_url('/למצוא-את-הקונה-הנכון/'); ?>">למצוא את הקונה הנכון</a></li>
                                <li><a href="<?php echo site_url('/למכור-נכון/'); ?>">למכור נכון</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>

                <div>
                <?php
                $is_logged_in = 0;
                $is_logged_in = is_user_logged_in();
                ?>

                <a class="header_btn advertising_website_btn" href="<?php echo $is_logged_in ? site_url('/profile-page') : wp_login_url(); ?>">
                    <?php echo $is_logged_in ? 'אזור אישי' : 'בחירת אזור \ התחברות'; ?>
                </a>

                <?php
                $current_user = wp_get_current_user();
                $roles = (array) $current_user->roles;

                if (in_array('broker', $roles)) {
                    $link = site_url('/profile-page/?tab=publish');
                } else {
                    $link = site_url('/upload-apartment-for-personal/');
                }
                ?>

                <a class="header_btn select_search_btn" href="<?php echo esc_url($link); ?>">
                    פרסום דירה ←
                </a>

                </div>
            </nav>
        </div>
        
        <!-- Mobile Menu Area Start -->
        <nav class="mobile_menu_wrap">
            <ul>
                <li>
                    <a href="<?php echo site_url('/apartments-search/'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                           <!-- SVG content for דירות למכירה -->
                           <!-- <path d="M...Z" fill="#20315F"/> -->
                        </svg>
                        <p>דירות <br>למכירה</p>
                    </a>
                </li>
                <li>
                    <a class="active" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none">
                           <!-- SVG content for מי אנחנו -->
                           <!-- <path d="M...Z" fill="#20315F"/> -->
                        </svg>
                        <p>מי <br>אנחנו</p>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                           <!-- SVG content for לאזור האישי -->
                           <!-- <path d="M...Z" fill="#20315F"/> -->
                        </svg>
                        <p>לאזור <br>האישי</p>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                           <!-- SVG content for לפרסום דירה -->
                           <!-- <path d="M...Z" fill="#20315F"/> -->
                        </svg>
                        <p>לפרסום <br>דירה</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- Mobile Menu Area End -->
    </header>
    <!-- Header end -->