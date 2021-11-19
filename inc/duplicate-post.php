<?php

namespace PressGang;

/**
 * Duplicate posts and pages without plugins
 *
 * @package PressGang
 */
class DuplicatePost {

	/**
	 * Adds the duplicate link to action list for post_row_actions for "post" and custom post types.
	 *
	 */
	public function __construct() {
		add_filter( 'post_row_actions', array( $this, 'duplicate_post_link' ), 10, 2 );
		add_filter( 'page_row_actions', array( $this, 'duplicate_post_link' ), 10, 2 );
		add_action( 'admin_action_duplicate_post_as_draft', array( $this, 'duplicate_post_as_draft' ) );
		add_action( 'admin_notices', array( $this, 'duplication_admin_notice' ) );
	}

	/**
	 * @param $actions
	 * @param $post
	 *
	 * @return mixed
	 */
	public function duplicate_post_link( $actions, $post ) {

		if ( ! current_user_can( 'edit_posts' ) ) {
			return $actions;
		}

		$url = wp_nonce_url(
			add_query_arg(
				array(
					'action' => 'duplicate_post_as_draft',
					'post'   => $post->ID,
				),
				'admin.php'
			),
			basename( __FILE__ ),
			'duplicate_nonce'
		);

		$actions['duplicate'] = sprintf( "<a href=\"%s\" title=\"%s\" rel=\"permalink\">%s</a>",
			$url,
			__( "Duplicate this item" ),
			__( "Duplicate" ) );

		return $actions;
	}

	/**
	 * Creates a duplicate post as a draft, and redirects to the edit post screen.
	 *
	 */
	public function duplicate_post_as_draft() {

		if ( empty( $_GET['post'] ) ) {
			wp_die( __( "No post to duplicate has been provided!" ) );
		}

		if ( ! isset( $_GET['duplicate_nonce'] ) || ! wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) ) {
			return;
		}

		$post_id = absint( $_GET['post'] );

		$current_user    = wp_get_current_user();
		$new_post_author = $current_user->ID;

		if ( $post = get_post( $post_id ) ) {

			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => 'draft',
				'post_title'     => $post->post_title,
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order
			);

			$new_post_id = wp_insert_post( $args );


			// get all current post terms and add them to the new post draft
			if ( $taxonomies = get_object_taxonomies( get_post_type( $post ) ) ) {
				foreach ( $taxonomies as $taxonomy ) {
					$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
					wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
				}
			}

			// duplicate all post meta
			if ( $post_meta = get_post_meta( $post_id ) ) {

				foreach ( $post_meta as $meta_key => $meta_values ) {

					if ( '_wp_old_slug' == $meta_key ) { // do nothing for this meta key
						continue;
					}

					foreach ( $meta_values as $meta_value ) {
						add_post_meta( $new_post_id, $meta_key, $meta_value );
					}
				}
			}

			// redirect to the edit post screen for the new draft
			wp_safe_redirect(
				add_query_arg(
					array(
						'action' => 'edit',
						'post'   => $new_post_id
					),
					admin_url( 'post.php' )
				)
			);

			/*
			// or redirect to all posts with a message
			wp_safe_redirect(
				add_query_arg(
					array(
						'post_type' => ( 'post' !== get_post_type( $post ) ? get_post_type( $post ) : false ),
						'saved'     => 'post_duplication_created' // just a custom slug here
					),
					admin_url( 'edit.php' )
				)
			);
			*/

			exit;

		} else {
			wp_die( __( "Post creation failed, could not find original post." ) );
		}

	}

	/**
	 * Add admin notices
	 *
	 */
	public function duplication_admin_notice() {

		$screen = get_current_screen();

		if ( 'edit' !== $screen->base ) {
			return;
		}

		if ( isset( $_GET['saved'] ) && 'post_duplication_created' == $_GET['saved'] ) {

			echo sprintf( "<div class=\"notice notice-success is-dismissible\"><p>%s</p></div>", __( "Post copy created." ) );

		}
	}
}

new DuplicatePost();