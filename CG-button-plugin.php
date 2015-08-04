<?php
/**
 * Copyright © 2009-2014 Rhizome Networks LTD All rights reserved.
 *
 * Created by IntelliJ IDEA.
 * User: Tomer Schilman
 * Date: 10/11/14
 * Time: 15:05
 */

/**
 * Plugin Name: CG Button
 * Plugin URI: http://www.contentglass.com/wordpress-plugin-help
 * Description: Add the CG Button to your Wordpress site
 * Version: 1.0.5.6
 * Author: Rhizome Networks
 * Author URI: http://www.contentglass.com
 * License:
 */

global $pagenow;
$excludePage = array(
	'wp-login.php', 'post.php', 'post-new.php', 'edit.php', 'plugins.php',
	'upload.php', 'edit-comments.php', 'themes.php', 'customize.php', 'widgets.php', 'nav-menus.php',
	'options-writing.php', 'options-reading.php', 'options-discussion.php',
	'options-media.php', 'options-permalink.php', 'users.php', 'user-new.php', 'profile.php',
	'tools.php', 'import.php', 'export.php', 'admin-ajax.php',
);
if ( !in_array($pagenow, $excludePage) ) {
	require_once( 'core/CG-Button-init.php' );
} else {
	require_once( 'core/CG-Button-menu-link.php' );
}