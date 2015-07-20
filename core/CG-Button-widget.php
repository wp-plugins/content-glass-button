<?php
/**
 * Copyright © 2009-2014 Rhizome Networks LTD All rights reserved.
 *
 * Created by IntelliJ IDEA.
 * User: Tomer Schilman
 * Date: 18/11/14
 * Time: 12:10
 */

require_once(  __DIR__ . '/../utils/StringUtils.php' );
require_once(  __DIR__ . '/../utils/ModuleUtils.php' );

define( 'CG_BUTTON_WIDGET_ENABLE', 'cg_button_widget_enable' );

define( 'CG_WIDGET_DESCRIPTION', 'A widget for showing Content Glass button.' );
define( 'CG_WIDGET_DEFAULT_TEXT', 'Content Glass' );
define( 'CG_WIDGET_DEFAULT_SIZE', 14 );
define( 'CG_WIDGET_DEFAULT_STYLE', '' );

define( 'CG_WIDGET_TITLE', 'title' );
define( 'CG_WIDGET_SIZE', 'size' );
define( 'CG_WIDGET_STYLE', 'style' );

class CgButtonWidget extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	function CgButtonWidget() {
		$widget_ops = array( 'classname' => 'cgbutton_widget', 'description' => CG_WIDGET_DESCRIPTION );
		$this->WP_Widget( 'cgbutton_widget', 'CG Button', $widget_ops );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = $this->get_param( $instance, CG_WIDGET_TITLE, CG_WIDGET_DEFAULT_TEXT );
		$style = $this->get_param( $instance, CG_WIDGET_STYLE, CG_WIDGET_DEFAULT_STYLE );

		//We don't use escaping function in the next 2 echo's because it destroy the structure and print as plain text.
		//So ignore the phpcs warnings.
		// before and after widget arguments are defined by themes
		echo( $args['before_widget'] );
		// This is where you run the code and display the output
		echo ent2ncr( CgModuleUtils::get_widget_script( $title, $style ) );
		echo( $args['after_widget'] );
	}

	private function get_param($instance, $param_name, $default){
		if ( isset( $instance[ $param_name ] ) ) {
			$param = $instance[ $param_name ];
		} else {
			$param = __( $default, 'cg_widget_domain' );
		}
		return $param;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		$title = $this->get_param( $instance, CG_WIDGET_TITLE, CG_WIDGET_DEFAULT_TEXT );
		$style = $this->get_param( $instance, CG_WIDGET_STYLE, CG_WIDGET_DEFAULT_STYLE );
		// Widget admin form
		$content = file_get_contents(  __DIR__ . '/../templates/WidgetInputTemplate.html', FILE_USE_INCLUDE_PATH );
		$content = StringUtils::replace_all( $content, '[ID]', $this->get_field_id( CG_WIDGET_TITLE ), 4 );
		$content = StringUtils::replace_all( $content, '[NAME]', $this->get_field_id( CG_WIDGET_TITLE ), 6 );
		$content = StringUtils::replace_all( $content, '[VALUE]', $title, 7 );
		$content = StringUtils::replace_all( $content, '[EXPLANATION]', '', 13 );

		$content = $content . file_get_contents(  __DIR__ . '/../templates/WidgetTextareaTemplate.html', FILE_USE_INCLUDE_PATH );
		$content = StringUtils::replace_all( $content, '[ID]', $this->get_field_id( CG_WIDGET_STYLE ), 4 );
		$content = StringUtils::replace_all( $content, '[NAME]', $this->get_field_id( CG_WIDGET_STYLE ), 6 );
		$content = StringUtils::replace_all( $content, '[VALUE]', $style, 7 );
		$explanation = '<br><small style="font-size: x-small;">Example: background-color: red; top: 2px;</small>
		<br><small style="font-size: x-small;">*Note: font-size cannot be over-writen.</small>';
		$content = StringUtils::replace_all( $content, '[EXPLANATION]', $explanation, 13 );

		echo '<p>' . $content . '</p>';

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance[ CG_WIDGET_TITLE ] = $_REQUEST['widget-' . $_REQUEST['widget-id'] . '-' . CG_WIDGET_TITLE];
		$instance[ CG_WIDGET_STYLE ] = $_REQUEST['widget-' . $_REQUEST['widget-id'] . '-' .  CG_WIDGET_STYLE];
		return $instance;
	}
}

// register widget
function register_cg_widget() {
	unregister_widget( 'CgButtonWidget' );
	register_widget( 'CgButtonWidget' );
}

add_action( 'widgets_init', 'register_cg_widget' );