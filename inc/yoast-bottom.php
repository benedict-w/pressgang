<?php

namespace PressGang;

/**
 * Class YoastBottom
 *
 * @package PressGang
 */
class YoastBottom {

	public function __construct() {
		add_filter( 'wpseo_metabox_prio', function () {
			return 'low';
		} );

	}
}

new YoastBottom;