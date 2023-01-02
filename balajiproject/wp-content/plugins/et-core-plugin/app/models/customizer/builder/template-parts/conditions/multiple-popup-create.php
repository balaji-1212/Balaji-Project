<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 *
 */

$builder = isset( $_POST['builder'] ) ? $_POST['builder'] : 'header';
?>

<div class="new-template-popup text-left">
    <div style="margin-bottom: 40px;">
        <?php

        printf( '<h3>Name of the %s</h3> <p><input class="et_add-new-multiple" placeholder="Name your %s" name="et_add-new-header" type="text"></p>',
	        ($builder == 'header') ? 'header' : 'single product',
	        ($builder == 'header') ? 'header' : 'single product',
        );

        ?>
    </div>
    <div >

        <?php require_once( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/conditions/multiple-condition-'.$builder.'.php' );  ?>
    <div>


    <div style="margin-top: 40px;">
<!--        <span class="et_button et_button-lg et_button-green et_header-action et_header-add" data-action="popup-add">Create</span>-->
        <span class="et_button et_button-lg et_button-green et_header-action et_header-add" data-action="popup-add-open" data-url="<?php echo esc_js( wp_customize_url() ); ?>">Create & Edit</span>
    </div>
</div>

