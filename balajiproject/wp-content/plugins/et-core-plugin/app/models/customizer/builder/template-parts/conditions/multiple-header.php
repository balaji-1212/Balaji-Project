<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * The template for displaying multiple templates of Wordpress customizer
 *
 * @since   4.1.1
 * @version 1.0.2
 */

$headers = get_option('et_multiple_headers', false);
$headers = json_decode($headers, true);

if ( ! is_array($headers) ) {
    $headers = array();
}

$is_rtl = is_rtl();
$_i = 0;

?>

<ul>
    <?php foreach ($headers as $key => $value): ?>
        <?php
        $_i++;
        $title = ( $value['title'] == 'Default' ) ? $value['title'] . ' header' : $value['title'];
        ?>
        <li data-header="<?php echo $key; ?>" class="<?php echo ( $_i == count($headers) ) ? 'last': ''; echo ( isset($_REQUEST['et_multiple']) && $_REQUEST['et_multiple'] == $key) ? 'editing' : ''; ?>">
            <div class="et_header-left">
				<span class="et_header-title">
                    <b><?php echo $title; ?></b>
                    <?php if ($value['title'] != 'Default'): ?>
                        <input type="text" class="et_title-editor hidden" value="<?php echo $title; ?>">
                        <span class="no-title et_header-action et_button et_button-darktext et_header-edit-name" data-action="edit-name"><span class="dashicons dashicons-edit"></span></span>
                        <span class="no-title et_header-action et_button et_button-darktext et_header-save-name hidden" data-action="save-name"><span class="dashicons dashicons-yes"></span></span>
                    <?php endif; ?>
                </span>
                <span class="et_header-conditions text-nowrap">
					<?php
                    $Etheme_Customize_Builder = new Etheme_Customize_header_Builder();
                    $conditions = $Etheme_Customize_Builder->get_json_option('et_multiple_conditions');
					$languages  = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );
					$default_lang = apply_filters('wpml_default_language', NULL );
                    $titles = $Etheme_Customize_Builder->condition_default_select_data();
                    $i = 0;
                    foreach ( $conditions as $k => $v ) {
                        if ( $i > 1 ) {
                            echo '...'; break;
                        }
                        if ( $key == $v['header'] ) {

                            echo '<span class="et_header-condition">';

                            if ($languages){
                                if (isset($v['language']) && $v['language'] && $v['language'] !=='false'){
	                                echo $v['language'] . '/';
                                } else {
	                                echo $default_lang . '/';
                                }
                            }

                            echo $titles[$v['primary']]['title'];

                            if ( isset( $v['secondary'] ) ) {
                                if ( is_array( $v['secondary'] ) ) {
                                    $post_type = get_post_type_object($v['secondary']['post_type']);
                                    echo '/' . $post_type->label . '/' . $v['secondary']['title'];
                                } elseif ( ! empty( $v['secondary'] ) )  {
                                    echo '/'. $titles[$v['secondary']]['title'];
                                }
                            }
                            if ( isset( $v['third'] ) && ! empty( $v['third'] ) ) {
                                $atts	          = array();
                                $atts['selected'] = $v['third'];

                                $atts['data']     = $v['secondary'];

                                $selected = $Etheme_Customize_Builder->condition_select_data($atts);
                                echo '/'. $selected[0]['text'];
                            }
                            echo "<br></span>";
                            $i++;
                        }
                        if( $value['title'] == 'Default' ) {
                            echo '<span class="et_header-condition">' . esc_html__( 'Entire Site by Default', 'xstore-core' ) . '</span>';
                            break;
                        }
                    }
                    ?>
				</span>
            </div>

            <?php if ( $value['title'] != 'Default' ) $Etheme_Customize_Builder->show_multiple_date($value); ?>

            <span class="et_header-actions">
				<?php if ( $value['title'] != 'Default' ): ?>


                    <span class="et_header-action et_button et_button-darktext et_header-copy" data-action="open-conditions" data-template="<?php echo $key; ?>" data-action-text="<?php echo esc_attr('conditions', 'xstore-core'); ?>"><span class="dashicons dashicons-admin-settings"></span></span>

                    <span class="et_separator">|</span>

                    <span class="et_header-action et_button et_button-darktext" data-action="edit-multiple" data-url="<?php echo esc_js( add_query_arg( array( 'autofocus[panel]' => 'header-builder', 'et_multiple' => $key), wp_customize_url() ) ); ?>" data-action-text="<?php echo esc_attr('Edit', 'xstore-core'); ?>">
                        <span class="dashicons dashicons-edit"></span>
                    </span>

                    <span class="et_separator">|</span>
                    <span class="et_header-action et_button et_button-darktext et_header-copy" data-action="copy" data-action-text="<?php echo esc_attr('Duplicate', 'xstore-core'); ?>"><span class="dashicons dashicons-admin-page"></span></span>

					<span class="et_header-action et_button et_button-darktext et_header-remove" data-action="remove" data-action-text="<?php echo esc_attr('Remove', 'xstore-core'); ?>"><span class="dashicons dashicons-trash"></span></span>
                <?php else : ?>
                    <span class="et_header-action et_button et_button-darktext et_header-copy" data-action="copy_default" data-action-text="<?php echo esc_attr('Duplicate', 'xstore-core'); ?>"><span class="dashicons dashicons-admin-page"></span></span>
                <?php endif; ?>
			</span>
        </li>
    <?php endforeach; ?>
</ul>

<div class="add-new-section">
    <span class="et_header-action et_button et_button-lg et_button-green" data-action="new-template"><?php esc_html_e('Create header', 'xstore-core'); ?></span>
</div>