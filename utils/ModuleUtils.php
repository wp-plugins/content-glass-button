<?php
/**
 * Copyright © 2009-2014 Rhizome Networks LTD All rights reserved.
 *
 * Created by IntelliJ IDEA.
 * User: Tomer Schilman
 * Date: 21/11/14
 * Time: 09:14
 */

require_once( 'StringUtils.php' );

define( 'CG_BUTTON_DEFAULT_THEME', 'redmond' );
define( 'CG_BUTTON_DEFAULT_LABEL', 'Content Glass' );
define( 'CG_BUTTON_DEFAULT_VERSION', 'latest' );
define( 'CG_BUTTON_TEST_APP_ID', '559a1b7f3de3a' );
define( 'CG_BUTTON_TEST_API_KEY', '559a1a7d380b3-dev' );
define( 'CG_BUTTON_DEFAULT_FONT_SIZE', 14 );
define( 'CG_BUTTON_DEFAULT_STYLE', '' );
define( 'CG_BUTTON_DEFAULT_APPLICATION_DATA', '{"button": {"name": "button", "type": "button"}}' );
define( 'CG_BUTTON_DEFAULT_APPLICATION', 'button' );

define( 'CG_BUTTON_APP_ID', 'cg_button_app_id' );
define( 'CG_BUTTON_API_KEY', 'cg_button_api_key' );
define( 'CG_BUTTON_THEME', 'cg_button_default_theme' );
define( 'CG_BUTTON_VERSION', 'cg_button_version' );
define( 'CG_BUTTON_STYLE', 'cg_button_style' );
define( 'CG_BUTTON_LABEL', 'cg_button_label' );
define( 'CG_BUTTON_FONT_SIZE', 'cg_button_font_size' );
define( 'CG_BUTTON_ENABLE', 'cg_button_enable' );
define( 'CG_BUTTON_APPLICATION_DATA', 'cg_button_application_data' );
define( 'CG_BUTTON_APPLICATION', 'cg_button_application' );

//TODO for propduction chnage to prod
define( 'CG_DEV_MODE', 'prod' );
//define( 'CG_DEV_MODE', 'dev' );
//define( 'CG_DEV_MODE', 'local' );
define( 'XDEBUG', 19544 );
define( 'XDEBUG_TOKEN', 'XDEBUG_SESSION_START=' );

if ( CG_DEV_MODE === 'local' ) {
	define( 'CG_BUTTON_SERVER_URL', '//local.contentglass.com' );
} elseif ( CG_DEV_MODE === 'dev' ) {
	define( 'CG_BUTTON_SERVER_URL', '//dev.contentglass.com' );
} else {
	define( 'CG_BUTTON_SERVER_URL', '//api.contentglass.com' );
}
define( 'CG_AUTH_URL', 'http:' . CG_BUTTON_SERVER_URL . '/server_api/p1/security/authorize' );
define( 'ABS_PLUGIN_PATH', ABSPATH . 'wp-content/plugins/content-glass-button' );


class CGModuleUtils {
	public static function get_system_scripts($accessToken) {
		$content = file_get_contents( __DIR__ . '/../templates/ContentGlassSystemScripts.html' );
		$appId = get_option( CG_BUTTON_APP_ID );
		if ( null === $appId ) {
			$appId = '';
		}
		$content = StringUtils::replace_all( $content, '[APP_ID]', $appId, 8 );

		$content = StringUtils::replace_all( $content, '[ACCESS_TOKEN]', $accessToken, 14 );

		$sessId = isset( $_COOKIE['wp_rhz_session_id'] ) === true? $_COOKIE['wp_rhz_session_id'] : '';
		$content = StringUtils::replace_all( $content, '[SESSION_ID]', $sessId, 12 );

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

		$content = StringUtils::replace_all( $content, '[APP_DATA]', json_encode( $type ), 10 );

		$theme = get_option( CG_BUTTON_THEME );
		if ( null === $theme ) {
			$theme = "'" . CG_BUTTON_DEFAULT_THEME . "'";
		} else {
			$theme = "'" . $theme . "'";
		}
		$content = StringUtils::replace_all( $content, '[DEFAULT_THEME]', $theme, 15 );

		$version = get_option( CG_BUTTON_VERSION );
		if ( null === $version ) {
			$version = CG_BUTTON_DEFAULT_VERSION;
		}
		$content = StringUtils::replace_all( $content, '[CG_VERSION]', $version, 12 );

		$content = StringUtils::replace_all( $content, '[CG_SERVER]', CG_BUTTON_SERVER_URL, 11 );

		if ( CG_DEV_MODE === 'local' ) {
			$content = StringUtils::replace_all( $content, '[DEV_MODE]', '&DEV=true', 10 );
			$content = StringUtils::replace_all( $content, '[XDEBUG]', XDEBUG_TOKEN . XDEBUG, 8 );
		} else if ( CG_DEV_MODE === 'dev' ) {
			$content = StringUtils::replace_all( $content, '[DEV_MODE]', '&DEV_HOST=true', 10 );
			$content = StringUtils::replace_all( $content, '[XDEBUG]', '', 8 );
		} else {
			$content = StringUtils::replace_all( $content, '[DEV_MODE]', '', 10 );
			$content = StringUtils::replace_all( $content, '[XDEBUG]', '', 8 );
		}

		return $content;
	}

	public static function get_system_scripts_params($accessToken){
		$params = array();
		$appId = get_option( CG_BUTTON_APP_ID );
		if ( null === $appId ) {
			$appId = '';
		}
		$params['APP_ID'] = $appId;
		$params['ACCESS_TOKEN'] = $accessToken;

		$sessId = isset($_COOKIE['wp_rhz_session_id']) === true? $_COOKIE['wp_rhz_session_id'] : '';
		$params['SESSION_ID'] = $sessId;

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
		$params['APP_DATA'] = json_encode( $type );

		$theme = get_option( CG_BUTTON_THEME );
		if ( null === $theme ) {
			$theme = "'" . CG_BUTTON_DEFAULT_THEME . "'";
		} else {
			$theme = "'" . $theme . "'";
		}
		$params['DEFAULT_THEME'] = $theme;

		$version = get_option( CG_BUTTON_VERSION );
		if ( null === $version ) {
			$version = CG_BUTTON_DEFAULT_VERSION;
		}
		$params['CG_VERSION'] = $version;
		$params['CG_SERVER'] = CG_BUTTON_SERVER_URL;

		if ( CG_DEV_MODE === 'local' ) {
			$params['DEV_MODE'] = 'DEV=true';
			$params['XDEBUG'] = XDEBUG_TOKEN . XDEBUG;
		} else if ( CG_DEV_MODE === 'dev' ) {
			$params['DEV_MODE'] = 'DEV_HOST=true';
			$params['XDEBUG'] = '';
		} else {
			$params['DEV_MODE'] = '';
			$params['XDEBUG'] = '';
		}

		return $params;
	}

	public static function parse_params( $params ) {
		$paramsStr = '';
		foreach ( $params as $key => $value ) {
			$paramsStr = $paramsStr . $key . '=' . $value . '&';
		}
		$paramsStr = substr( $paramsStr, 0, strlen( $paramsStr ) - 1 );
		return $paramsStr;
	}

	public static function get_customize_button_dev_script() {
		$content = file_get_contents( __DIR__ . '/../templates/CustomizeButtonScript.html', FILE_USE_INCLUDE_PATH );

		if ( false !== $content ) {
			$label = get_option( CG_BUTTON_LABEL );
			if ( null === $label ) {
				$label = CG_BUTTON_DEFAULT_LABEL;
			}
			$content = StringUtils::replace_all( $content, '[CG_LABEL]', $label, 10 );

			$style = get_option( CG_BUTTON_STYLE );
			$style = StringUtils::replace_all( $style, "\n", '', 1 ); //we remove new lines if the user enter some
			$style = StringUtils::replace_all( $style, "\r", '', 1 );
			if ( strpos( $style, 'position' ) === false ) {
				$style = $style . 'position:fixed;';
			}
			$content = StringUtils::replace_all( $content, '[CG_STYLE]', $style, 10 );

			return $content;
		} else {
			echo 'Error in getting floating button script';
		}
	}

	public static function get_customize_button_params(){
		$params = array();

		$label = get_option( CG_BUTTON_LABEL );
		if ( null === $label ) {
			$label = CG_BUTTON_DEFAULT_LABEL;
		}
		$params['label'] = $label;
		$style = get_option( CG_BUTTON_STYLE );
		$style = StringUtils::replace_all( $style, "\n", '', 1 ); //we remove new lines if the user enter some
		$style = StringUtils::replace_all( $style, "\r", '', 1 );
		if ( strpos( $style, 'position' ) === false ) {
			$style = $style . 'position:fixed;';
		}
		$params['style'] = $style;
		return $params;
	}

	public static function get_widget_script( $label, $style) {
		$content = file_get_contents( __DIR__ . '/../templates/WidgetButtonScript.html', FILE_USE_INCLUDE_PATH );
		$content = StringUtils::replace_all( $content, '[CG_LABEL]', $label, 10 );

		$style = StringUtils::replace_all( $style, "\n", '', 1 );
		$style = StringUtils::replace_all( $style, "\r", '', 1 );
		$content = StringUtils::replace_all( $content, '[BUTTON_STYLE]', $style, 14 );

		return $content;
	}

	//TODO merage this two function into one that by one call return all the meta-data needed

	public static function get_versions() {
		$url = 'http:' . CG_BUTTON_SERVER_URL . '/server_api/s1/application/get-versions?XDEBUG_SESSION_START=' . XDEBUG;

		return self::send_get_request( $url );
	}

	public static function get_application_types() {
		$url = 'http:' . CG_BUTTON_SERVER_URL . '/server_api/s1/application/get-applications?XDEBUG_SESSION_START=' . XDEBUG;

		return self::send_get_request( $url );
	}

	public static function send_get_request( $url ) {
		$curl = curl_init();
		$options = array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_TIMEOUT => 30,
		);
		curl_setopt_array($curl, $options);
		$result = curl_exec( $curl );
		curl_close( $curl );
		return $result;
	}
}