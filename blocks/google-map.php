<?php

namespace PressGang;

/**
 * Class GoogleMap
 *
 * @package PressGang
 */
class GoogleMap extends \Pressgang\Block {

	/**
	 * do_shortcode
	 *
	 * Render the shortcode template
	 *
	 * @return array
	 */
	public static function get_context( $block ) {

		wp_register_script( 'pressgang-google-map', get_template_directory_uri() . '/js/src/custom/google-map.js' );
		wp_enqueue_script( 'pressgang-google-map' );

		wp_register_script( 'google-maps', sprintf( 'https://maps.googleapis.com/maps/api/js?key=%s&callback=initMap', \get_theme_mod( 'acf_google_maps_key' ) ), array(
			'jquery',
			'pressgang-google-map'
		) );

		wp_enqueue_script( 'google-maps' );

		\Pressgang\Scripts::$defer[] = 'google-maps';
		\Pressgang\Scripts::$async[] = 'pressgang-google-map';

		static $i;
		$i ++;

		static::$context = parent::get_context( $block );
		static::$context['id'] = sprintf( "google-maps-%d", $i );

		return static::$context;
	}
}