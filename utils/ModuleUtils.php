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
define( 'CG_BUTTON_DEFAULT_APPLICATION_DATA', '{"button": {"name": "CG-Button", "type": "button", "app_id": "5409596055731"}}' );

//define('CG_BUTTON_APP_ID', 'cg_button_app_id');
define( 'CG_BUTTON_APP_VERSIONS', 'cg_button_app_versions' );
define( 'CG_BUTTON_APP_DATA', 'cg_button_app_data' );
define( 'CG_BUTTON_APP_TYPE', 'cg_button_app_type' );
define( 'CG_BUTTON_API_KEY', 'cg_button_api_key' );
define( 'CG_BUTTON_THEME', 'cg_button_default_theme' );
define( 'CG_BUTTON_VERSION', 'cg_button_version' );
define( 'CG_BUTTON_STYLE', 'cg_button_style' );
define( 'CG_BUTTON_LABEL', 'cg_button_label' );
define( 'CG_BUTTON_FONT_SIZE', 'cg_button_font_size' );
define( 'CG_BUTTON_ENABLE', 'cg_button_enable' );
define( 'CG_BUTTON_APPLICATION_DATA', 'cg_button_application_data' );

//TODO for propduction chnage to prod
define( 'CG_DEV_MODE', 'prod' );
//define( 'CG_DEV_MODE', 'dev' );
//define( 'CG_DEV_MODE', 'local' );
define( 'CG_PORT', '' );
define( 'XDEBUG', 19771 );
define( 'XDEBUG_TOKEN', 'XDEBUG_SESSION_START=' );

if ( CG_DEV_MODE === 'local' ) {
	define( 'CG_BUTTON_SERVER_URL', '//local.contentglass.com:' . CG_PORT );
} elseif ( CG_DEV_MODE === 'dev' ) {
	define( 'CG_BUTTON_SERVER_URL', '//dev.contentglass.com' );
} else {
	define( 'CG_BUTTON_SERVER_URL', '//api.contentglass.com' );
}
define( 'CG_AUTH_URL', 'http:' . CG_BUTTON_SERVER_URL . '/server_api/p1/security/authorize' );
define( 'ABS_PLUGIN_PATH', ABSPATH . 'wp-content/plugins/content-glass-button' );


class CGModuleUtils {

	public static function get_system_scripts_params() {
		$params = array();

		$sessId                 = isset( $_COOKIE[ 'wp_rhz_session_id' ] ) === true ? $_COOKIE[ 'wp_rhz_session_id' ] : '';
		$params[ 'SESSION_ID' ] = $sessId;

		$defAppData = self::get_default_app_data();
		$apps_data  = self::get_applications();
		$app_type   = '' . get_option( CG_BUTTON_APP_TYPE );
		if ( null === $app_type ) {
			$app_type = $defAppData->type;
		}

		if ( isset( $apps_data->{$app_type} ) ) {
			$selectedApp = $apps_data->{$app_type};
		} else {
			$selectedApp = $apps_data->{$defAppData->type};
		}
		$params[ 'APP_ID' ] = $selectedApp->app_id;

		//make sure you pass the data safely to ContentGlassSystemScript. Also note JSON_UNESCAPED_SLASHES that provide lean url of application data
		$params[ 'APP_DATA' ] = base64_encode( json_encode( $selectedApp, JSON_UNESCAPED_SLASHES ) );

		$theme = get_option( CG_BUTTON_THEME );
		if ( null === $theme ) {
			$theme = "'" . CG_BUTTON_DEFAULT_THEME . "'";
		} else {
			$theme = "'" . $theme . "'";
		}
		$params[ 'DEFAULT_THEME' ] = $theme;

		$version = get_option( CG_BUTTON_VERSION );
		if ( null === $version ) {
			$version = CG_BUTTON_DEFAULT_VERSION;
		}
		$params[ 'CG_VERSION' ] = $version;
		$params[ 'CG_SERVER' ]  = CG_BUTTON_SERVER_URL;
		$params[ 'CG_PORT' ]    = CG_PORT;

		if ( CG_DEV_MODE === 'local' ) {
			$params[ 'DEV_MODE' ] = 'DEV=true';
			$params[ 'XDEBUG' ]   = XDEBUG_TOKEN . XDEBUG;
		} else {
			if ( CG_DEV_MODE === 'dev' ) {
				$params[ 'DEV_MODE' ] = 'DEV_HOST=true';
				$params[ 'XDEBUG' ]   = '';
			} else {
				$params[ 'DEV_MODE' ] = '';
				$params[ 'XDEBUG' ]   = '';
			}
		}

		return $params;
	}

	public static function get_customize_button_params() {
		$params = array();

		$label = get_option( CG_BUTTON_LABEL );
		if ( null === $label ) {
			$label = CG_BUTTON_DEFAULT_LABEL;
		}
		$params[ 'label' ] = $label;
		$style             = get_option( CG_BUTTON_STYLE );
		$style             = StringUtils::replace_all( $style, "\n", '', 1 ); //we remove new lines if the user enter some
		$style             = StringUtils::replace_all( $style, "\r", '', 1 );
		if ( strpos( $style, 'position' ) === false ) {
			$style = $style . 'position:fixed;';
		}
		$params[ 'style' ] = $style;

		return $params;
	}

	public static function get_widget_script( $label, $style ) {
		$content = file_get_contents( __DIR__ . '/../templates/WidgetButtonScript.html', FILE_USE_INCLUDE_PATH );
		$content = StringUtils::replace_all( $content, '[CG_LABEL]', $label, 10 );

		$style   = StringUtils::replace_all( $style, "\n", '', 1 );
		$style   = StringUtils::replace_all( $style, "\r", '', 1 );
		$content = StringUtils::replace_all( $content, '[BUTTON_STYLE]', $style, 14 );

		return $content;
	}

	//TODO merage this two function into one that by one call return all the meta-data needed
	/**
	 * Get version meta data from either option or CG server. If an option not
	 * set load the data from the server and store in an option.
	 * @return array|mixed
	 */
	public static function get_versions() {
		$verDataStr = get_option( CG_BUTTON_APP_VERSIONS );
		if ( $verDataStr == null ) {
			$url     = 'http:' . CG_BUTTON_SERVER_URL . '/server_api/s1/application/get-versions?XDEBUG_SESSION_START=' . XDEBUG;
			$resp    = self::send_get_request( $url );
			$respObj = json_decode( $resp );
			if ( $respObj->status != 1 ) {
				$verData = array( "latest" );
			} else {
				$verData = $respObj->data->versions;
				update_option( CG_BUTTON_APP_VERSIONS, json_encode( $verData ) );
			}

		} else {
			$verData = json_decode( $verDataStr );
		}

		return $verData;
	}

	/**
	 * Load applications meta data from either option or CG API Server.  After
	 * loading from server once, the data is stored in an option and is served
	 * from there for the next calls. If for some reason
	 *we get an error from server call we use the default application data and do not store in an option.
	 *
	 * @return array|mixed|void
	 */
	public static function get_applications() {
		$appsDataStr = get_option( CG_BUTTON_APP_DATA );
		if ( $appsDataStr == null ) {
			$url        = 'http:' . CG_BUTTON_SERVER_URL . '/server_api/s1/application/get-applications?XDEBUG_SESSION_START=' . XDEBUG;
			$resp       = self::send_get_request( $url );
			$respObject = json_decode( $resp );
			if ( $respObject->status != 1 ) {
				$appsData = self::get_default_app_data();
			} else {
				$appsData = $respObject->data->applications;
				update_option( CG_BUTTON_APP_DATA, json_encode( $appsData ) );
			}

		} else {
			$appsData = json_decode( $appsDataStr );
		}

		return $appsData;
	}

	/**
	 * Create options array to set in application select element.
	 * @return array associative array that map [api_key] => [app name]
	 */
	public static function prepare_app_options() {
		$appsData = CgModuleUtils::get_applications();
		$options  = array();
		foreach ( $appsData as $appTag => $app ) {
			$options[ $appTag ] = $app->name;
		}

		return $options;
	}

	public static function send_get_request( $url ) {
		$curl    = curl_init();
		$options = array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL            => $url,
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_TIMEOUT        => 30,
		);
		curl_setopt_array( $curl, $options );
		$result = curl_exec( $curl );
		curl_close( $curl );

		return $result;
	}

	/**
	 * Create options array to set in versions select element.
	 * @return array associative array that map [version] => [version]
	 */
	public static function prepare_version_options() {
		$options  = array();
		$versions = CgModuleUtils::get_versions();
		for ( $i = 0; $i < count( $versions ); $i ++ ) {
			$options[ $versions[ $i ] ] = $versions[ $i ];
		}

		return $options;
	}

	/**
	 * Prepare the themes options array.
	 *
	 * @return array
	 */
	public static function prepare_themes_options() {
		return array(
			'blitzer'        => 'blitzer',
			'cupertino'      => 'cupertino',
			'darkness'       => 'darkness',
			'eggplant'       => 'eggplant',
			'excite-bike'    => 'excite-bike',
			'flick'          => 'blitzer',
			'humanity'       => 'flick',
			'lightness'      => 'lightness',
			'overcast'       => 'overcast',
			'pepper-grinder' => 'pepper-grinder',
			'redmond'        => 'redmond',
			'smoothness'     => 'smoothness',
			'sunny'          => 'sunny',
			'vader'          => 'vader',
		);
	}

	/**
	 * Get the Application Id of currently selected application.
	 * @return string
	 */
	public static function get_selected_app_id() {
		$appTag = get_option( CG_BUTTON_APP_TYPE );
		if ( $appTag == null ) { 
			$defAppData = self::get_default_app_data();
			$appTag     = $defAppData->type;
		}
		$appsData = CGModuleUtils::get_applications();
		$appData  = $appsData->{$appTag};

		return $appData->app_id;
	}


	public static function get_default_app_data() {
		$obj = json_decode( CG_BUTTON_DEFAULT_APPLICATION_DATA );

		return $obj->button;
	}

}