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

delete_option( CG_BUTTON_APP_VERSIONS );
delete_option( CG_BUTTON_APP_DATA );
delete_option( CG_BUTTON_APP_TYPE );
delete_option( CG_BUTTON_API_KEY );
delete_option( CG_BUTTON_THEME );
delete_option( CG_BUTTON_VERSION );
delete_option( CG_BUTTON_STYLE );
delete_option( CG_BUTTON_ENABLE );
delete_option( CG_BUTTON_LABEL );