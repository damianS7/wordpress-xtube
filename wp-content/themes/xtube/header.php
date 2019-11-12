<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Xtube
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="profile" href="https://gmpg.org/xfn/11" />
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <a class="navbar-brand" href="#">
                <img class="img-fluid" src="<?php ?>" alt="" />
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01"
                aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarColor01">
                <?php wp_nav_menu(array('container' => '', 'menu_class' => 'navbar-nav mr-auto', 'theme_location'  => 'header-nav')); ?>
                <?php wp_nav_menu(array('container' => '', 'menu_class' => 'dropdown-menu', 'theme_location' => 'header-nav-drop')); ?>
                <form action="<?php echo home_url() . '/search/';?>" class="form-inline my-2 my-lg-0" method="get">
                    <input class="form-control mr-sm-2" name="keyword" type="text" placeholder="Search">
                    <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </nav>
    </header>