<?php
/**
 * Copyright © 2009-2014 Rhizome Networks LTD All rights reserved.
 *
 * Created by IntelliJ IDEA.
 * User: Tomer Schilman
 * Date: 18/11/14
 * Time: 12:10
 */

define( 'CG_BUTTON_WIDGET_ENABLE', 'cg_button_widget_enable' );

define( 'CG_WIDGET_DESCRIPTION', 'A widget for showing Content Glass button.' );
define( 'CG_WIDGET_DEFAULT_TEXT', 'Content Glass' );
define( 'CG_WIDGET_DEFAULT_SIZE', 14 );

define( 'CG_WIDGET_TITLE', 'title' );
define( 'CG_WIDGET_SIZE', 'size' );

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
		if ( isset( $instance[ CG_WIDGET_TITLE ] ) ) {
			$title = $instance[ CG_WIDGET_TITLE ];
		} else {
			$title = __( CG_WIDGET_DEFAULT_TEXT, 'cg_widget_domain' );
		}
		if ( isset( $instance[ CG_WIDGET_SIZE ] ) ) {
			$size = $instance[ CG_WIDGET_SIZE ];
		} else {
			$size = __( CG_WIDGET_DEFAULT_SIZE, 'cg_widget_domain' );
		}
		//We don't use escaping function in the next 2 echo's because it destroy the structure and print as plain text.
		//So ignore the phpcs warnings.
		// before and after widget arguments are defined by themes
		echo( $args['before_widget'] );
		// This is where you run the code and display the output
		echo ent2ncr( CgModuleUtils::get_widget_script( $title, $size ) );
		echo( $args['after_widget'] );
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
		if ( isset( $instance[ CG_WIDGET_TITLE ] ) ) {
			$title = $instance[ CG_WIDGET_TITLE ];
		} else {
			$title = __( CG_WIDGET_DEFAULT_TEXT, 'cg_widget_domain' );
		}
		if ( isset( $instance[ CG_WIDGET_SIZE ] ) ) {
			$size = $instance[ CG_WIDGET_SIZE ];
		} else {
			$size = __( CG_WIDGET_DEFAULT_SIZE, 'cg_widget_domain' );
		}
		// Widget admin form
		?>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( CG_WIDGET_TITLE ) ); ?>"><?php esc_attr( 'Button Text:' ); ?></label>
			<input
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( CG_WIDGET_TITLE ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( CG_WIDGET_TITLE ) ); ?>"
				type="text"
				value="<?php echo esc_attr( $title ); ?>"/>
			<label
				for="<?php echo esc_attr( $this->get_field_id( CG_WIDGET_SIZE ) ); ?>"><?php esc_attr( 'Text size:' ); ?></label>
			<input
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( CG_WIDGET_SIZE ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( CG_WIDGET_SIZE ) ); ?>"
				type="text"
				value="<?php echo esc_attr( $size ); ?>"/>
		</p>
	<?php
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
		foreach ( $new_instance as $key => $value ) {
			$instance[ $key ] = ( ! empty( $value ) ) ? strip_tags( $value ) : '';
		}
		//$instance[CG_WIDGET_TITLE] = ( ! empty( $new_instance[CG_WIDGET_TITLE] ) ) ? strip_tags( $new_instance[CG_WIDGET_TITLE] ) : '';
		//$instance[CG_WIDGET_SIZE] = ( ! empty( $new_instance[CG_WIDGET_SIZE] ) ) ? strip_tags( $new_instance[CG_WIDGET_SIZE] ) : '';
		return $instance;
	}
}
 