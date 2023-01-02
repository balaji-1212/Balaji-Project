<?php

class QLWAPP_Settings extends QLWAPP_Model {


	protected $table = 'settings';

	function get_args() {
		$args = array(
			'googleAnalytics'         => 'disable',
			'googleAnalyticsScript'   => 'no',
			'googleAnalyticsV3Id'     => '',
			'googleAnalyticsV4Id'     => '',
			'googleAnalyticsLabel'    => '',
			'googleAnalyticsCategory' => '',
		);

		return $args;
	}

	function save( $scheme = null ) {
		return parent::save_data( $this->table, $scheme );
	}
}
