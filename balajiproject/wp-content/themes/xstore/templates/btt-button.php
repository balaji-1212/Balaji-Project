<?php  if ( ! defined('ABSPATH')) exit('No direct script access allowed');
/**
 * The template for displaying theme back to top btn
 *
 * @since   6.4.5
 * @version 1.0.0
 */
$class = 'back-top';
if ( ! get_query_var('et_btt', false) ) {
	$class .= ' dt-hide';
}
if ( ! get_query_var('et_btt-mobile', false) ) {
	$class .= ' mob-hide';
}

if (get_query_var('et_btt', false) || get_query_var('et_btt-mobile', false) || get_query_var('et_is_customize_preview', false)): ?>
    <div id="back-top" class="<?php echo esc_attr( $class ); ?>">
        <span class="et-icon et-right-arrow-2"></span>
        <svg width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 150.621;" fill="none"></path>
        </svg>
    </div>
<?php
wp_enqueue_script( 'back-top' );
endif;