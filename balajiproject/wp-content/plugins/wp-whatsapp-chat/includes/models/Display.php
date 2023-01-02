<?php

include_once(QLWAPP_PLUGIN_DIR . 'includes/models/Display_Component.php');

class QLWAPP_Display extends QLWAPP_Model {

    protected $table = 'display';

    // Entries and Taxonomies = array of Display_Component
    function get_args() {
        $display_component_model = new Display_Component();

        return $display_component_model->get_args();
    }

    function save($display_data = NULL) {
        return parent::save_data($this->table, $display_data);
    }

}
