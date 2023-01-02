<div id="qlwapp" class="qlwapp-free <?php printf('qlwapp-%s qlwapp-%s qlwapp-%s qlwapp-%s', esc_attr($button['layout']), esc_attr($button['position']), esc_attr($display['devices']), esc_attr($button['rounded'] === 'yes' ? 'rounded' : 'square')); ?>">
	<div class="qlwapp-container">
		<?php if ($button['box'] === 'yes') : ?>
			<div class="qlwapp-box">
				<?php if (!empty($box['header'])) : ?>
					<div class="qlwapp-header">
						<i class="qlwapp-close" data-action="close">&times;</i>
						<div class="qlwapp-description">
							<div class="qlwapp-description-container">
								<?php echo wpautop(wp_kses_post(wpautop($box['header']))); ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<div class="qlwapp-body">
					<?php if (isset($contacts[0])) : ?>
						<a class="qlwapp-account" data-action="open" data-phone="<?php echo qlwapp_format_phone($contacts[0]['phone']); ?>" data-message="<?php echo esc_html($button['message']); ?>" href="javascript:void(0);" target="_blank">
							<?php if (!empty($contacts[0]['avatar'])) : ?>
								<div class="qlwapp-avatar">
									<div class="qlwapp-avatar-container">
										<img alt="<?php printf('%s %s', esc_html($contacts[0]['firstname']), esc_html($contacts[0]['lastname'])); ?>" src="<?php echo esc_url($contacts[0]['avatar']); ?>">
									</div>
								</div>
							<?php endif; ?>
							<div class="qlwapp-info">
								<span class="qlwapp-label"><?php echo esc_html($contacts[0]['label']); ?></span>
								<span class="qlwapp-name"><?php printf('%s %s', esc_html($contacts[0]['firstname']), esc_html($contacts[0]['lastname'])); ?></span>
							</div>
						</a>
					<?php endif; ?>
				</div>
				<?php if (!empty($box['footer'])) : ?>
					<div class="qlwapp-footer">
						<?php echo wpautop(wp_kses_post($box['footer'])); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<a class="qlwapp-toggle" data-action="<?php echo ($button['box'] === 'yes' ? 'box' : 'open'); ?>" data-phone="<?php echo qlwapp_format_phone($button['phone']); ?>" data-message="<?php echo esc_html($button['message']); ?>" href="javascript:void(0);" target="_blank">
			<?php if ($button['icon']) : ?>
				<i class="qlwapp-icon <?php echo esc_attr($button['icon']); ?>"></i>
			<?php endif; ?>
			<i class="qlwapp-close" data-action="close">&times;</i>
			<?php if ($button['text']) : ?>
				<span class="qlwapp-text"><?php echo esc_html($button['text']); ?></span>
			<?php endif; ?>
		</a>
	</div>
</div>