<?php
namespace ETC\App\Controllers\Elementor\Modules;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Control_Media;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
/**
 * General options.
 *
 * @since      2.0.0
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/Modules
 */
class General {

    function __construct(){

	    // Clear Elementor file cache after staticblocks save
	    add_action( 'elementor/editor/after_save', array($this,  'clear_cache'), 10, 2 );

    }

    public function clear_cache( $post_ID, $editor_data ) {
    	if (get_post_type($post_ID) == 'staticblocks'){
		    Plugin::$instance->files_manager->clear_cache();
	    }
    }

}
