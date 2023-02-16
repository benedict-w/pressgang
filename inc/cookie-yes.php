<?php

namespace PressGang;

class CookieYes {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action('customize_register', array($this, 'customizer'));
		add_action('wp_head', [$this, 'cookieyes_header_script']);
	}

	/**
	 * Add to customizer
	 *
	 * @param $wp_customize
	 */
	public function customizer($wp_customize) {

		if (!isset($wp_customize->sections['cookieyes'])) {
			$wp_customize->add_section('cookieyes', array(
				'title' => __("CookieYes", THEMENAME),
			));
		}

		$wp_customize->add_setting(
			'cookieyes-id',
			array(
				'default' => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'cookieyes-id', array(
			'label' => __("CookieYes ID", THEMENAME),
			'description' => sprintf(__("See %s"), 'https://www.cookieyes.com/'),
			'section'  => 'cookieyes',
		)));
	}

	/**
	 * cookieyes_header_script
	 *
	 * @return void
	 */
	public function cookieyes_header_script() {

		if ($cookieyes_id = get_theme_mod('cookieyes-id')) {
            echo "<script id=\"cookieyes\" type=\"text/javascript\" src=\"https://cdn-cookieyes.com/client_data/{$cookieyes_id}/script.js\"></script>";
		}
	}

}

new CookieYes();