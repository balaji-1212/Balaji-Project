<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Texts for builder js notices.
 *
 * @since   1.5.4
 * @version 1.0.1
 * @log
 * 1.0.1
 * Added save-text
 */
return array(
    'load-builder'              	=> esc_html__( 'Ajax error: can not load builder', 'xstore-core' ),
    'switch-header'             	=> esc_html__( 'Ajax error: can not switch header to builder or default', 'xstore-core' ),
    'switch-single-product'     	=> esc_html__( 'Ajax error: can not switch single-product to builder or default', 'xstore-core' ),
    'reset-header-builder'      	=> esc_html__( 'Ajax error: can not reset header builder to default.', 'xstore-core' ),
    'wrong-multiple-section'    	=> esc_html__( 'Error: wrong multiple section selected', 'xstore-core' ),
    'load-multiple'             	=> esc_html__( 'Ajax error: can not load multiple section, file with this template does not exist' ),
    'default-multiple-template' 	=> esc_html__( 'Ajax error: can not create a default template', 'xstore-core' ),
    'name-your-template'        	=> esc_html__( 'Name your template, please', 'xstore-core' ),
    'save-active-template-options'  => esc_html__( 'Ajax error: can not save active template options', 'xstore-core' ),
    'multiple-action-error' 		=> esc_html__( 'Ajax error: multiple template action error. action - ', 'xstore-core' ),
    'conditions-save'               => esc_html__( 'Ajax error: can not save conditions', 'xstore-core' ),
    'conditions-select-data'        => esc_html__( 'Ajax error: can not load conditions selects data', 'xstore-core' ),
    'get-preset'                    => esc_html__( 'Ajax error: can not get prset data, file with this preset does not exist', 'xstore-core' ),
    'set-preset'                    => esc_html__( 'Ajax error: can not set prset data', 'xstore-core' ),
    'add-column'                    => esc_html__( 'Ajax error: can not add new column', 'xstore-core' ),
    'remove-inside-column'          => esc_html__( 'Ajax error: can not remove data from column', 'xstore-core' ),
    'remove-all-inside-block'       => esc_html__( 'Ajax error: can not remove data from connect block', 'xstore-core' ),
    'block-popup'                   => esc_html__( 'Ajax error: can not load popup for current connect block', 'xstore-core' ),
    'block-style-popup'             => esc_html__( 'Ajax error: can not load popup for current connect block', 'xstore-core' ),
	'save-text'                     => esc_attr__('save', 'xstore-core'),
    'save-close-text'               => esc_attr__('save & close', 'xstore-core'),
);