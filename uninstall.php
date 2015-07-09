<?php
/**
 * Copyright © 2009-2014 Rhizome Networks LTD All rights reserved.
 *
 * Created by IntelliJ IDEA.
 * User: Tomer Schilman
 * Date: 08/07/2015
 * Time: 11:34
 */

// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

require_once( 'utils/ModuleUtils.php' );

//function cg_button_uninstall() {
	if ( ! delete_option( CG_BUTTON_APP_ID ) ) {
		file_put_contents('C:/Dropbox/work/test/error_activation.txt', "Failed to delete " . CG_BUTTON_APP_ID);
	}
	if ( ! delete_option( CG_BUTTON_API_KEY ) ) {
		file_put_contents('C:/Dropbox/work/test/error_activation.txt', "Failed to delete " . CG_BUTTON_API_KEY);
	}
	if ( ! delete_option( CG_BUTTON_THEME ) ) {
		file_put_contents('C:/Dropbox/work/test/error_activation.txt', "Failed to delete " . CG_BUTTON_THEME);
	}
	if ( ! delete_option( CG_BUTTON_VERSION ) ) {
		file_put_contents('C:/Dropbox/work/test/error_activation.txt', "Failed to delete " . CG_BUTTON_VERSION);
	}
	if ( ! delete_option( CG_BUTTON_STYLE ) ) {
		file_put_contents('C:/Dropbox/work/test/error_activation.txt', "Failed to delete " . CG_BUTTON_STYLE);
	}
	if ( ! delete_option( CG_BUTTON_SESSION_ID ) ) {
		file_put_contents('C:/Dropbox/work/test/error_activation.txt', "Failed to delete " . CG_BUTTON_SESSION_ID);
	}
	if ( ! delete_option( CG_BUTTON_ENABLE ) ) {
		file_put_contents('C:/Dropbox/work/test/error_activation.txt', "Failed to delete " . CG_BUTTON_ENABLE);
	}
	if ( ! delete_option( CG_BUTTON_LABEL ) ) {
		file_put_contents('C:/Dropbox/work/test/error_activation.txt', "Failed to delete " . CG_BUTTON_LABEL);
	}
//}

//register_uninstall_hook( 'content-glass-button/uninstall.php' , 'cg_button_uninstall' );