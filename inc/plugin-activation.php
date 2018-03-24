<?php
/**
 * Install and activate required plugin(s)
 *
 * @package    TGM-Plugin-Activation
 * @subpackage SMNTCS Room Bookings
 * @version    2.6.1 for plugin SMNTCS Room Bookings
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

/**
 * Register the required plugins for this theme.
 */
add_action( 'tgmpa_register', 'smntcs_room_bookings_register_required_plugins' );
function smntcs_room_bookings_register_required_plugins() {
	$plugins = array(
		array(
			'name'      => 'Advanced Custom Fields',
			'slug'      => 'advanced-custom-fields',
			'required'  => true,
		),
	);

	$config = array(
		'id'           => 'smntcs-room-bookings',   // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                       // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins',  // Menu slug.
		'parent_slug'  => 'plugins.php',            // Parent menu slug.
		'capability'   => 'manage_options',         // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                     // Show admin notices or not.
		'dismissable'  => false,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                       // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                    // Automatically activate plugins after installation or not.
		'message'      => '',                       // Message to output right before the plugins table.
		'strings'      => array(
			'page_title'                      => __( 'Install Required Plugins', 'smntcs-room-bookings' ),
			'menu_title'                      => __( 'Install Plugins', 'smntcs-room-bookings' ),
			'installing'                      => __( 'Installing Plugin: %s', 'smntcs-room-bookings' ),
			'updating'                        => __( 'Updating Plugin: %s', 'smntcs-room-bookings' ),
			'oops'                            => __( 'Something went wrong with the plugin API.', 'smntcs-room-bookings' ),
			'notice_can_install_required'     => _n_noop( 'SMNTCS Room Bookings requires the following plugin: %1$s.', 'SMNTCS Room Bookings requires the following plugins: %1$s.', 'smntcs-room-bookings' ),
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'smntcs-room-bookings' ),
			'notice_ask_to_update_maybe'      => _n_noop( 'There is an update available for: %1$s.', 'There are updates available for the following plugins: %1$s.', 'smntcs-room-bookings' ),
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'smntcs-room-bookings' ),
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'smntcs-room-bookings' ),
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'smntcs-room-bookings' ),
			'update_link' 					  => _n_noop( 'Begin updating plugin', 'Begin updating plugins', 'smntcs-room-bookings' ),
			'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'smntcs-room-bookings' ),
			'return'                          => __( 'Return to Required Plugins Installer', 'smntcs-room-bookings' ),
			'plugin_activated'                => __( 'Plugin activated successfully.', 'smntcs-room-bookings' ),
			'activated_successfully'          => __( 'The following plugin was activated successfully:', 'smntcs-room-bookings' ),
			'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'smntcs-room-bookings' ),
			'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'smntcs-room-bookings' ),
			'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'smntcs-room-bookings' ),
			'dismiss'                         => __( 'Dismiss this notice', 'smntcs-room-bookings' ),
			'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'smntcs-room-bookings' ),
			'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'smntcs-room-bookings' ),
			'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
		),
	);

	tgmpa( $plugins, $config );
}
