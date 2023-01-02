<?php
namespace ETC\App\Models\Widgets;

use ETC\App\Models\Widgets;

/**
 * Static block Widget.
 * 
 * @since      1.4.4
 * @version    1.0.2
 * @package    ETC
 * @subpackage ETC/Models/Widgets
 * @log
 * 1.0.1
 * ADDED: select2
 */
class Static_Block extends Widgets {

    public $blocks_count = 0;

    function __construct() {
        $widget_ops = array('classname' => 'etheme_widget_satick_block', 'description' => esc_html__( "Insert a static block", 'xstore-core') );
        parent::__construct('etheme-static-block', '8theme - '.esc_html__('Static Block', 'xstore-core'), $widget_ops);
        $this->alt_option_name = 'etheme_widget_satick_block';
        $this->blocks_count = $this->get_blocks_count();
    }

    function widget($args, $instance) {

        if (parent::admin_widget_preview(esc_html__('Static Block', 'xstore-core')) !== false) return;
	    if ( xstore_notice() ) return;

	    // elementor panel return args as NULL is no changes in widgets, or it opened id elementor editor
	    if (!is_array($args)){
		    $args = array();
	    }

	    if (!is_null($args)) {
		    extract($args);
	    }

	    $block_id = false;

	    if ( isset($instance['block_id_gutenberg']) && !empty($instance['block_id_gutenberg']) ){
		    $block_id = $instance['block_id_gutenberg'];
        }
	    if ( isset($instance['block_id']) && ! empty($instance['block_id']) ){
		    $block_id = $instance['block_id'];
        }

	    echo (isset($before_widget)) ? $before_widget : '';

	    echo parent::etheme_widget_title($args, $instance); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped

        if ( function_exists( 'etheme_static_block' ) ){
	        if (get_theme_mod('static_blocks', true)){
		        if ( $this->blocks_count && $block_id ){
		            if ($this->is_block_exists($block_id)){
			            etheme_static_block( $block_id, true );
                    } else {
			            echo $this->notice('exists', 'woocommerce');
                    }
		        } elseif ( ! $this->blocks_count ){
			        echo $this->notice('empty', 'woocommerce');
		        } elseif ( !$block_id ){
			        echo $this->notice('select', 'woocommerce');
		        }
	        } else {
		        echo $this->notice('disable', 'woocommerce');
	        }
        } else {
	        echo $this->notice('theme', 'woocommerce');
        }
	    echo (isset($after_widget)) ? $after_widget : '';
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title']    = strip_tags($new_instance['title']);
        $instance['block_id'] = $new_instance['block_id'];
	    $instance['block_id_gutenberg'] = isset( $new_instance['block_id_gutenberg'] ) ? $new_instance['block_id_gutenberg'] : false;
        return $instance;
    }

    function form( $instance ) {
        $block_id = 0;
        $title = isset($instance['title']) ? $instance['title'] : '';
	    $rand = rand(1000,10000);
        if(!empty($instance['block_id']))
            $block_id = esc_attr($instance['block_id']);

	    if(!empty($instance['block_id_gutenberg'])){
		    $block_id_gutenberg = esc_attr($instance['block_id_gutenberg']);
	    } else {
		    $block_id_gutenberg = false;
	    }

        global $wp_version;
	    if (
		    version_compare( $wp_version, '5.8', '>=' )
		    && apply_filters( 'gutenberg_use_widgets_block_editor', true )
		    && apply_filters( 'use_widgets_block_editor', true )
	    ){ ?>

		    <?php parent::widget_input_text( esc_html__( 'Widget title:', 'xstore-core' ), $this->get_field_id( 'title' ),$this->get_field_name( 'title' ), $title ); ?>

            <?php
                $sbn = array();
                $sbn[''] = '--Select--';

                if ( $this->blocks_count ) {
	                foreach ( etheme_get_static_blocks() as $item ) {
		                $sbn[$item['value']] = $item['label'];
	                }
                }

                if ($block_id == '--Select--'){
	                $block_id = false;
                }
                parent::widget_input_dropdown( esc_html__( 'Block name:', 'xstore-core' ), $this->get_field_id( 'block_id_gutenberg' ), $this->get_field_name( 'block_id_gutenberg' ), $block_id_gutenberg, $sbn);
            ?>

		    <?php parent::widget_input_text( esc_html__( 'Custom static block id:', 'xstore-core' ), $this->get_field_id( 'block_id' ),$this->get_field_name( 'block_id' ), $block_id ); ?>

		    <?php if( ! get_theme_mod('static_blocks', true) ): ?>
                <p>
	                <?php echo $this->notice('disable');?>
                </p>
		    <?php else: ?>
			    <?php if( ! $this->blocks_count ): ?>
                    <p>
	                    <?php echo $this->notice('empty');?>
                    </p>
			    <?php endif; ?>
                <p>
				    <?php echo $this->notice('gutenberg');?>
                </p>
		    <?php endif; ?>

            <?php return; }
        ?>

        <?php parent::widget_input_text( esc_html__( 'Widget title:', 'xstore-core' ), $this->get_field_id( 'title' ),$this->get_field_name( 'title' ), $title ); ?>
        <p class="et_select2-holder-<?php echo $rand; ?>"><label for="<?php echo $this->get_field_id( 'block_id' ); ?>"><?php esc_html_e( 'Block name:', 'xstore-core' ); ?></label>
            <select class="et_select2-select-<?php echo $rand; ?>" name="<?php echo $this->get_field_name( 'block_id' ); ?>" id="<?php echo $this->get_field_id( 'block_id' ); ?>">
                <option value="">--Select--</option>
                <?php if ( $this->blocks_count && $block_id ): ?>
                    <option value="<?php echo $block_id; ?>" selected><?php echo get_the_title($block_id); ?></option>
                <?php endif ?>
            </select>
                <?php if( ! get_theme_mod('static_blocks', true) ): ?>
                    <p>
                        <?php echo $this->notice('disable');?>
                    </p>
                <?php else: ?>
                    <?php if( ! $this->blocks_count ): ?>
                        <p>
	                        <?php echo $this->notice('empty');?>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            <script>
                jQuery(document).ready(function($) {
                    let select = $('.et_select2-select-<?php echo $rand; ?>');
                    // console.log(select);
                    if (select.hasClass("select2-hidden-accessible")) {
                        select.parent().find('.select2-container').remove();
                    }
                    select.select2({
                        width : '100%',
                        ajax: {
                            url: ajaxurl,
                            dataType: 'json',
                            data: function (params) {
                                var query = {
                                    search: params.term,
                                    action: 'et_ajax_get_static_blocks',
                                }
                                return query;
                            },
                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            }
                        }
                    });
                });
            </script>
        </p>
<?php
    }

    function notice($notice,$type = ''){
        $notices = array(
            'disable' => 'To use this widget, please enable static block via <a href="' . admin_url( '/customize.php?autofocus[section]=general' ) . '" target="_blank">Theme settings -> General/Layout -> Static Blocks</a>',
            'empty' => 'Please publish at least one static block via <a href="' . admin_url( '/edit.php?post_type=staticblocks' ) . '" target="_blank">' . 'Dashboard -> Static Blocks</a>',
            'theme' => 'To use this widget, please enable theme <a href="https://1.envato.market/2rXmmA" target="_blank">' . 'XStore</a> theme',
            'select' => 'Please select static block for this widget',
            'gutenberg' => 'If you don\'t find your static block in "Block name" selector, please go to <a href="' . admin_url( '/edit.php?post_type=staticblocks' ) . '" target="_blank">' . 'Dashboard -> Static Blocks</a> select static block id and paste it to "Custom static block id" field.',
            'exists' => 'Selected static block was removed or unpublished',
            'wrong_notice' => 'Undefined notice selected to show'
        );

	    $notice = ( isset($notices[$notice]) ) ? $notices[$notice] : $notices['wrong_notice'];

	    if ($type == 'woocommerce') {
		    $notice = '<div class="woocommerce-info">' . $notice  . '</div>';
	    }

	    return $notice;
    }

    function get_blocks_count(){
	    global $wpdb;

	    $query = "SELECT COUNT( ID ) as count FROM {$wpdb->posts} WHERE post_type = 'staticblocks'";
	    $results = (array) $wpdb->get_results( $query, ARRAY_A );

        return $results[0]['count'];
    }

    function is_block_exists( $id = '' ){
	    global $wpdb;

	    $query = "SELECT COUNT( ID ) as count FROM {$wpdb->posts} WHERE post_type = 'staticblocks' AND ID = %s AND post_status='publish'";
	    $results = (array) $wpdb->get_results( $wpdb->prepare( $query, $id ), ARRAY_A );

	    return $results[0]['count'];
    }
}