<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Twitter shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Twitter extends VC {

	public function hooks() {
		$this->register_vc_twitter();
	}
	
	public function register_vc_twitter() {

		$strings = $this->etheme_vc_shortcodes_strings();
	    $params = array(
	      'name' => 'Twitter',
	      'base' => 'twitter',
		  'icon' => ETHEME_CODE_IMAGES . 'vc/Twitter.png',
		  'description' => esc_html__('Display Twitter feeds', 'xstore-core'),
	      'params' => array(
	        array(
	          'type' => 'textfield',
	          'heading' => esc_html__('Title', 'xstore-core'),
	          'param_name' => 'title'
	        ),
	        array(
	          'type' => 'textfield',
	          'heading' => esc_html__('Username', 'xstore-core'),
	          'param_name' => 'username'
	        ),
	        array(
	          'type' => 'textfield',
	          'heading' => esc_html__('Customer Key', 'xstore-core'),
	          'param_name' => 'consumer_key'
	        ),
	        array(
	          'type' => 'textfield',
	          'heading' => esc_html__('Customer Secret', 'xstore-core'),
	          'param_name' => 'consumer_secret'
	        ),
	        array(
	          'type' => 'textfield',
	          'heading' => esc_html__('Number of tweets', 'xstore-core'),
	          'param_name' => 'limit'
	        ),
            array(
              'type' => 'dropdown',
              'heading' => esc_html__('Design', 'xstore-core'),
              'param_name' => 'design',
              'value' => array(
	              esc_html__('Grid', 'xstore-core') => 'grid',
                  esc_html__('List', 'xstore-core') => 'list',
	              esc_html__('Slider', 'xstore-core') => 'slider',
                )
            ),
	        array(
	          'type' => 'textfield',
	          'heading' => $strings['heading']['el_class'],
	          'param_name' => 'class',
	          'hint' => $strings['hint']['el_class']
	        )
	      )
	
	    );  
	
	    vc_map($params);
	}

}
