<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div class="content-area">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Hrm
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
    <!-- BEGIN: Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- END: Google Font -->

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Mobile menu offcanvas -->
<?php Hrm_Templates::mobile_menu_offcanvas_template(); ?>

<!-- Header Area Start -->
<header>
    <div class="header-1">
        <div class="header-logo">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <img src="<?php echo get_template_directory_uri() . '/assets/img/logo/logo.png' ?>" alt="Header Logo">
            </a>
        </div>
        <div class="mega-menu-wrapper">
            <div class="header-main">
                <div class="header-left">
                    <div class="mean__menu-wrapper d-none d-lg-block">
                        <div class="main-menu">
                            <nav id="mobile-menu">
	                            <?php
	                            wp_nav_menu(
		                            array(
			                            'theme_location' => 'primary',
			                            'menu_id'        => '',
			                            'menu_class'     => '',
		                            )
	                            );
	                            ?>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="header-right d-flex justify-content-end align-items-center">
                    <div class="header-button">
                        <!--<i data-feather="user"></i>-->
                    </div>
                    <div class="header__hamburger d-lg-none my-auto">
                        <div class="sidebar__toggle">
                            <a class="bar-icon" href="javascript:void(0)">
                                <img src="<?php echo get_template_directory_uri() . '/assets/img/logo/bars.svg' ?>" alt="bar-img">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="content-area">

