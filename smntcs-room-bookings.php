<?php
/*
Plugin Name: SMNTCS Room Bookings
Plugin URI: https://github.com/nielslange/smntcs-room-bookings
Description: Simple room booking management system
Author: Niels Lange
Author URI: https://nielslange.com
Text Domain: smntcs-room-bookings
Domain Path: /languages/
Version: 1.0
Requires at least: 3.4
Tested up to: 4.9
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

/*  Copyright 2014-2018	Niels Lange (email : info@nielslange.de)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Avoid direct plugin access
if ( !defined( 'ABSPATH' ) ) die('¯\_(ツ)_/¯');

// Load custom taxonomy and custom post type
require_once ( plugin_dir_path( __FILE__ ) . '/inc/plugin-activation.php' );
require_once ( plugin_dir_path( __FILE__ ) . '/inc/cpt-booking.php' );
require_once ( plugin_dir_path( __FILE__ ) . '/inc/ctax-room.php' );

// Enhance debugging
if ( !function_exists( 'debug' ) ) {
	function debug( $data ) {
		print('<pre>');
		print_r($data);
		print('</pre>');
	}
}

/**
 * @todo give it some info ¯\_(ツ)_/¯
 */
register_activation_hook( __FILE__, 'add_roles_on_plugin_activation' );
function add_roles_on_plugin_activation() {
	add_role( 'guest_role', 'Guest', array( 'read' => false, 'level_0' => true ) );
}

/**
 * @todo give it some info ¯\_(ツ)_/¯
 */
add_action('admin_menu', 'remove_built_in_roles');
function remove_built_in_roles() {
	global $wp_roles;

	$roles_to_remove = array('subscriber', 'contributor', 'author', 'editor');

	foreach ($roles_to_remove as $role) {
		if (isset($wp_roles->roles[$role])) {
			$wp_roles->remove_role($role);
		}
	}
}

/**
 * @todo give it some info ¯\_(ツ)_/¯
 */
add_filter ( 'wppb_register_admin_email_message_with_admin_approval', 'wppb_delete_admin_email_message', 10, 5 );
add_filter ( 'wppb_register_admin_email_message_without_admin_approval', 'wppb_delete_admin_email_message', 10, 5 );
function wppb_delete_admin_email_message(){
	return '';
}

/**
 * @todo give it some info ¯\_(ツ)_/¯
 */
add_action('plugins_loaded', 'smntcs_room_bookings_load_text_domain');
function smntcs_room_bookings_load_text_domain() {
	load_plugin_textdomain( 'smntcs-room-bookings', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}

/**
 * @todo give it some info ¯\_(ツ)_/¯
 */
add_action( 'admin_enqueue_scripts', 'smntcs_room_bookings_load_scripts' );
function smntcs_room_bookings_load_scripts() {
	wp_enqueue_style( 'custom-style', plugins_url('custom.css', __FILE__) );
}

/**
 * @todo give it some info ¯\_(ツ)_/¯
 */
add_action( 'admin_menu', 'smntcs_room_bookings_remove_menu_pages', 999 );
function smntcs_room_bookings_remove_menu_pages() {
	remove_menu_page('edit.php');                   // Posts
	//remove_menu_page('edit.php?post_type=acf');     // ACF
	remove_menu_page('upload.php');                 // Media
	remove_menu_page('link-manager.php');           // Links
	remove_menu_page('edit-comments.php');          // Comments
	remove_menu_page('edit.php?post_type=page');    // Pages
	//remove_menu_page('plugins.php');                // Plugins
	remove_menu_page('themes.php');                 // Appearance
	//remove_menu_page('users.php');                  // Users
	remove_menu_page('tools.php');                  // Tools
	remove_menu_page('options-general.php');        // Settings
}

/**
 * @todo give it some info ¯\_(ツ)_/¯
 */
add_action( 'admin_bar_menu', 'smntcs_room_bookings_remove_nodes', 999 );
function smntcs_room_bookings_remove_nodes( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'site-name' );
	$wp_admin_bar->remove_node( 'comments' );
	//$wp_admin_bar->remove_node( 'new-page' );
	$wp_admin_bar->remove_node( 'new-post' );
	$wp_admin_bar->remove_node( 'new-media' );
}

/**
 * @todo give it some info ¯\_(ツ)_/¯
 */
add_filter('post_updated_messages', 'custom_post_type_messages' );
function custom_post_type_messages($messages) {
	global $post, $post_ID;

	$post_type = get_post_type( $post_ID );
	$obj = get_post_type_object($post_type);

	$singular = $obj->labels->singular_name;

	$viewLink = ($obj->public) ?  ' <a href="%s">View '.strtolower($singular).'</a>' : "";
	$previewLink = ($obj->public) ? ' <a target="_blank" href="%s">Preview '.strtolower($singular).'</a>': "";
	$schedPreviewLink = ($obj->public) ? ' <a target="_blank" href="%2$s">Preview '.strtolower($singular).'</a>': "";

	$messages[$post_type] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __($singular.' updated.'.$viewLink), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __($singular.' updated.'),
		5 => isset($_GET['revision']) ? sprintf( __($singular.' restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __($singular.' published.'.$viewLink), esc_url( get_permalink($post_ID) ) ),
		7 => __('Page saved.'),
		8 => sprintf( __($singular.' submitted.'.$previewLink), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __($singular.' scheduled for: <strong>%1$s</strong>.'.$schedPreviewLink), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __($singular.' draft updated.'.$previewLink), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $messages;
}

