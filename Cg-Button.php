<?php

/**
 * Copyright © 2009-2014 Rhizome Networks LTD All rights reserved.
 *
 * Created by IntelliJ IDEA.
 * User: Tomer Schilman
 * Date: 18/11/14
 * Time: 12:19
 */
class CgButton {
	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'cg_button_addition' ) );
	}

	function cg_button_addition() {
		echo ent2ncr( CgModuleUtils::get_customize_button_dev_script() );
	}
}