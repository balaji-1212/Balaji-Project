<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );

/**
 * The template for displaying customizer search
 *
 * @version 0.1
 * @since 0.1
 */
?>

<script type="text/html" id="et_search-form">
    <div class="et_customize-search">
        <div class="et_search-wrapper empty">
            <input type="text"
                placeholder="<?php _e( 'Search for options', 'xstore' ); ?>"
                name="et_customizer-search"
                autofocus="autofocus"
                id="et_customizer-search"
                class="et_customizer-search-input">
                <span class="et_clear-search"><span class="dashicons dashicons-no-alt"></span></span>
        </div>
        <ul id="et_customizer-search-results" 
            data-length="<?php esc_html_e( 'Please, enter at least 3 symbols.', 'xstore' ); ?>"
            data-empty="<?php esc_html_e( 'No results were found.', 'xstore' ); ?>"></ul>
    </div>

     <?php if(isset($_REQUEST['et_multiple'])): ?>
         <style>
             body .wp-full-overlay-sidebar .wp-full-overlay-sidebar-content{
                 top: 60px!important;
             }
             #customize-header-actions{
                 background: none;
                 height: 0;
             }
             #accordion-panel-header-builder,
             .collapse-sidebar.button,
             #sub-accordion-panel-header-builder>.panel-meta.customize-info.accordion-section,
             .customize-controls-close,
             .customize-pane-parent .accordion-section:not(#accordion-panel-header-builder) h3
             {
                 display: none !important;
             }
             .et_customize-search{
                 width: 100%;
             }
             #customize-control-go_to_section_ajax_added_product_notify_popup_products_type{
                 display: none;
             }
             #sub-accordion-panel-single_product_builder li:first-child{
                 display: none;
             }
         </style>
     <?php endif; ?>

</script>