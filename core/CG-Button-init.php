<?php
/**
 * Copyright © 2009-2014 Rhizome Networks LTD All rights reserved.
 *
 * Created by IntelliJ IDEA.
 * User: Tomer Schilman
 * Date: 18/11/14
 * Time: 12:57
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// We want to try and append the floating button element
// before the system script run so it will recognize it and render it.
require_once( 'CG-Button.php' );
if ( ! defined( 'CG_BUTTON_ENABLE' ) ) {
	define( 'CG_BUTTON_ENABLE', 'cg_button_enable' );
}

require_once( 'CG-Button-menu-link.php' );//All constant are loaded form CG-Button-menu-link.php.

add_action( 'init', function () {
	if ( get_option( CG_BUTTON_ENABLE ) === 'Enable' ) {
		new CgButton();
	}
	/**
	 * Register the given styles to be loaded in the admin pages and in other pages.
	 */
	add_action( 'wp_enqueue_scripts', 'cg_button_styles' );
	add_action( 'admin_enqueue_scripts', 'cg_button_styles' );
} );

function cg_button_styles() {
	wp_enqueue_style(
		'content-glass-override-style',
		plugins_url( '/content-glass-button/css/CGWordpressOverride.css' )
	);
}