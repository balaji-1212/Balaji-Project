<?php
/**
 * The template for displaying header "connect block" block
 *
 * @since   1.4.0
 * @version 1.0.1
 */
 ?>


<?php

    $package = 'connect_block_package';

    $package = apply_filters( 'connect_block_package', $package );

    $blocks = get_theme_mod( $package );

    if ( $blocks && count( $blocks ) ) {

        $blockId = false;

        $blockId = apply_filters( 'et_connect_block_id', $blockId );

        if ( $blockId !== false ) {
            $key  = array_search( $blockId, array_column( $blocks, 'id' ) );
            $data = json_decode( $blocks[$key]['data'], true );

            if ( ! is_array( $data ) ) {
                $data = array();
            }

            uasort( $data, function ( $item1, $item2 ) {
                return $item1['index'] <=> $item2['index'];
            });

            $class = 'flex-col';
            if ( $blocks[$key]['type'] == 'horizontal' ) {
                $class = 'flex-row';
            }

            $separator = '';
            if ( isset($blocks[$key]['separator']) && $blocks[$key]['separator'] == 'true' ) {
                $separator = '<span class="et_connect-block-sep"></span>';
                $separator = apply_filters('et_connect_block_sep', $separator);
            }

            $blocks[$key]['spacing'] = isset($blocks[$key]['spacing']) ? $blocks[$key]['spacing'] : '5';
            $blocks[$key]['spacing'] = $blocks[$key]['spacing'] . 'px';

            $class .= ' connect-block-'. $blockId;
            $class .= ' align-items-center';
            $class .= ' justify-content-' . $blocks[$key]['align'];

            ob_start();
            ?>
                .connect-block-<?php echo $blockId ?> {
                    --connect-block-space: <?php echo $blocks[$key]['spacing']; ?>;
                    margin: <?php echo ( $blocks[$key]['type'] == 'horizontal' ) ? '0 ' . '-'.$blocks[$key]['spacing'] : '-'.$blocks[$key]['spacing'] . ' 0'; ?>;
                }
                .et_element.connect-block-<?php echo $blockId ?> > div,
                .et_element.connect-block-<?php echo $blockId ?> > form.cart,
                .et_element.connect-block-<?php echo $blockId ?> > .price {
                    margin: <?php echo ( $blocks[$key]['type'] == 'horizontal' ) ? '0 ' . $blocks[$key]['spacing'] : $blocks[$key]['spacing'] . ' 0'; ?>;
                }
                <?php if ( $blocks[$key]['type'] == 'horizontal' ) : ?>
                    .et_element.connect-block-<?php echo $blockId ?> > .et_b_header-widget > div, 
                    .et_element.connect-block-<?php echo $blockId ?> > .et_b_header-widget > ul {
                        margin-left: <?php echo $blocks[$key]['spacing']; ?>;
                        margin-right: <?php echo $blocks[$key]['spacing']; ?>;
                    }
                    .et_element.connect-block-<?php echo $blockId ?> .widget_nav_menu .menu > li > a {
                        margin: <?php echo '0 ' . $blocks[$key]['spacing']; ?>
                    }
/*                    .et_element.connect-block-<?php echo $blockId ?> .widget_nav_menu .menu .menu-item-has-children > a:after {
                        right: <?php echo $blocks[$key]['spacing']; ?>;
                    }*/
                <?php else: ?>
                    .et_element.connect-block-<?php echo $blockId ?> > .et_b_header-widget > div, 
                    .et_element.connect-block-<?php echo $blockId ?> > .et_b_header-widget > ul {
                        margin-top: <?php echo $blocks[$key]['spacing']; ?>;
                        margin-bottom: <?php echo $blocks[$key]['spacing']; ?>;
                    }
                <?php endif;
                $style = ob_get_clean();
                
                $inline_style = apply_filters('et_connect_block_inline_css', true);
                
                if ( $inline_style ) {
                    echo '<style>'.$style.'</style>';
                }
                else {
	                wp_add_inline_style( 'xstore-inline-css', $style );
                }

            $count = 0;

            echo '<div class="et_element et_connect-block flex ' . $class . '">';
                foreach ( $data as $key => $value ) {
                    $count++;
                    if ( in_array( $package, array( 'connect_block_package', 'connect_block_mobile_package' ) ) ) {
                        require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/' . $key . '.php' );
                    } else {
                        do_action($key);
                    }
                    if ( $count > 0 && $count < count($data)) {
                        echo $separator;
                    }
                }
            echo '</div>';
        }
    }
?>
