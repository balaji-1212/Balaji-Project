<?php
/**
 * The template for displaying multiple each single-product condition of Wordpress customizer
 *
 * @since   3.2.0
 * @version 1.0.0
 */

$Etheme_Customize_Builder = new Etheme_Customize_header_Builder();
$header     = false;
$header     = apply_filters( 'Etheme_Customize_Builder_ajax', $header );
$conditions = $Etheme_Customize_Builder->get_json_option('et_multiple_single_product_conditions');
$languages  = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );
$current_conditions = array();

if ( $header ) {
	foreach ( $conditions as $key => $value ) {
		if ( $header === $value['header'] ) {
			$current_conditions[ $key ] = $value;
		}
	}
}?>

<h3 class="text-left">Setup single product conditions</h3>
<ul class="et_conditions text-left" data-header="<?php echo $header; ?>">


<?php if ( ! count( $current_conditions ) ): ?>
	<li class="et_condition" data-condition="">
		<?php if ($languages): ?>
			<select class="et-languages-select">
				<?php
				$languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );
				foreach ( $languages as $language ) {
					echo '<option value="'.$language['language_code'].'">'.$language['language_code'].'</option>';
				}
				?>
			</select>
		<?php endif; ?>
		<select class="primary-select">
			<?php foreach ($Etheme_Customize_Builder->get_all_single('product') as $key => $value): ?>
				<?php if ( is_array( $value ) && isset( $value['options']) ): ?>
					<?php foreach ($value['options'] as $k => $v): ?>
						<option value='<?php echo esc_attr( json_encode( $v ) ); ?>'><?php echo esc_html( $v['title'] ); ?></option>
					<?php endforeach; ?>
				<?php else: ?>
					<option value='<?php echo $key; ?>'><?php echo esc_html( $value['title'] ); ?></option>
				<?php endif; ?>
			<?php endforeach; ?>
			<option value='{"post_type":"product","type":"all","slug":"in_product","title":"In product"}'>In product</option>
		</select>

        <span class="et_select2-holder hidden">
		    <select class="third-select hidden"></select>
        </span>

		<span class="sub-items-section hidden">
                <label for="sub-items">
                    <input type="checkbox" name="sub-items" value="on" class="sub-items">
                    Sub-Items
                </label>
            </span>

		<div class="et_conditions-actions">
			<span class="et_condition-action et_condition-remove" data-action="remove"></span>
		</div>
	</li>
<?php else: ?>
	<?php foreach ($current_conditions as $key => $condition): ?>
		<?php $show = false; ?>
		<li class="et_condition" data-condition="<?php echo $key ?>">
			<?php if ($languages): ?>
				<select class="et-languages-select">
					<?php
					$languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );
					foreach ( $languages as $language ) {
						echo '<option value="'.$language['language_code'].'" '. selected( $condition['language'], $language['language_code'], false) .'>'.$language['language_code'].'</option>';
					}
					?>
				</select>
			<?php endif; ?>
			<select name="et_contion-select-1" class="primary-select">

				<?php foreach ($Etheme_Customize_Builder->get_all_single('product') as $key => $value): ?>
					<?php if ( is_array( $value ) && isset( $value['options']) ): ?>
						<?php foreach ($value['options'] as $k => $v): ?>

							<option value='<?php echo esc_attr( json_encode( $v ) ); ?>' <?php selected( implode($condition['primary']), implode( $v ) ); ?>>
								<?php echo esc_html( $v['title'] ); ?>
							</option>

						<?php endforeach; ?>
					<?php else: ?>
						<option value='<?php echo $key; ?>' <?php selected( $condition['primary'], $key ); ?>><?php echo esc_html( $value['title'] ); ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
				<option value='{"post_type":"product","type":"all","slug":"in_product","title":"In product"}' <?php selected( implode($condition['primary']), implode( array(
					"post_type"=>"product",
					"type"=>"all",
					"slug"=>"in_product",
					"title"=>"In product"
				) ) ); ?>>In product</option>
			</select>

			<?php
			$default_third = '';
			if ( $condition['third']&&$condition['primary'] ) {
				$Etheme_Customize_Builder = new Etheme_Customize_header_Builder();

				$atts	 = array();
				$atts	['selected'] = $condition['third'];

				$atts['data']     = $condition['primary'];

				$selected = $Etheme_Customize_Builder->condition_select_data($atts	);
				$default_third = '<option value="' . $selected[0]['id'] . '" selected="selected">' . $selected[0]['text'] . '</option>';
			}
			$var_types = array('product_cat', 'product_tag', 'brand', 'category', 'post_tag', 'portfolio_category');
			$sub_items_class = (in_array($condition['primary']['slug'], $var_types)) ? 'active': 'hidden' ;
			?>

            <span class="et_select2-holder <?php echo ( ! $default_third ) ? 'hidden' : ''; ?>">
                <select class="third-select <?php echo ( ! $default_third ) ? 'hidden' : ''; ?>">
                    <?php echo $default_third; ?>
                </select>
            </span>

			<span class="sub-items-section <?php echo $sub_items_class; ?>">
                    <label for="sub-items">
                        <input type="checkbox" name="sub-items" value="on" class="sub-items" <?php echo ( isset($condition['sub-items']) && $condition['sub-items'] == 'true') ? 'checked' : '' ?>>
                        Sub-Items
                    </label>
                </span>
			<div class="et_conditions-actions">
				<span class="et_condition-action et_condition-remove" data-action="remove"></span>
			</div>
		</li>
	<?php endforeach ?>
<?php endif; ?>
</ul>
<div class="et_conditions-actions text-left">
    <span class="et_condition-action et_condition-add et_button et_button-lg et_button-lightgrey hidden" data-action="add"><span class="dashicons dashicons-no-alt" style="transform: rotate(45deg);"></span>Add Condition</span>
    <span class="et_condition-action et_button et_button-lg et_button-green" data-action="save_all" style="display: block; width: 31px; margin-top: 40px;">Save</span>
</div>