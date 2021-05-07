<?php
/**
 * Template for displaying logo.
 *
 * @package     Kutak/Templates
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly  ?>

<div class="site-branding flex align-middle align-justify">

    <button class="menu-trigger hamburger-menu"><span></span></button>

    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="logo">

        <?php if ( has_apalodi_logo( 'mobile' ) ) : ?>
        <img class="logo-mobile" src="<?php echo esc_url( apalodi_get_logo( 'mobile' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
        <?php endif; ?>

        <img class="logo-default" src="<?php echo esc_url( apalodi_get_logo( 'custom' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
    </a>

    <button class="search-trigger site-action-trigger"><span></span></button>

</div><!-- .site-branding -->
