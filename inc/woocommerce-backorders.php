<?php

namespace PressGang;

/**
 * Class WooCommerceBackorders
 *
 * An include file adding a WooCommerce Backorder Date
 *
 * @package EightBar
 */
class WooCommerceBackorders {
	/**
	 * WooCommerce constructor.
	 */
	public function __construct() {
		// Display and save custom field in WooCommerce
		add_action( 'woocommerce_product_options_stock_status', [
			$this,
			'woocommerce_product_custom_fields'
		], 10 );
		add_action( 'woocommerce_process_product_meta', [ $this, 'woocommerce_product_custom_fields_save' ] );

		// Message display
		add_filter( 'woocommerce_out_of_stock_message', [ $this, 'out_of_stock_message' ] );
		add_filter( 'woocommerce_get_availability_text', [ $this, 'availability_backorder_text' ], 10, 2 );
		add_filter( 'woocommerce_composited_product_availability', [ $this, 'availability_backorder_text' ], 10, 2 );
		add_filter( 'woocommerce_composited_product_availability_text', [
			$this,
			'availability_backorder_text'
		], 10, 2 );
	}

	/**
	 * out_of_stock_message
	 */
	public function out_of_stock_message( $text ) {
		global $product;

		if ( $product ) {
			if ( $backorder_date = get_field( 'backorder_date', $product->get_id() ) ) {
				$backorder_date = wp_date( get_option( 'date_format' ), strtotime( $backorder_date ) );
				$text           = sprintf( __( "Out of stock. Expected delivery date %s.", THEMENAME ), $backorder_date );
			}
		}

		return $text;
	}

	/**
	 * availability_backorder_text
	 *
	 * @param $availability
	 * @param $product
	 *
	 * @return mixed
	 */
	public function availability_backorder_text( $availability, $instance ) {

		if ( is_a( $instance, 'WC_CP_Product' ) ) {
			$product = $instance->get_product();
		} else {
			global $product;
		}

		if ( $product ) {

			switch ( $product->get_stock_status() ) {
				case 'onbackorder' :
					if ( $backorder_date = get_field( 'backorder_date', $product->get_id() ) ) {
						$backorder_date = wp_date( get_option( 'date_format' ), strtotime( $backorder_date ) );
						$availability   =
							sprintf( __( "Available on backorder. Expected delivery date %s.", THEMENAME ), $backorder_date );
					}
					break;
				case 'outofstock' :
					$availability = $this->out_of_stock_message( $availability );
					break;
			}
		}

		return $availability;
	}

	/**
	 * Create custom date picker field
	 *
	 * @return void
	 */
	public function woocommerce_product_custom_fields() {
		global $woocommerce, $post;

		echo '<div class="product_custom_field form-field backorder-date hide_if_variable hide_if_external hide_if_grouped">';
		woocommerce_wp_text_input( [
			'id'       => 'backorder_date',
			'label'    => __( 'Backorder date', 'woocommerce' ),
			'desc_tip' => 'true',
			'type'     => 'date'
		] );
		echo '</div>';
	}

	/**
	 * woocommerce_product_custom_fields_save
	 *
	 * @param $post_id
	 *
	 * @return void
	 */
	public function woocommerce_product_custom_fields_save( $post_id ) {

		$backorder_date = $_POST['backorder_date'];

		if ( ! empty( $backorder_date ) ) {
			update_post_meta( $post_id, 'backorder_date', esc_attr( $backorder_date ) );
		}
	}

}

new WooCommerceBackorders();