<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Instagram shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Instagram extends VC {

	function hooks() {
		$this->register_vc_scslug();
	}

	function register_vc_scslug() {
		
		$is_admin = $this->is_admin();
		
		$users    = array( '' => '' );

		if ( $is_admin ) {
			$api_data = get_option( 'etheme_instagram_api_data' );
			$api_data = json_decode($api_data, true);
			if ( is_array( $api_data ) && count( $api_data ) ) {
				foreach ( $api_data as $key => $value ) {
					$value = json_decode( $value, true );
					if ( isset( $value['data']['username'] ) ) {
						$users[ $value['data']['username'] . ' (old API)' ] = $key;
					} else {
						$users[ $value['username'] ] = $key;
					}
				}
			}
		}

		$strings = $this->etheme_vc_shortcodes_strings();
		$counter = 0;
		$params = array(
			'name' => 'Instagram',
			'base' => 'instagram',
			'icon' => ETHEME_CODE_IMAGES . 'vc/Instagram.png',
			'description' => esc_html__('Display Instagram feeds with slider or grid', 'xstore-core'),
			'category' => $strings['category'],
			'params' => array_merge(array(
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Content', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Title', 'xstore-core'),
					'param_name' => 'title',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Instagram account', 'xstore-core' ),
					'description' => '<a href="' . admin_url('admin.php?page=et-panel-social'). '" target="_blank">'. esc_html__('Add Instagram account?', 'xstore-core') . '</a>',
					'param_name' => 'user',
					'admin_label' => true,
					'value' => $users
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Hashtag', 'xstore-core'),
					'param_name' => 'username',
					'description' => 'Only for Instagram business users',
					'admin_label' => true
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Sort by', 'xstore-core' ),
					'param_name' => 'tag_type',
					'value' => array(
						esc_html__( 'Recent media', 'xstore-core' ) => 'recent_media',
						esc_html__( 'Top media', 'xstore-core' ) => 'top_media',
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Numer of photos', 'xstore-core'),
					'param_name' => 'number',
					'description' => esc_html__('Do not forget to ', 'xstore-core') . '<a href="' . admin_url('admin.php?page=et-panel-social'). '" target="_blank">'. esc_html__('reinit Instagram', 'xstore-core') . '</a> ' . esc_html__('after change number of photos ', 'xstore-core'),
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Images settings', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__( 'Photo size', 'xstore-core' ),
					'param_name' => 'size',
					'value' => array(
						esc_html__( 'Thumbnail', 'xstore-core' ) => 'thumbnail',
						esc_html__( 'Medium', 'xstore-core' ) => 'medium',
						esc_html__( 'Large', 'xstore-core' ) => 'large',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__( 'Type', 'xstore-core' ),
					'param_name' => 'img_type',
					'value' => array(
						esc_html__( 'Squared', 'xstore-core' ) => 'squared',
						esc_html__( 'Default', 'xstore-core' ) => 'default',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Layout', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__( 'Type', 'xstore-core' ),
					'param_name' => 'type',
					'value' => array(
						esc_html__( 'Element', 'xstore-core' ) => 'element',
						esc_html__( 'Widget', 'xstore-core' ) => 'widget',
					),
					'edit_field_class' => 'vc_col-sm-4 vc_column',
				),
                array(
                    'type' => 'xstore_slider',
                    'heading' => esc_html__( 'Columns', 'xstore-core' ),
                    'param_name' => 'columns',
                    'min' => 2,
                    'max' => 6,
                    'step' => 1,
                    'default' => 2,
                    'units' => '',
                    'edit_field_class' => 'vc_col-sm-8 vc_column',
                ),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Link settings', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__( 'Open links in', 'xstore-core' ),
					'param_name' => 'target',
					'value' => array(
						esc_html__( 'Current window', 'xstore-core' ) => '_self',
						esc_html__( 'New window', 'xstore-core' ) => '_blank',
					),
				),
				array(
					'type' => 'textfield',
					'hint' => esc_html__('Leave field empty to not showing this link after images', 'xstore-core'),
					'heading' => esc_html__('"Follow us" text', 'xstore-core'),
					'param_name' => 'link',
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Advanced', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Slider', 'xstore-core' ),
					'param_name' => 'slider',
				  	'dependency' => array(
				  		'element' => 'type',
				  		'value_not_equal_to' => 'widget',
				  	),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Without spacing', 'xstore-core' ),
					'param_name' => 'spacing',
				),
				array(
					'type' => 'checkbox',
					'heading' => $strings['heading']['ajax'],
					'param_name' => 'ajax',
				),
			), $this->get_slider_params( array( 'element' => 'slider', 'value' => 'true' ) ) )

		);  

		vc_map($params);
	}

}
