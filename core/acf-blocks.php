<?php

namespace PressGang;

/**
 * Class AcfBlocks
 *
 * @package PressGang
 */
class AcfBlocks {
	protected $custom_categories = array();

	/**
	 * __construct
	 *
	 */
	public function __construct() {
		add_action( 'acf/init', array( $this, 'setup' ) );
		add_filter( 'block_categories_all', array( $this, 'add_custom_categories' ) );

		// Fix in WordPress 5.11 ?
		// add_filter('render_block', array($this, 'disable_wpautop'), 20, 2);
	}

	/**
	 * setup
	 *
	 */
	public function setup() {
		if ( function_exists( 'acf_register_block_type' ) ) {

			$blocks = Config::get( 'acf-blocks' );

			foreach ( $blocks as $key => &$settings ) {

				// when category is an array use it to register custom categories
				// otherwise expect category to be the slug for a default gutenberg category
				if ( isset( $settings['category']) && is_array( $settings['category'] ) ) {

					$this->custom_categories[ $settings['category']['slug'] ] = $settings['category'];
					$settings['category']                                     = $settings['category']['slug'];

				}

				acf_register_block_type( $settings );

				// load the template for the block
				$inc = preg_match( '/.php/', $key ) ? "blocks/{$key}" : "blocks/{$key}.php";
				locate_template( $inc, true, true );
			}
		}

	}

	/**
	 * add_custom_categories
	 *
	 * @param $categories
	 * @param $post
	 *
	 * @return array
	 */
	public function add_custom_categories( $categories ) {

		return array_values( array_merge( $categories, $this->custom_categories ) );

	}

	/**
	 * disable_wpautop
	 *
	 * Try to disable wpautop inside specific blocks.
	 *
	 * @link https://wordpress.stackexchange.com/q/321662/26317
	 *
	 * @param string $block_content The HTML generated for the block.
	 * @param array $block The block.
	 */
	public function disable_wpautop( $block_content, $block ) {
		if ( in_array( preg_replace( '/^acf\//', '', $block['blockName'] ), array_keys( Config::get( 'acf-blocks' ) ) ) ) {
			\remove_filter( 'the_content', 'wpautop' );
		}

		return $block_content;
	}

}

new AcfBlocks();