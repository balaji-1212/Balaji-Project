<?php
namespace ETC\App\Controllers\Elementor\Modules;

/**
 * Custom css option.
 *
 * @since      2.0.0
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/Modules
 */
class CSS {

    /**
     * Construct
     */
    function __construct(){
        // Add new controls to advanced tab globally
        if ( defined('ELEMENTOR_PRO_VERSION') ) {
            return;
        }
        
        // Add new controls to setting tab globally
        add_action( 'elementor/element/after_section_end', array( $this, 'add_custom_css_controls_section'), 25, 3 );
        add_action( 'elementor/element/parse_css', array( $this, 'add_post_css' ), 10, 2 );
        add_action( 'elementor/documents/register_controls', array( $this,  'add_custom_page_css' ) );
        add_action( 'elementor/css-file/post/parse', array( $this, 'render_page_css' ) );
	    add_action( 'elementor/css-file/dynamic/parse', array( $this, 'render_page_css' ) );

    }

    /**
     * Add custom css control to all elements
     *
     * @return void
     */
    public function add_custom_css_controls_section( $widget, $section_id, $args ){

        if( 'section_custom_css_pro' !== $section_id ){
            return;
        }

        $widget->start_controls_section(
            'xstore_advanced_custom_css',
            array(
                'label'     => __( 'XStore Custom CSS', 'xstore-core' ),
                'tab'       => \Elementor\Controls_Manager::TAB_ADVANCED
            )
        );

        $widget->add_control(
            'xstore_element_custom_css',
            array(
                'type'        => \Elementor\Controls_Manager::CODE,
                'label'       => __( 'Element Custom CSS', 'xstore-core' ),
                'label_block' => true,
                'language'    => 'css'
            )
        );

        ob_start();?>
<pre>
<?php echo esc_html__('Example', 'xstore-core'); ?>:
selector .child-element{ color: blue; }
</pre>
        <?php
        $message = ob_get_clean();

        $widget->add_control(
            'xstore_custom_css_description',
            array(
                'raw'             => $message,
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor',
                'separator'       => 'none'
            )
        );

        $widget->end_controls_section();

    }

    /**
     * Render custom css
     *
     * @return renderd css
     */
    public function add_post_css( $post_css, $element ) {
        $element_settings = $element->get_settings();

        if ( empty( $element_settings['xstore_element_custom_css'] ) ) {
            return;
        }

        $css = trim( $element_settings['xstore_element_custom_css'] );

        if ( empty( $css ) ) {
            return;
        }

        $css = str_replace( 'selector', $post_css->get_element_unique_selector( $element ), $css );

        // Add a css comment
        $css = sprintf( '/* Start custom CSS for %s, class: %s */', $element->get_name(), $element->get_unique_selector() ) . $css . '/* End custom CSS */';

        $post_css->get_stylesheet()->add_raw_css( $css );
    }

    /**
     * Add custom page css
     *
     * @return control
     */
    public function add_custom_page_css( $page ) {
        if ( isset( $page ) && $page->get_id() > '' ) {
            $page_options_post_type = false;
            $page_options_post_type = get_post_type( $page->get_id() );
            if ( in_array( $page_options_post_type, array('page', 'revision', 'staticblocks') ) ) {
                $page->start_controls_section(
                    'xstore_page_section_custom_css',
                    [
                        'label' => __( 'XStore Custom CSS', 'xstore-core' ),
                        'tab'       => \Elementor\Controls_Manager::TAB_SETTINGS,
                    ]
                );

                $page->add_control(
                    'xstore_page_custom_css',
                    [
                        'type' => \Elementor\Controls_Manager::CODE,
                        'label' => __( 'Page Custom CSS', 'xstore-core' ),
                        'label_block' => true,
                        'language'    => 'css'
                    ]
                );

        ob_start();?>
<pre>
Example:
.element{ color: blue; }
</pre>
            <?php
                $message = ob_get_clean();

                $page->add_control(
                    'xstore_page_custom_css_description',
                    array(
                        'raw'             => $message,
                        'type'            => \Elementor\Controls_Manager::RAW_HTML,
                        'separator'       => 'none'
                    )
                );

                $page->end_controls_section();
            }
        }
    }

    /**
     * Add page css
     * 
     * @param post css
     */
    public function render_page_css( $post_css ) {
        $doc = \Elementor\Plugin::$instance->documents->get( $post_css->get_post_id() );
        $css = $doc->get_settings( 'xstore_page_custom_css' );

        if ( is_null($css) ) {
            return;
        }

        $css = trim( $css );

        if ( empty( $css ) ) {
            return;
        }

        $css = str_replace( 'selector', $doc->get_css_wrapper_selector(), $css );
        
        $post_css->get_stylesheet()->add_raw_css( $css );
    }

}
