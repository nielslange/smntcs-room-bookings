<?php
/**
 * Register custom post type booking
 *
 * @author Niels Lange <info@nielslange.de>
 * @since 1.0
 */
add_action( 'init', 'cpt_booking', 0);
function cpt_booking() {
	$labels = array(
		'name'                  => _x( 'Booking', 'Post Type General Name', 'smntcs-room-bookings' ),
		'singular_name'         => _x( 'Booking', 'Post Type Singular Name', 'smntcs-room-bookings' ),
		'menu_name'             => __( 'Bookings', 'smntcs-room-bookings' ),
		'name_admin_bar'        => __( 'Booking', 'smntcs-room-bookings' ),
		'archives'              => __( 'Booking Archives', 'smntcs-room-bookings' ),
		'attributes'            => __( 'Booking Attributes', 'smntcs-room-bookings' ),
		'all_items'             => __( 'All Bookings', 'smntcs-room-bookings' ),
		'add_new_item'          => __( 'Add New Booking', 'smntcs-room-bookings' ),
		'add_new'               => __( 'Add New', 'smntcs-room-bookings' ),
		'new_item'              => __( 'New Booking', 'smntcs-room-bookings' ),
		'edit_item'             => __( 'Edit Booking', 'smntcs-room-bookings' ),
		'update_item'           => __( 'Update Booking', 'smntcs-room-bookings' ),
		'view_item'             => __( 'View Booking', 'smntcs-room-bookings' ),
		'view_items'            => __( 'View Bookings', 'smntcs-room-bookings' ),
		'search_items'          => __( 'Search Booking', 'smntcs-room-bookings' ),
		'not_found'             => __( 'Not found', 'smntcs-room-bookings' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'smntcs-room-bookings' ),
		'featured_image'        => __( 'Featured Image', 'smntcs-room-bookings' ),
		'set_featured_image'    => __( 'Set featured image', 'smntcs-room-bookings' ),
		'remove_featured_image' => __( 'Remove featured image', 'smntcs-room-bookings' ),
		'use_featured_image'    => __( 'Use as featured image', 'smntcs-room-bookings' ),
		'insert_into_item'      => __( 'Insert into booking', 'smntcs-room-bookings' ),
		'uploaded_to_this_item' => __( 'Uploaded to this booking', 'smntcs-room-bookings' ),
		'items_list'            => __( 'Bookings list', 'smntcs-room-bookings' ),
		'items_list_navigation' => __( 'Bookings list navigation', 'smntcs-room-bookings' ),
		'filter_items_list'     => __( 'Filter bookings list', 'smntcs-room-bookings' ),
	);
	$args = array(
		'label'                 => __( 'booking', 'smntcs-room-bookings' ),
		'description'           => __( 'Post Type Description', 'smntcs-room-bookings' ),
		'labels'                => $labels,
		'supports'              => array( '' ),
		'taxonomies'            => array( 'room' ),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-admin-home',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => false,
	);
	register_post_type( 'booking', $args );
}

/**
 * @todo give it some info ¯\_(ツ)_/¯
 */
add_filter('manage_edit-booking_columns', function ( $columns ) {

	/*
	*/
	if ( isset( $columns['title'] ) ) {
		unset( $columns['title'] );
	}

	if ( isset( $columns['date'] ) ) {
		unset( $columns['date'] );
	}

	if ( isset( $columns['taxonomy-room'] ) ) {
		unset( $columns['taxonomy-room'] );
	}

	$columns['name']        = __( 'Guest', 'smntcs-room-bookings' );
	$columns['room']        = __( 'Room', 'smntcs-room-bookings' );
	$columns['checkin']     = __( 'Checkin', 'smntcs-room-bookings' );
	$columns['checkout']    = __( 'Checkout', 'smntcs-room-bookings' );
	$columns['stay']        = __( 'Stay', 'smntcs-room-bookings' );
	$columns['ppn']         = __( 'Price per night', 'smntcs-room-bookings' );
	$columns['tp']          = __( 'Total price', 'smntcs-room-bookings' );

	return $columns;
} );

/**
 * @todo give it some info ¯\_(ツ)_/¯
 */
add_action('manage_booking_posts_custom_column', function ( $column, $post_id ) {
	$id = $post_id;

	switch ( $column ) {
		case 'name':
			$user = get_field( 'booking_name', $id );
			printf('<a href="/wp-admin/post.php?post=%s&action=edit">%s %s</a>', $id, $user['user_firstname'], $user['user_lastname']);
			break;
		case 'room':
			$rid    = get_field( 'booking_room', $id );
			$room   = get_term($rid, 'room');
			printf('<a href="/wp-admin/term.php?taxonomy=room&tag_ID=%s&post_type=booking">%s</a>', $rid, $room->name);
			break;
		case 'checkin':
			$source = get_field( 'booking_checkin', $id );
			$date   = new DateTime($source);
			print($date->format('l, F d, Y'));
			break;
		case 'checkout':
			$source = get_field( 'booking_checkout', $id );
			$date   = new DateTime($source);
			print($date->format('l, F d, Y'));
			break;
		case 'stay':
			$in     = new DateTime(get_field( 'booking_checkin', $id ));
			$out    = new DateTime(get_field( 'booking_checkout', $id ));
			$days   = $in->diff($out);
			echo $days->format('%R%a days');
			break;
		case 'ppn':
			$rid    = get_field( 'booking_room', $id );
			$room   = get_term($rid, 'room');
			$tax    = get_option('room_' . $room->term_id);
			printf('RP %s', number_format($tax['ppd'], 0, ',', '.'));
			break;
		case 'tp':
			$rid    = get_field( 'booking_room', $id );
			$room   = get_term($rid, 'room');
			$tax    = get_option('room_' . $room->term_id);
			$in     = new DateTime(get_field( 'booking_checkin', $id ));
			$out    = new DateTime(get_field( 'booking_checkout', $id ));
			$days   = $in->diff($out);
			$price  = $tax['ppd'] * $days->days;
			printf('<strong>RP %s</strong>', number_format($price, 0, ',', '.'));
			break;
	}

	//debug ( 'hallo welt!' );
	//debug( [ $column, $post_id ] );

}, 10, 3);

/**
 * @todo give it some info ¯\_(ツ)_/¯
 */
add_filter( 'list_table_primary_column', 'my_list_table_primary_column', 10, 2 );
function my_list_table_primary_column( $default, $screen ) {
	if ( $screen == 'edit-booking' ) {
		$default = 'name';
	}

	return $default;
}
