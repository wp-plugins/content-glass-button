<?php
/**
 * Copyright © 2009-2014 Rhizome Networks LTD All rights reserved.
 *
 * Created by IntelliJ IDEA.
 * User: Tomer Schilman
 * Date: 18/11/14
 * Time: 12:57
 */

require( 'HtmlElement.php' );
require( 'utils/ModuleUtils.php' );
require( 'CG-Button.php' );
require( 'CG-Button-widget.php' );
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
define( 'PLUGIN_DIRECTORY', 'content-glass-button' );

echo ent2ncr( CGModuleUtils::get_system_scripts() );

add_action( 'init', function () {
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

// create custom plugin settings menu
add_action( 'admin_menu', 'cg_button_create_menu' );

function cg_button_create_menu() {
	//create new top-level menu
	add_menu_page( 'CG Button Plugin Settings', 'CG Button Settings', 'administrator', PLUGIN_DIRECTORY . '/CG-Button-init.php', 'cg_button_settings_page', plugins_url( '/images/cg17.png', PLUGIN_DIRECTORY . '/CG-Button-init.php' ) );

	add_submenu_page( PLUGIN_DIRECTORY . '/CG-Button-init.php', 'CG Button Plugin Settings', 'CG Button Settings', 'administrator', PLUGIN_DIRECTORY . '/CG-Button-init.php', 'cg_button_settings_page' );
	//call register settings function
	add_action( 'admin_init', 'register_mysettings' );
}

function register_mysettings() {
	//register our settings
	register_setting( 'cg-button-settings-group', CG_BUTTON_APP_ID );
	register_setting( 'cg-button-settings-group', CG_BUTTON_VERSION );
	register_setting( 'cg-button-settings-group', CG_BUTTON_APPLICATION );
	register_setting( 'cg-button-settings-group', CG_BUTTON_ENABLE );
	register_setting( 'cg-button-settings-group', CG_BUTTON_WIDGET_ENABLE );
	register_setting( 'cg-button-settings-group', CG_BUTTON_LABEL );
	register_setting( 'cg-button-settings-group', CG_BUTTON_THEME );
	register_setting( 'cg-button-settings-group', CG_BUTTON_TOP );
	register_setting( 'cg-button-settings-group', CG_BUTTON_LEFT );
	register_setting( 'cg-button-settings-group', CG_BUTTON_POSITION );
	register_setting( 'cg-button-settings-group', CG_BUTTON_FONT_SIZE );
}

/**
 * create and input element with the given $type and place is id and name to be $name
 *
 * @param $type
 * @param $name
 *
 * @return html_element
 */
function create_input_element( $type, $name ) {
	$inputElm = new HtmlElement( 'input' );
	$inputElm->set( 'type', $type );
	$inputElm->set( 'id', $name );
	$inputElm->set( 'name', $name );
	$inputElm->set( 'value', esc_attr( get_option( $name ) ) );

	return $inputElm;
}

/**
 * create
 *
 * @param $options
 * @param $defaultValue
 * @param $name
 */
function create_select_elm( $options, $defaultValue, $name ) {
	$selectElm = new HtmlElement( 'select' );
	$selectElm->set( 'id', $name );
	$selectElm->set( 'name', $name );

	foreach ( $options as $option ) {
		$optionElm = new HtmlElement( 'option' );
		$optionElm->set( 'value', $option );
		if ( null === get_option( $name ) ) {
			update_option( $name, $defaultValue );
		}
		if ( get_option( $name ) === $option ) {
			$optionElm->set_selected();
		}
		$optionElm->set( 'text', $option );
		$selectElm->inject( $optionElm );
	}
	$selectElm->output();
}

/**
 * Register the given script to be loaded in the admin pages.
 */
add_action( 'admin_enqueue_scripts', 'cg_button_scripts' );

function cg_button_scripts() {
	wp_register_script(
		'quick-registration-script',
		plugins_url( '/' . PLUGIN_DIRECTORY . '/utils/QuickRegistration.js' ),
		false,
		false,
		false
	);
	wp_enqueue_script( 'quick-registration-script' );
}

/**
 * Register the given styles to be loaded in the admin pages.
 */
add_action( 'admin_enqueue_scripts', 'cg_button_styles' );

function cg_button_styles() {
	wp_enqueue_style(
		'quick-registration-style',
		plugins_url( '/' . PLUGIN_DIRECTORY . '/utils/QuickRegistration.css' )
	);
	wp_enqueue_style(
		'cg-button-jquery-style',
		'http://api.contentglass.com/core/libs/jquery_ui/css/sunny/jquery-ui-custom.min.css'
	);
}

function cg_button_settings_page() {
	?>
	<div id="cg-plugin-settings-page" class="wrap">
		<h2><a>CG Button settings</a></h2>
		<form method="post"">
			<?php settings_fields( 'cg-button-settings-group' ); ?>
			<?php do_settings_sections( 'cg-button-settings-group' ); ?>
			<div id="cg-wordpress-plugin-notification-message" hidden="hidden"></div>
			<p>
				* Note: for trial you can use the shared APP ID: "<b><?php echo esc_attr( CG_BUTTON_DEFAULT_APP_ID )?></b>".
				<!-- For trial period you can use the trial app id: <b><?php echo esc_attr( CG_BUTTON_DEFAULT_APP_ID )?></b> and register later at
				<a href="http://developers.contentglass.com/user/register" target="_blank">developers.contentglass.com</a> and creating your own app Id. <br>Note
				that test App ID is for testing purpose only. Content associated with the test App ID may not preserved. -->
			</p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">APP Id <!--<a href="#" onclick="wordPressCgPlugin.openQuickRegistration()"
											  style="font-size: 12px;">(click here to get APP ID)</a>-->
					</th>
					<td>
						<?php
						$inputElm = create_input_element( 'text', CG_BUTTON_APP_ID );
						$inputElm->output();
						?>
						<label style="color: red; font-weight: bold;" title="Required field">*</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Version</th>
					<td>
						<?php
						$versions = json_decode( CgModuleUtils::get_versions() );
						if ( $versions->status === 1 ) {
							$versions = $versions->data->versions;
						} else {
							$versions = array( 'latest' );
						}
						create_select_elm( $versions, CG_BUTTON_DEFAULT_VERSION, CG_BUTTON_VERSION );
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Application type</th>
					<td>
						<?php
						$appType = json_decode( CgModuleUtils::get_application_types() );
						$apps = array();
						if ( null !== $appType ) {
							if ( $appType->status === 1 ) {
								$appType = $appType->data->applications;
								foreach ( $appType as $app ) {
									array_push( $apps, $app->name );
								}
							} else {
								$apps = array( CG_BUTTON_DEFAULT_APPLICATION );
							}
						} else {
							$apps = array( CG_BUTTON_DEFAULT_APPLICATION );
						}
						create_select_elm( $apps, CG_BUTTON_DEFAULT_APPLICATION, CG_BUTTON_APPLICATION );
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Enable Floating CG Button</th>
					<td>
						<?php
						$options = array( 'Enable', 'Disable' );
						create_select_elm( $options, 'Enable', CG_BUTTON_ENABLE );
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Enable CG Button Widget</th>
					<td>
						<?php
						$options = array( 'Enable', 'Disable' );
						create_select_elm( $options, 'Enable', CG_BUTTON_WIDGET_ENABLE );
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Floating Button label</th>
					<td>
						<?php
						$inputElm = create_input_element( 'text', CG_BUTTON_LABEL );
						$inputElm->output();
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Floating CG Button Top (in px)</th>
					<td>
						<?php
						$inputElm = create_input_element( 'text', CG_BUTTON_TOP );
						$inputElm->output();
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Floating CG Button Left (in px)</th>
					<td>
						<?php
						$inputElm = create_input_element( 'text', CG_BUTTON_LEFT );
						$inputElm->output();
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Floating CG Button Size (in px)</th>
					<td>
						<?php
						$inputElm = create_input_element( 'text', CG_BUTTON_FONT_SIZE );
						$inputElm->output();
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Floating CG Button Position</th>
					<td>
						<?php
						$positions = array( 'absolute', 'static', 'fixed', 'relative' );
						create_select_elm( $positions, CG_BUTTON_DEFAULT_POSITION, CG_BUTTON_POSITION );
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Default CG Button theme</th>
					<td>
						<?php
						$themes = array(
							'blitzer', 'cupertino', 'darkness', 'eggplant', 'excite-bike', 'flick',
							'humanity', 'lightness', 'overcast', 'pepper-grinder', 'redmond', 'smoothness', 'sunny', 'vader', );
						create_select_elm( $themes, CG_BUTTON_DEFAULT_THEME, CG_BUTTON_THEME );
						?>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
<?php } ?>
