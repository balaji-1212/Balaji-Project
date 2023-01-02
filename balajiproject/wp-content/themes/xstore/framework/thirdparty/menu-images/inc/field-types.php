<?php
/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit;

class ET_Menu_Fields {

	public function menu_field_text($item_id, $args, $stored) {
		$output = '';
		ob_start();
		
		$input_type = 'text';
		$attrubites = '';

		if (isset($args['input_type'])) {
			$input_type = $args['input_type'];
		} 

		if (isset($args['attributes'])) {
			foreach ($args['attributes'] as $key => $value) {
				$attrubites .= $key . '="'.$value.'" ';
			}
		} 

		?>
			<label>
				<?php echo esc_html($args['title']); ?><br>
				<input type="<?php echo esc_attr($input_type); ?>" value="<?php echo esc_attr($stored); ?>" <?php if ($input_type === 'number') {echo 'step="0.001"'; } ?> class="widefat" <?php echo wp_specialchars_decode($attrubites); ?> />
			</label>
			<?php if ( isset($args['desc']) && !empty($args['desc'])) : ?>
				<span class="description"><?php echo esc_html($args['desc']); ?></span>
			<?php endif; ?>
		<?php

		$output .= ob_get_clean();

		return $output;

	}

	public function menu_field_select($item_id, $args, $stored) {
		$output = '';
		ob_start();

		?>
			<label>
				<?php echo esc_html($args['title']); ?><br>
				<select name="et-menu-item-<?php echo esc_attr($args['id']) ?>[<?php echo esc_attr($item_id); ?>]" class="widefat">
					<?php foreach ($args['options'] as $key => $value): ?>
						<option value="<?php echo esc_attr($key); ?>" <?php echo selected($key, $stored, false); ?>><?php echo esc_html($value); ?></option>
					<?php endforeach ?>
				</select>
			</label>
			<?php if ( isset($args['desc']) && !empty($args['desc'])) : ?>
				<span class="description"><?php echo esc_html($args['desc']); ?></span>
			<?php endif; ?>
		<?php

		$output .= ob_get_clean();

		return $output;

	}

	public function menu_field_select_default($item_id, $args, $stored) {
		$output = '';
		ob_start();

		?>
			<label>
				<?php echo esc_html($args['title']); ?><br>
				<select name="menu-item-<?php echo esc_attr($args['id']) ?>[<?php echo esc_attr($item_id); ?>]" class="widefat">
					<?php foreach ($args['options'] as $key => $value): ?>
						<option value="<?php echo esc_html($key); ?>" <?php echo selected($key, $stored, false); ?>><?php echo esc_html($value); ?></option>
					<?php endforeach ?>
				</select>
			</label>
			<?php if ( isset($args['desc']) && !empty($args['desc'])) : ?>
				<span class="description"><?php echo esc_html($args['desc']); ?></span>
			<?php endif; ?>
		<?php

		$output .= ob_get_clean();

		return $output;

	}



	public function menu_field_checkbox($item_id, $args, $stored) {
		$output = '';
		ob_start();

		?>
			<input type="checkbox" name="menu-item-<?php echo esc_attr($args['id']); ?>[<?php echo esc_attr($item_id); ?>]"  id="menu-item-<?php echo esc_attr($args['id']); ?>[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($args['value']); ?>" <?php echo checked($stored, $args['value'], false); ?> />
			<label for="menu-item-<?php echo esc_attr($args['id']); ?>[<?php echo esc_attr($item_id); ?>]">
				<?php echo esc_html($args['title']); ?><br>
			</label>
		<?php

		$output .= ob_get_clean();

		return $output;

	}

	public function menu_field_radio($item_id, $args, $stored) {
		$output = '';
		ob_start();

		?>
			<?php foreach ($args['options'] as $key => $value): ?>
				<input type="radio" name="et-menu-item-<?php echo esc_attr($args['id']); ?>[<?php echo esc_attr($item_id); ?>]"  id="et-menu-item-<?php echo esc_attr($key); ?>[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($value); ?>" <?php echo checked($stored, $value, false); ?> />
				<label for="et-menu-item-<?php echo esc_attr($key); ?>[<?php echo esc_attr($item_id); ?>]">
					<?php echo esc_html($value); ?><br>
				</label>
			<?php endforeach; ?>
		<?php

		$output .= ob_get_clean();

		return $output;

	}

	public function render_field( $item_id, $args ) {
		$output = $class = '';
		ob_start();


		$class .= 'field-'.$args['id'];
		$class .= ' description-'.$args['width'];

		if (isset($args['levels'])) {
			if(is_array($args['levels'])) {
				foreach ($args['levels'] as $level) {
					$class .= ' field-level-' . $level;
				}
			} else {
				$class .= ' field-level-' . $args['levels'];
			}
		}

		$stored = get_post_meta($item_id, '_menu-item-' . $args['id'], true);
		$func = 'menu_field_' . $args['type'];

		echo '<p class="description ' . esc_attr($class) . '">' . $this->$func($item_id, $args, $stored) . '</p>'; 

		if ($args['width'] == 'wide'): ?>
			<div class="clear"></div>
		<?php endif;

		$output .= ob_get_clean();

		return $output;
	}


	public function menu_render_fields( $item_id ) {
		$output = '';
		$fields = et_get_menu_fields();

		foreach ($fields as $field) {
			$output .= $this->render_field( $item_id, $field );
		}

		return $output;
	}
}
