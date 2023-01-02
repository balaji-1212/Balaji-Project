<?php  if ( ! defined('ABSPATH')) exit('No direct script access allowed');
/**
 * The template for displaying theme prefooter
 *
 * @since   6.2.12
 * @version 1.0.0
 */

$custom_prefooter = etheme_get_query_custom_field('custom_prefooter');
?>

<?php if( $custom_prefooter != 'without' || ( $custom_prefooter == '' && is_active_sidebar('prefooter') ) ): ?>
	<footer class="prefooter">
		<div class="container">
			<?php if(empty($custom_prefooter) && is_active_sidebar('prefooter')): ?>
				<?php dynamic_sidebar('prefooter'); ?>
			<?php elseif(!empty($custom_prefooter)): ?>
				<?php etheme_static_block($custom_prefooter, true); ?>
			<?php endif; ?>
		</div>
	</footer>
<?php endif; ?>