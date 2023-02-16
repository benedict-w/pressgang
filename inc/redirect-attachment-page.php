<?php

namespace PressGang;

/**
 * Prevent indexing of attachment pages
 */
class RedirectAttachmentPage {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'rewrite_rules_array',
			[ $this, 'remove_attachment_rewrite_rules' ] );

		add_filter( 'attachment_link',
			[ $this, 'cleanup_media_attachment_link' ] );
	}

	/**
	 * @return void
	 */
	public function remove_attachment_rewrite_rules( $rules ) {
		foreach ( $rules as $regex => $query ) {
			if ( strpos( $regex, 'attachment' ) || strpos( $query,
					'attachment' ) ) {
				unset( $rules[ $regex ] );
			}
		}

		return $rules;
	}

	/**
	 * @param $link
	 *
	 * @return void
	 */
	public function cleanup_media_attachment_link( $link ) {
		return;
	}

}

new RedirectAttachmentPage();