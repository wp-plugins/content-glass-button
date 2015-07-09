<?php
/**
 * Copyright © 2009-2014 Rhizome Networks LTD All rights reserved.
 *
 * Created by IntelliJ IDEA.
 * User: Tomer Schilman
 * Date: 08/07/2015
 * Time: 12:04
 */
class StringUtils {
	/**
	 * Replace all occurrences of start position of str1 + $length chars with str2.
	 * @param $string
	 * @param $str1
	 * @param $str2
	 * @param $length
	 *
	 * @return mixed
	 */
	public static function replace_all( $string, $str1, $str2, $length = null ) {
		if ( null === $length ) {
			$length = strlen( $str1 );
		}
		while ( ( $off = strpos( $string, $str1 ) ) !== false ) {
			$string = substr_replace( $string, $str2, $off, $length );
		}

		return $string;
	}
}