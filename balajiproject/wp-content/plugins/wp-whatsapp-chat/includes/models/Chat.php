<?php

class QLWAPP_Chat extends QLWAPP_Model {

    protected $table = 'chat';

    function get_args() {

        $args = array(
//            'contact' => $contact,
            'emoji' => 'no',
        );
        return $args;
    }

}
