<?php
/**
 * Plugin Name: Disable Comments API
 * Description: Closes the WordPress comments API to POST requests without changing the discussion settings.
 * Version:     1.0.0
 * Plugin URI:  https://www.boston.com
 * Author:      Peter Mumford
 */


// Hook into the `init` action
add_action( 'init', 'wp_block_comment_requests' );

function wp_block_comment_requests() {
	// Check if the request is for wp-comments-post.php
	if ( isset( $_SERVER['SCRIPT_NAME'] ) && basename( $_SERVER['SCRIPT_NAME'] ) === 'wp-comments-post.php' ) {
		// Disable comments for all users, logged in or not.
		// Set a 403 Forbidden header
		header( 'HTTP/1.1 403 Forbidden' );
		header( 'Content-Type: application/json' );
		echo json_encode(
			[
				'code'    => 'rest_forbidden',
				'message' => 'You are not allowed to post comments.',
				'data'    => [ 'status' => 403 ],
			]
		);
		exit; // Stop further processing 
	}
}

add_action( 'rest_api_init', 'disable_rest_comments_endpoint', 15 );

function disable_rest_comments_endpoint() {
	remove_action( 'rest_api_init', 'wp_rest_comments_controller' );
}
