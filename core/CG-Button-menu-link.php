<?php
/**
 * Copyright © 2009-2014 Rhizome Networks LTD All rights reserved.
 *
 * Created by IntelliJ IDEA.
 * User: Tomer Schilman
 * Date: 07/07/2015
 * Time: 08:39
 */
require_once( __DIR__ . '/../utils/ModuleUtils.php' );
require_once( 'HtmlElement.php' );
require_once( 'CG-Button-widget.php' );
const WP_RHZ_SESSION_ID = 'wp_rhz_session_id';
define( 'PLUGIN_DIRECTORY', 'content-glass-button' );
if ( ! defined( 'CG_BUTTON_WIDGET_ENABLE' ) ) {
	define( 'CG_BUTTON_WIDGET_ENABLE', 'cg_button_widget_enable' );
}

global $ERROR_PAGES;
global $authRetry;
$authRetry   = true;
$ERROR_PAGES = array( 'options-general.php', 'admin.php', 'options.php', );

function cg_button_authorize() {
	global $authRetry;
	global $ERROR_PAGES;
	global $pagenow;
	$appId  = CGModuleUtils::get_selected_app_id();
	$apiKey = esc_attr( get_option( CG_BUTTON_API_KEY ) );
	$sessId = isset( $_COOKIE[ WP_RHZ_SESSION_ID ] ) === true ? $_COOKIE[ WP_RHZ_SESSION_ID ] : '';
	$url    = CG_AUTH_URL . '?api_key=' . $apiKey . '&app_id=' . $appId . '&RHZ_SESSION_ID=' . $sessId;
	if ( CG_DEV_MODE ) {
		$url = $url . '&' . XDEBUG_TOKEN . XDEBUG;
	}
	$data   = CGModuleUtils::send_get_request( $url );
	$result = json_decode( $data );
	if ( $result->status === 1 ) {
		if ( ! in_array( $pagenow, $ERROR_PAGES ) ) {
			//global $cg_accessToken;
			//$cg_accessToken = $result->data->access_token;
			setcookie( WP_RHZ_SESSION_ID, $result->data->session_id );
			add_action( 'wp_enqueue_scripts', 'cg_button_scripts' );

			function cg_button_scripts() {
				$params      = CGModuleUtils::get_system_scripts_params();
				$queryParams = http_build_query( $params );
				//$cg_accessToken = '';
				wp_register_script(
					'cg-system-script',
					plugins_url( '/' . PLUGIN_DIRECTORY . '/templates/ContentGlassSystemScripts.php?' . $queryParams ),
					false,
					false,
					false
				);
				wp_enqueue_script( 'cg-system-script' );
			}
		}
	} else {
		if ( $authRetry && $result->http_code === 401 ) {
			$authRetry = false;
			setcookie( WP_RHZ_SESSION_ID, "", time() - 3600 );
			unset ( $_COOKIE[ WP_RHZ_SESSION_ID ] );
			cg_button_authorize();
		} else {
			global $cg_error;
			$cg_error = json_decode( $result->message );
			function cg_button_notice() {
				if ( current_user_can( 'install_plugins' ) ) {
					global $cg_error;
					echo '<div class="error"><p>' . $cg_error->message . '</p></div>';
				}
			}

			if ( in_array( $pagenow, $ERROR_PAGES ) ) {
				add_action( 'admin_notices', 'cg_button_notice' );
			}
		}
	}
}


cg_button_authorize();

// Add settings link on plugin page
function cg_button_plugin_settings_link( $links ) {
	$settings_link = '<a href="options-general.php?page=' . PLUGIN_DIRECTORY . '/core/CG-Button-init.php">Settings</a>';
	array_unshift( $links, $settings_link );

	return $links;
}

/*
 * Add a function to a filter, in this case we add the function "cg_button_plugin_settings_link" to the filter
 * "plugin_action_links_content-glass-button/CG-button-plugin.php" that add action links the the plugin
 * row in plugins.php page.
 */
add_filter( 'plugin_action_links_content-glass-button/CG-button-plugin.php', 'cg_button_plugin_settings_link' );

// create custom plugin settings menu
add_action( 'admin_menu', 'cg_button_create_menu' );

function cg_button_create_menu() {
	//create new top-level menu
	add_options_page( 'CG Button Plugin Settings', 'CG Button', 'administrator', PLUGIN_DIRECTORY . '/core/CG-Button-init.php', 'cg_button_settings_page', plugins_url( '/images/cg17.png', PLUGIN_DIRECTORY . '/core/CG-Button-init.php' ) );
	//	add_menu_page( 'CG Button Plugin Settings', 'CG Button Settings', 'administrator', PLUGIN_DIRECTORY . '/CG-Button-init.php', 'cg_button_settings_page', plugins_url( '/images/cg17.png', PLUGIN_DIRECTORY . '/CG-Button-init.php' ) );

	//	add_submenu_page( PLUGIN_DIRECTORY . '/CG-Button-init.php', 'CG Button Plugin Settings', 'CG Button Settings', 'administrator', PLUGIN_DIRECTORY . '/CG-Button-init.php', 'cg_button_settings_page' );

	//call register settings function
	add_action( 'admin_init', 'register_mysettings' );
}

function register_mysettings() {
	//register our settings
	register_setting( 'cg-button-settings-group', CG_BUTTON_APP_VERSIONS );
	register_setting( 'cg-button-settings-group', CG_BUTTON_APP_DATA );
	register_setting( 'cg-button-settings-group', CG_BUTTON_APP_TYPE );
	register_setting( 'cg-button-settings-group', CG_BUTTON_API_KEY );
	register_setting( 'cg-button-settings-group', CG_BUTTON_VERSION );
	register_setting( 'cg-button-settings-group', CG_BUTTON_ENABLE );
	register_setting( 'cg-button-settings-group', CG_BUTTON_LABEL );
	register_setting( 'cg-button-settings-group', CG_BUTTON_STYLE );
	register_setting( 'cg-button-settings-group', CG_BUTTON_THEME );
}

/**
 * create and input element with the given $type and place is id and name to be
 * $name
 *
 * @param $type
 * @param $name
 *
 * @param string $tag
 * @param null $extra
 *
 * @return mixed html_element
 */
function create_input_element( $type, $name, $tag = 'input', $extra = null ) {
	$inputElm = new HtmlElement( $tag );
	$inputElm->set( 'type', $type );
	$inputElm->set( 'text', esc_attr( get_option( $name ) ) );
	$inputElm->set( 'id', $name );
	$inputElm->set( 'name', $name );
	$inputElm->set( 'value', esc_attr( get_option( $name ) ) );

	if ( null !== $extra ) {
		foreach ( $extra as $key => $value ) {
			$inputElm->set( $key, $value );
		}
	}

	return $inputElm;
}

/**
 * create
 *
 * @param $options
 * @param $defaultValue
 * @param $name
 *
 * @return string
 */
function create_select_elm( $options, $defaultValue, $name ) {
	$selectElm = new HtmlElement( 'select' );
	$selectElm->set( 'id', $name );
	$selectElm->set( 'name', $name );
	$selectElm->set( 'text', '' );

	foreach ( $options as $value => $text ) {
		$optionElm = new HtmlElement( 'option' );
		$optionElm->set( 'value', $value );
		if ( null === get_option( $name ) ) {
			update_option( $name, $defaultValue );
		}
		if ( get_option( $name ) === $value ) {
			$optionElm->set_selected();
		}
		$optionElm->set( 'text', $text );
		$selectElm->inject( $optionElm );
	}

	//	$selectElm->output();
	return $selectElm->to_string();
}

function cg_button_settings_page() {
	//we first echo the start of the form.
	echo '<div id="cg-plugin-settings-page" class="wrap">
    <h2><a>CG Button settings</a></h2>
    <form method="post" action="options.php">';

	//then we use wordpress settings hooks
	settings_fields( 'cg-button-settings-group' );
	do_settings_sections( 'cg-button-settings-group' );

	$content = file_get_contents( __DIR__ . '/../templates/SettingsFormTemplate.html', FILE_USE_INCLUDE_PATH );
	$content = StringUtils::replace_all( $content, '[TEST_APP_ID]', CG_BUTTON_TEST_APP_ID );
	$content = StringUtils::replace_all( $content, '[TEST_API_KEY]', CG_BUTTON_TEST_API_KEY );

	//$inputElm = create_input_element( 'text', CG_BUTTON_APP_ID );
	//$content = StringUtils::replace_all( $content, '[APP_ID_INPUT]', $inputElm->to_string() );

	$inputElm = create_input_element( 'text', CG_BUTTON_API_KEY );
	$content  = StringUtils::replace_all( $content, '[API_KEY_INPUT]', $inputElm->to_string() );

	$options  = CgModuleUtils::prepare_version_options();
	$inputElm = create_select_elm( $options, CG_BUTTON_DEFAULT_VERSION, CG_BUTTON_VERSION );
	$content  = StringUtils::replace_all( $content, '[VERSIONS_INPUT]', $inputElm );

	$defAppData = CgModuleUtils::get_default_app_data();
	$options    = CgModuleUtils:: prepare_app_options();
	$inputElm   = create_select_elm( $options, $defAppData->type, CG_BUTTON_APP_TYPE );
	$content    = StringUtils::replace_all( $content, '[APP_TYPES_INPUT]', $inputElm );

	$options  = array( 'Enable' => 'Enable', 'Disable' => 'Disable' );
	$inputElm = create_select_elm( $options, 'Enable', CG_BUTTON_ENABLE );
	$content  = StringUtils::replace_all( $content, '[BUTTON_ENABLE_INPUT]', $inputElm );

	$inputElm = create_input_element( 'text', CG_BUTTON_LABEL );
	$content  = StringUtils::replace_all( $content, '[BUTTON_LABEL_INPUT]', $inputElm->to_string() );

	$inputElm = create_input_element(
		'text', CG_BUTTON_STYLE,
		'textarea', array(
			'style' => 'resize: none; width: 350px; height: 150px;',
		)
	);
	$content  = StringUtils::replace_all( $content, '[BUTTON_STYLE_INPUT]', $inputElm->to_string() );

	$themes   = CGModuleUtils::prepare_themes_options();
	$inputElm = create_select_elm( $themes, CG_BUTTON_DEFAULT_THEME, CG_BUTTON_THEME );
	$content  = StringUtils::replace_all( $content, '[BUTTON_THEMES_INPUT]', $inputElm );

	echo( $content );
	//After we finish with the form content we use wordpress submit button function and then close the form.
	submit_button();
	echo '</form></div>';
}

function cg_button_add_rate_link() {
	global $pagenow;
	if ( 'plugins.php' === $pagenow ) {
		wp_register_script(
			'cg-rate-script',
			plugins_url( '/' . PLUGIN_DIRECTORY . '/utils/AddRateLink.js?' ),
			false,
			false,
			true
		);
		wp_enqueue_script( 'cg-rate-script' );
	}
}

add_action( 'admin_enqueue_scripts', 'cg_button_add_rate_link' );