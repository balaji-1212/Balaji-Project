<?php
	// Prevent the direct loading

	defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

	// Check if post is pwd protected
	if(post_password_required()){
		?>
		<p><?php esc_html_e('This post is password protected. Enter the password to view the comments.', 'xstore'); ?></p>
		<?php
		return;
	}

	if(have_comments()) :?>
		<div class="comments">
			<h4 class="title-alt">
			<span>
				<?php
				$comments_number = get_comments_number();
				if ( '1' === $comments_number ) {
					printf(
						/* translators: 1: title. */
						esc_html__( 'One comment on &ldquo;%1$s&rdquo;', 'xstore' ),
						'<span>' . wp_kses_post( get_the_title() ) . '</span>'
					);
				} else {
					printf( 
						/* translators: 1: comment count number, 2: title. */
						esc_html( _nx( '%1$s comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', $comments_number, 'comments title', 'xstore' ) ),
						number_format_i18n( $comments_number ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'<span>' . wp_kses_post( get_the_title() ) . '</span>'
					);
				}
				?>
			</span>
			</h4>

			<ul class="comments-list">
				<?php wp_list_comments('callback=etheme_comments'); ?>
			</ul>

			<?php if (get_comment_pages_count() > 1 && get_option('page_comments')): ?>

				<div class="comments-nav">
					<div class="pull-left"><?php previous_comments_link(esc_html__('&larr; Older Comments', 'xstore')); ?></div>
					<div class="pull-right"><?php next_comments_link(esc_html__('Newer Comments &rarr;', 'xstore')); ?></div>
					<div class="clear"></div>
				</div>

			<?php endif ?>

		</div>

	<?php elseif(!comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) : ?>

		<p class="no-comments"><?php esc_html_e('Comments are closed', 'xstore') ?></p>

		<?php
	endif;

	// Display Comment Form
	comment_form(array('title_reply' => '<span>' . esc_html__('Leave a reply', 'xstore') . '</span>'));
?>