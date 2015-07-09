<?php
/**
 * Copyright © 2009-2014 Rhizome Networks LTD All rights reserved.
 *
 * Created by IntelliJ IDEA.
 * User: Tomer Schilman
 * Date: 18/11/14
 * Time: 12:06
 */
/* creates an html element, like in js */

class HtmlElement {
	/* vars */
	var $type;
	var $attributes;
	var $self_closers;
	var $selected = false;

	/* constructor */
	function HtmlElement( $type, $self_closers = array( 'input', 'img', 'hr', 'br', 'meta', 'link' ) ) {
		$this->type = strtolower( $type );
		$this->self_closers = $self_closers;
	}

	/* get */
	function get( $attribute ) {
		return $this->attributes[ $attribute ];
	}

	/* set -- array or key,value */
	function set( $attribute, $value = '' ) {
		if ( ! is_array( $attribute ) ) {
			$this->attributes[ $attribute ] = $value;
		} else {
			$this->attributes = array_merge( $this->attributes, $attribute );
		}
	}

	/* remove an attribute */
	function remove( $att ) {
		if ( isset( $this->attributes[ $att ] ) ) {
			unset( $this->attributes[ $att ] );
		}
	}

	/* clear */
	function clear() {
		$this->attributes = array();
	}

	function set_selected() {
		$this->selected = true;
	}

	function clear_selected() {
		$this->selected = false;
	}

	/* inject */
	function inject( $object ) {
		if ( @get_class( $object ) === __class__ ) {
			$this->attributes['text'] .= $object->build();
		}
	}

	/* build */
	function build() {
		//start
		$build = '<' . $this->type;

		//add attributes
		if ( count( $this->attributes ) ) {
			foreach ( $this->attributes as $key => $value ) {
				if ( 'text' !== $key ) {
					$build .= ' ' . $key . '="' . $value . '"';
				}
			}
		}

		if ( $this->selected ) {
			$build .= ' selected';
		}

		//closing
		if ( ! in_array( $this->type, $this->self_closers ) ) {
			$build .= '>' . $this->attributes['text'] . '</' . $this->type . '>';
		} else {
			$build .= ' />';
		}

		//return it
		return $build;
	}

	/* spit it out */
	function output() {
		//We don't use esc_html because the purpose of this function is to return an html tag
		echo ( $this->build() );
	}

	/* spit it out */
	function to_string() {
		//We don't use esc_html because the purpose of this function is to return an html tag
		return $this->build();
	}
}

?>