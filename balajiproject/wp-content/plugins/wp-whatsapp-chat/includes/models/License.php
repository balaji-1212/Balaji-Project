<?php

class QLWAPP_License extends QLWAPP_Model {

  protected $table = 'license';

  function get_args() {

    $args = array(
        'market' => 'quadlayers',
        'key' => '',
        'email' => ''
    );
    return $args;
  }

}
