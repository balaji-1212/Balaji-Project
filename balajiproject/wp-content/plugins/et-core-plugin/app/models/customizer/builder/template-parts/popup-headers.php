<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * The template for displaying headers popup of Wordpress customizer
 *
 * @since   1.0.0
 * @version 1.0.0
 */

?>

<div class="et_popup et_popup-presets et_popup-headers ui-draggable ui-draggable-handle">
    <div class="et_actions-1">
        <span class="dashicons dashicons-move"></span>
        <span><?php esc_html_e( 'Headers', 'xstore-core' ); ?></span>
        <span class="dashicons dashicons-no-alt et_close"></span>
    </div>
    <div class="et_inside-wrapper">
        <p class="header-save-holder"><input id="new_header" type="text"><span class="et_button header-save">Add new</span></p>
		 <div class="et_headers-list">
        	<?php 
        		$path = ET_CORE_DIR . 'app/models/customizer/builder/headers/';
        		$files = scandir($path);
    			foreach ($files as $key => $value) {
                    if ( strpos($value, '-mob.options.' ) ) continue;
    				if ( strpos($value, '.options.' ) !== false && file_exists($path . '/' . $value) ) {
    					$title = explode('.', $value);
    					echo '<p class="header-vesion" data-id="' . $title[0] . '">'. $title[0] .'</p>';
    				}
    			}
        	 ?>
        </div>
    </div>
    
</div>


<style>
    .et_popup.saving{
        pointer-events: none;
    }
    .et_popup.saving:before{
        color: #fff;
        line-height: 20;
        text-align: center;
        content: 'saving';
        position: absolute;
        width: 100%;
        height: 100%;
        background: rgba(42, 42, 42, 0.5);
        text-transform: uppercase;
    }
    .et_popup-headers{
        height: 300px!important;
        overflow: scroll;
    }
    .header-save-holder{
        text-align: center;
    }
    #new_header{
        margin-bottom: 10px;
    }
    .et_button.header-save{
        background: green;
        color: white!important;
        margin-bottom: 10px;
    }
    .header-vesion{
        cursor: pointer;
        border-bottom: 1px #00000026 dashed;
        padding-bottom: 5px;
    }


    .et_popup-presets, .et_popup-preset, .et_popup-presets .et_button, .et_popup-preset .et_button{
        color: #000;
    }

    .pull-left, .pull-right{
        width: 50%;
        display: inline-block;
    }

    .et_popup-presets li {
        margin-bottom: 20px;
        display: inline-block;
    }

    .pull-left{
        float: left;
        text-align: left;
    }

    .pull-left span{
        width: 100%;
        display: inline-block;
        
    }

    .pull-right{
        float: right;
        text-align: right;
    }
        
    .et_popup-presets ul {
        overflow: scroll;
        max-height: 150px;
    }


   .et_popup-presets .et-title, .et_popup-presets .et-image, .et_popup-presets .preset-new, .et_popup-presets .et_notice{
        width: 100%;
        display: inline-block;
   }


</style>