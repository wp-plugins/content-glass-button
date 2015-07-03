<?php
/**
 * Copyright © 2009-2014 Rhizome Networks LTD All rights reserved.
 *
 * Created by IntelliJ IDEA.
 * User: Tomer Schilman
 * Date: 21/11/14
 * Time: 09:14
 */

define( 'CG_BUTTON_DEFAULT_THEME', 'redmond' );
define( 'CG_BUTTON_DEFAULT_LABEL', 'Content Glass' );
define( 'CG_BUTTON_DEFAULT_VERSION', 'latest' );
define( 'CG_BUTTON_DEFAULT_APP_ID', '5513c675ag580' );
define( 'CG_BUTTON_DEFAULT_FONT_SIZE', 14 );
define( 'CG_BUTTON_DEFAULT_LEFT', 0 );
define( 'CG_BUTTON_DEFAULT_TOP', 0 );
define( 'CG_BUTTON_DEFAULT_POSITION', 'absolute' );
define( 'CG_BUTTON_DEFAULT_APPLICATION_DATA', '{"button": {"name": "button", "type": "button"}}' );
define( 'CG_BUTTON_DEFAULT_APPLICATION', 'button' );

define( 'CG_BUTTON_APP_ID', 'cg_button_app_id' );
define( 'CG_BUTTON_API_KEY', 'cg_button_api_key' );
define( 'CG_BUTTON_THEME', 'cg_button_default_theme' );
define( 'CG_BUTTON_VERSION', 'cg_button_version' );
define( 'CG_BUTTON_LABEL', 'cg_button_label' );
define( 'CG_BUTTON_TOP', 'cg_button_top' );
define( 'CG_BUTTON_LEFT', 'cg_button_left' );
define( 'CG_BUTTON_POSITION', 'cg_button_position' );
define( 'CG_BUTTON_FONT_SIZE', 'cg_button_font_size' );
define( 'CG_BUTTON_ENABLE', 'cg_button_enable' );
define( 'CG_BUTTON_APPLICATION_DATA', 'cg_button_application_data' );
define( 'CG_BUTTON_APPLICATION', 'cg_button_application' );

//TODO for propduction chnage to false
//define( 'CG_DEV_MODE', true );
define( 'CG_DEV_MODE', false );
define( 'XDEBUG', 15229 );
define( 'XDEBUG_TOKEN', '&XDEBUG_SESSION_START=' );

//TODO for production change to api url
define( 'CG_BUTTON_SERVER_URL', 'http://api.contentglass.com' );
//define( 'CG_BUTTON_SERVER_URL', 'http://local.contentglass.com' );

class CGModuleUtils {
	public static function get_system_scripts() {
		$content = file_get_contents( 'ContentGlassSystemScripts.html', FILE_USE_INCLUDE_PATH );
		$appId = get_option( CG_BUTTON_APP_ID );
		if ( null === $appId ) {
			$appId = '';
		}
		$content = self::replace_all( $content, '[APP_ID]', $appId, 8 );

		$apiKey = get_option( CG_BUTTON_API_KEY );
		if ( null === $apiKey ) {
			$apiKey = '';
		}
		$content = self::replace_all( $content, '[API_KEY]', $apiKey, 9 );

		$apps_data = json_decode( self::get_application_types() );
		if ( $apps_data->status === 1 ) {
			$apps_data = $apps_data->data->applications;
		} else {
			$apps_data = json_decode( CG_BUTTON_DEFAULT_APPLICATION_DATA );
		}
		$app_type = '' . get_option( CG_BUTTON_APPLICATION );
		if ( null === $app_type ) {
			$app_type = CG_BUTTON_DEFAULT_APPLICATION;
		}
		if ( isset( $apps_data->{$app_type} ) ) {
			$type = $apps_data->{$app_type};
		} else {
			$type = $apps_data->{CG_BUTTON_DEFAULT_APPLICATION};
		}

		$content = self::replace_all( $content, '[APP_DATA]', json_encode( $type ), 10 );

		$theme = get_option( CG_BUTTON_THEME );
		if ( null === $theme ) {
			$theme = "'" . CG_BUTTON_DEFAULT_THEME . "'";
		} else {
			$theme = "'" . $theme . "'";
		}
		$content = self::replace_all( $content, '[DEFAULT_THEME]', $theme, 15 );

		$version = get_option( CG_BUTTON_VERSION );
		if ( null === $version ) {
			$version = CG_BUTTON_DEFAULT_VERSION;
		}
		$content = self::replace_all( $content, '[CG_VERSION]', $version, 12 );

		$content = self::replace_all( $content, '[CG_SERVER]', CG_BUTTON_SERVER_URL, 11 );

		if ( CG_DEV_MODE ) {
			$content = self::replace_all( $content, '[DEV_MODE]', '&DEV=true', 10 );
			$content = self::replace_all( $content, '[XDEBUG]', XDEBUG_TOKEN . XDEBUG, 8 );
		} else {
			$content = self::replace_all( $content, '[DEV_MODE]', '', 10 );
			$content = self::replace_all( $content, '[XDEBUG]', '', 8 );
		}

		return $content;
	}

	public static function get_customize_button_dev_script() {
		$content = file_get_contents( 'CustomizeButtonScript.html', FILE_USE_INCLUDE_PATH );
		$top = get_option( CG_BUTTON_TOP );
		if ( null === $top ) {
			$top = CG_BUTTON_DEFAULT_TOP;
		}
		$content = self::replace_all( $content, '[BUTTON_TOP]', $top, 12 );

		$left = get_option( CG_BUTTON_LEFT );
		if ( null === $left ) {
			$left = CG_BUTTON_DEFAULT_LEFT;
		}
		$content = self::replace_all( $content, '[BUTTON_LEFT]', $left, 13 );

		$position = get_option( CG_BUTTON_POSITION );
		if ( null === $position ) {
			$position = CG_BUTTON_DEFAULT_POSITION;
		}
		$content = self::replace_all( $content, '[BUTTON_POSITION]', $position, 17 );

		$fontSize = get_option( CG_BUTTON_FONT_SIZE );
		if ( null === $fontSize ) {
			$fontSize = CG_BUTTON_DEFAULT_FONT_SIZE;
		}
		$content = self::replace_all( $content, '[BUTTON_SIZE]', $fontSize, 13 );

		$label = get_option( CG_BUTTON_LABEL );
		if ( null === $label ) {
			$label = CG_BUTTON_DEFAULT_LABEL;
		}
		$content = self::replace_all( $content, '[CG_LABEL]', $label, 10 );

		return $content;
	}

	public static function get_widget_script( $label, $size ) {
		$content = file_get_contents( 'WidgetButtonScript.html', FILE_USE_INCLUDE_PATH );
		$content = self::replace_all( $content, '[CG_LABEL]', $label, 10 );
		$content = self::replace_all( $content, '[BUTTON_SIZE]', $size, 13 );

		return $content;
	}

	private static function replace_all( $string, $str1, $str2, $length ) {
		while ( ( $off = strpos( $string, $str1 ) ) !== false ) {
			$string = substr_replace( $string, $str2, $off, $length );
		}

		return $string;
	}

	public static function get_versions() {
		$url = CG_BUTTON_SERVER_URL . '/server_api/s1/application/get-versions?XDEBUG_SESSION_START=' . XDEBUG;

		return self::send_get_request( $url );
	}

	public static function get_application_types() {
		$url = CG_BUTTON_SERVER_URL . '/server_api/s1/application/get-applications?XDEBUG_SESSION_START=' . XDEBUG;

		return self::send_get_request( $url );
	}

	private static function send_get_request( $url ) {
		$curl = curl_init();

		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

		$result = curl_exec( $curl );
		curl_close( $curl );

		return $result;
	}
}