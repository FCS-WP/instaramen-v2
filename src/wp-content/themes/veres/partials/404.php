<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Veres
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<main class="site-main" role="main">
  <div class="default-404-content">
    <div class="container">
        <div class="lakit-row">
            <div class="lakit-col default-404-content--img">
                <img src="<?php echo esc_url(get_theme_file_uri( '/assets/images/404.png' )); ?>" width="700" height="480" alt="<?php echo esc_attr_x('Page not found !!', 'front-end', 'veres') ?>" loading="lazy"/>
            </div>
        </div>
        <div class="lakit-row">
            <div class="lakit-col default-404-content--content">
                <div class="default-404-content--inner">
                    <h4><?php echo esc_html_x('Page Cannot Be Found!', 'front-end', 'veres') ?></h4>
                    <div class="button-wrapper"><a class="button" href="<?php echo esc_url(home_url('/')) ?>"><?php echo esc_html_x('Go to home', 'front-view','veres')?></a></div>
                </div>
            </div>
        </div>
    </div>
  </div>
</main>
