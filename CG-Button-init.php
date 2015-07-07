<?php
/**
 * Copyright © 2009-2014 Rhizome Networks LTD All rights reserved.
 *
 * Created by IntelliJ IDEA.
 * User: Tomer Schilman
 * Date: 18/11/14
 * Time: 12:57
 */


require( 'CG-Button.php' );
require( 'CG-Button-widget.php' );
require( 'CG-Button-menu-link.php' );//All constant are loaded form CG-Button-menu-link.php.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function cg_button_authorize(){
	$appId = esc_attr( get_option( CG_BUTTON_APP_ID ) );
	$apiKey = esc_attr( get_option( CG_BUTTON_API_KEY ) );
	$url = CG_AUTH_URL . '?api_key=' . $apiKey . '&app_id=' . $appId . '&RHZ_SESSION_ID=' . get_option( CG_BUTTON_SESSION_ID );
	if ( CG_DEV_MODE ) {
		$url = $url . XDEBUG_TOKEN . XDEBUG;
	}
	$data = CGModuleUtils::send_get_request( $url );
	$result = json_decode( $data );
	if ( $result->status === 1 ) {
		$accessToken = $result->data->access_token;
		update_option( CG_BUTTON_SESSION_ID, $result->data->session_id );
		echo ent2ncr( CGModuleUtils::get_system_scripts( $accessToken ) );
	} else {
		global $error;
		$error = json_decode( $result->message );
		function cg_button_notice() {
			global $pagenow;
			if ( current_user_can( 'install_plugins' ) ) {
				if ( 'admin.php' === $pagenow ) {
					global $error;
					echo '<div class="error"><p>' . $error->message . '</p></div>';
				}
			}
		}
		add_action( 'admin_notices', 'cg_button_notice' );
	}
}

add_action( 'init', function () {
	cg_button_authorize();
	if ( get_option( CG_BUTTON_ENABLE ) === 'Enable' ) {
		new CgButton();
	}
} );

// register widget
function register_cg_widget() {
	if ( get_option( CG_BUTTON_WIDGET_ENABLE ) === 'Enable' ) {
		unregister_widget( 'CgButtonWidget' );
		register_widget( 'CgButtonWidget' );
	}
}

add_action( 'widgets_init', 'register_cg_widget' );


