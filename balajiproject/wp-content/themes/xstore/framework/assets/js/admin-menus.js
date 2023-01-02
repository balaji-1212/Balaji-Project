jQuery(document).ready(function ($) {
    if (et_variation_gallery_admin.menu_enabled) {
        // Functions for updating et_content in menu item
        function et_update_item() {

            var items = $("ul#menu-to-edit li.menu-item");
            // Go through all items and display link & thumb
            for (var i = 0; i < items.length; i++) {
                var id = $(items[i]).children("#nmi_item_id").val();

                var sibling = $("#edit-menu-item-attr-title-" + id).parent().parent();
                var image_div = $("li#menu-item-" + id + " .nmi-current-image");
                var link_div = $("li#menu-item-" + id + " .nmi-upload-link");
                var other_fields = $("li#menu-item-" + id + " .nmi-other-fields");

                if (image_div) {
                    sibling.after(image_div);
                    image_div.show();
                }
                if (link_div) {
                    sibling.after(link_div);
                    link_div.show();
                }
                if (other_fields) {
                    sibling.after(other_fields);
                    other_fields.show();
                }

            }

            // Save item ID on click on a link
            $(".nmi-upload-link").on("click", function () {
                window.clicked_item_id = $(this).parent().parent().children("#nmi_item_id").val();
            });

            // Display alert when not added as featured
            window.send_to_editor = function (html) {
                alert(nmi_vars.alert);
                tb_remove();
            };
        }

        function ajax_update_item_content() {
            $.ajax({
                url: window.location.href,
                success: function () {
                    if ($('.add-to-menu .spinner').hasClass('is-active')) {
                        ajax_update_item_content();
                    } else {
                        et_update_item();
                    }
                },
            });
            $('.et_item-popup').hide();
        };

        $('.submit-add-to-menu').on("click", function () {

            ajax_update_item_content();

        });

        // end et_content items

        var menu_id = $('#menu').val();

        // Visibility option
        $(document).on('change', '.field-item_visibility select', function () {
            var item = $(this).closest('.menu-item');
            var id = $(item).find('.menu-item-data-db-id').val();
            var el_vis = $(item).find('.field-item_visibility select').val();
            changed_settings = true;

            function et_refresh_item_visibility(id, el_vis) {
                if ($('ul#menu-to-edit').find('input.menu-item-data-parent-id[value="' + id + '"]').length > 0) {
                    var child = $('ul#menu-to-edit').find('input.menu-item-data-parent-id[value="' + id + '"]').closest('.menu-item');
                    var select = child.find('.field-item_visibility select');
                    var c_vis = select.val();
                    if (c_vis != el_vis) {
                        select.val(el_vis).change();
                        var id = child.find('.menu-item-data-db-id').val();
                        et_refresh_item_visibility(id, el_vis);
                    }
                }
            }

            et_refresh_item_visibility(id, el_vis);
        });

        // Open options

        $(document).on('click', '.item-type', function () {
            var parent = $(this).closest('.menu-item');
            parent.prepend('<div class="popup-back"></div>');
            var menu_setgs = $(parent).find('.menu-item-settings');
            var children = $(parent).find('.et_item-popup');
            $(children).addClass('popup-opened');
            $('body').addClass('et_modal-opened');
            if ($(parent).hasClass('menu-item-edit-inactive')) {
                $(parent).removeClass('menu-item-edit-inactive').addClass('menu-item-edit-active');
            }
            $(menu_setgs).css('display', 'block');
            $(children).show();
        });

        // Single item
        $(document).on('click', '.et-saveItem, .popup-back', function () {
            if ($('body').hasClass('et_modal-opened')) {

                var el = $(this).closest('.menu-item');
                var children = el.find('.et_item-popup');

                if ($(this).hasClass('et-close-modal')) {
                    if ($(children).hasClass('popup-opened')) {
                        $(children).removeClass('popup-opened').hide();
                        $('body').removeClass('et_modal-opened');
                        el.find('.popup-back').remove();
                    }
                    return;
                }

                $(children).addClass('et-saving');

                var db_id = anchor = design = dis_titles = column_width = column_height = columns = icon_type =
                    icon = item_label = background_repeat = background_position = background_position = use_img = open_by_click = sublist_width = '';

                db_id = el.find('.menu-item-data-db-id').val();


                anchor = el.find('.field-anchor input').val();
                design = el.find('.field-design select option:selected').val();
                design2 = el.find('.field-design2 select option:selected').val();
                dis_titles = el.find('.field-disable_titles input:checked').val() ? 1 : 0;
                column_width = el.find('.field-column_width input').val();
                column_height = el.find('.field-column_height input').val();
                sublist_width = el.find('.field-sublist_width input').val();
                columns = el.find('.field-columns select option:selected').val();
                icon_type = el.find('.field-icon_type select option:selected').val();
                icon = el.find('.field-icon input').val();
                item_label = el.find('.field-label select option:selected').val();
                background_repeat = el.find('.field-background_repeat select option:selected').val();
                background_position = el.find('.field-background_position select option:selected').val();
                widget_area = (el.hasClass('menu-item-depth-1') || el.hasClass('menu-item-depth-2')) ? el.find('.field-widget_area select option:selected').val() : '';
                static_block = el.find('.field-static_block select option:selected').val();
                use_img = el.find('.field-use_img select option:selected').val();
                // open_by_click = el.find('.field-open_by_click input:checked').val() ? 1 : 0;
                // visibility = el.find('.field-item_visibility select option:selected').val();

                item_menu = {
                    db_id: db_id,
                    anchor: anchor,
                    design: design,
                    design2: design2,
                    column_width: column_width,
                    column_height: column_height,
                    columns: columns,
                    icon_type: icon_type,
                    icon: icon,
                    item_label: item_label,
                    background_repeat: background_repeat,
                    background_position: background_position,
                    widget_area: widget_area,
                    static_block: static_block,
                    use_img: use_img,
                    sublist_width: sublist_width
                };

                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        'action': 'et_update_menu_ajax',
                        's_meta': 'item',
                        'item_menu': item_menu,
                        'menu_id': menu_id,
                    },
                    success: function (data) {
                        if ($(children).hasClass('popup-opened')) {
                            $(children).removeClass('et-saving').removeClass('popup-opened').hide();
                            $('body').removeClass('et_modal-opened');
                            el.find('.popup-back').remove();
                        }
                    },
                });
            }
        });

        // Remove item
        // $("a.item-delete").addClass('custom-remove-item');
        // $("a.custom-remove-item").removeClass('item-delete');
        //
        // $(document).on('click', '.custom-remove-item', function (e) {
        //     e.preventDefault();
        //     button = $(this);
        //     delid = button.attr('id');
        //     var itemID = parseInt(button.attr('id').replace('delete-', ''), 10);
        //     button.addClass('item-delete');
        //     ajaxdelurl = button.attr('href');
        //     $.ajax({
        //         type: 'GET',
        //         url: ajaxdelurl,
        //         beforeSend: function (xhr) {
        //             button.text('Removing...');
        //         },
        //         success: function (data) {
        //             button.text('Remove');
        //             $("#" + delid).trigger("click");
        //         }
        //     });
        //     return false;
        // });
    }
});