<?php
/**
 * Template "Tnstagram" for 8theme dashboard.
 *
 * @since   6.3.0
 * @version 1.0.3
 */
$instagram_API = apply_filters(
	'etheme_admin_popup_instagram_types',
	array(
		'personal' => array(
			'enabled' => true,
			'checked' => false,
			'display' => true,
			'url' => $_POST['personal']
		),
		'business' => array(
			'enabled' => true,
			'checked' => true,
			'display' => true,
			'url' => $_POST['business']
		),
	)
);

$key  = array_search(true, (array_column($instagram_API, 'checked')));
$keys = array_keys($instagram_API);
$key  = $keys[$key];
?>
    <div class="et_popup-instagram-content text-left">

	    <?php if( $instagram_API['business']['display'] ) : ?>
            <p style="<?php echo (!$instagram_API['business']['enabled']) ? 'pointer-events: none; opacity: 0.5;' : ''; ?>">
                <label for="business">
                    <input
                            type="radio"
                            id="business"
                            name="type"
                            value="business"
					    <?php if ($instagram_API['business']['checked']) echo 'checked=""'; ?>
                            data-url="<?php echo esc_url($instagram_API['business']['url']); ?>">
                    <b><?php esc_html_e('Business','xstore'); ?></b> <?php esc_html_e('(Based on Grap API. Allows you to show media from the Instagram account of the Business type and media by hashtags. Recommended for owners of the Business Instagram accounts) ', 'xstore'); ?>
                </label>
            </p>
	    <?php endif; ?>

	    <?php if( $instagram_API['personal']['display'] ) : ?>
            <p style="<?php echo (!$instagram_API['personal']['enabled']) ? 'pointer-events: none; opacity: 0.5;' : ''; ?>">
            <label for="personal">
                <input
                        type="radio"
                        id="personal"
                        name="type"
                        value="personal"
					    <?php if ($instagram_API['personal']['checked']) echo 'checked=""'; ?>
                        data-url="<?php echo esc_url($instagram_API['personal']['url']); ?>">
                <b><?php esc_html_e('Personal', 'xstore'); ?></b> <?php esc_html_e('(Based on Basic Display API. Allows you to show only your own Instagram media. Recommended for owners of the personal Instagram accounts )', 'xstore') ?>
            </label>
        </p>
        <?php endif; ?>

        <p class="align-center" style="margin-bottom: 0;">
            <a class="
		   et-button et-button-green et-get-token et-facebook-corporate"
               href="<?php echo esc_url($instagram_API[$key]['url']); ?>">
                <span class="dashicons dashicons-instagram"></span>
				<?php esc_html_e('Add account', 'xstore'); ?>
            </a>
        </p>
    </div>
<?php
