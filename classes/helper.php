<?php

namespace PressGang;

class Helper {

	/**
	 * Hyphenate camel cased text
	 *
	 * @param $name
	 *
	 * @return string
	 */
	public static function camel_to_hyphenated( $name ) {
		return self::camel_to_delimited( $name, '-' );
	}

	/**
	 * Underscore camel cased text
	 *
	 * @param $name
	 *
	 * @return string
	 */
	public static function camel_to_underscored( $name ) {
		return self::camel_to_delimited( $name, '_' );
	}

	/**
	 * Add delimiters to camel cased text
	 *
	 * @param $name
	 * @param string $delimiter
	 *
	 * @return string
	 */
	protected static function camel_to_delimited( $name, $delimiter = '-' ) {
		return strtolower( preg_replace( '/([a-zA-Z])(?=[A-Z])/', "$1{$delimiter}", $name ) );
	}

	/**
	 * Calculates a reading time for a given block of text
	 *
	 * @param $text
	 * @param bool $to_nearest_minute
	 *
	 * @return string
	 */
	public static function reading_time( $text, $to_nearest_minute = false, $speed = 200 ) {
		$words = str_word_count( strip_tags( $text ) );

		$seconds = 0;

		if ( $to_nearest_minute ) {
			$minutes = floor( $words / $speed );
			$seconds = floor( $words % $speed / ( $speed / 60 ) );
		} else {
			$minutes = ceil( $words / $speed );
		}

		$est = sprintf( _n( "%d minute", "%d minutes", $minutes, THEMENAME ), $minutes );

		if ( $seconds ) {
			$est .= ',' . sprintf( _n( "%d second", "%d seconds", $seconds, THEMENAME ), $seconds );
		}

		return $est;
	}
}
