<?php
/**
 * The header for our theme.
 *
 * @package     Kutak
 * @since       1.0
 * @author      apalodi
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'kutak' ); ?></a>

<?php do_action( 'apalodi_before_page' ); ?>

<div id="page" class="site">

    <header id="masthead" class="site-header flex align-center-middle">

        <span class="site-actions-backdrop"></span>
        <span class="site-actions-bg"></span>

        <?php apalodi_get_template( 'header/logo' ); ?>
        <?php apalodi_get_template( 'header/site-navigation' ); ?>
        <?php apalodi_get_template( 'header/search' ); ?>

    </header>

    <main id="main" class="site-main">

        <?php do_action( 'apalodi_before_content' ); ?>
