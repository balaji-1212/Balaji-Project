<?php 

    add_action( 'init', function(){
        if ( !class_exists( 'Kirki' ) ) {
            return;
        }

        $options = array(
            'config' => 'config',
            'general' => 'layout',
            'typography' => 'global',
            'breadcrumbs' => 'breadcrumbs',
            'footer' => 'global',
            'styling' => 'styling',
            'portfolio' => 'portfolio',
            'woocommerce' => ( class_exists('WooCommerce') ? 'global' : 'section' ),
            'blog' => 'global',
            'social-sharing' => 'social-sharing',
            '404-page' => '404-page',
            'custom-css' => 'custom-css',
            'speed-optimization' => 'speed-optimization'
        );

        foreach ($options as $key => $value) {
            require_once apply_filters('etheme_file_url', ETHEME_CODE . 'customizer/theme-options/'.$key.'/'.$value.'.php');   
        }

        do_action( 'customizer_after_including_fields' );
        
        // Config
        Kirki::add_config( 'et_kirki_options', array(
            'capability'    => 'edit_theme_options',
            'option_type'   => 'theme_mod',
        ) );

        // panel
        $panels = apply_filters( 'et/customizer/add/panels', array() );
        foreach ($panels as $panel) {
	        if (isset($panel['id'])) {
		        kirki::add_panel($panel['id'], $panel);
	        }
        }

        // section
        $sections = apply_filters( 'et/customizer/add/sections', array() );
        foreach ($sections as $section) {
	        if (isset($section['name'])) {
		        Kirki::add_section($section['name'], $section);
	        }
        }

        // field
        $fields = apply_filters( 'et/customizer/add/fields', array() );
        foreach ($fields as $field) {
            Kirki::add_field('et_kirki_options', $field);
        }

    });

    // copy full when update will be on 
    if ( ! class_exists( 'Kirki' ) ) {
        global $et_options;

        $base_options = get_template_directory() . '/theme/base-options.php';
        $base_options = require_once $base_options;
        $base_options = json_decode( $base_options, true );
        $et_options   = $base_options;
        return;
    }
    
    