<?php
/**
 * Register custom taxonomy room
 *
 * @author Niels Lange <info@nielslange.de>
 * @since 1.0
 */
add_action( 'init', 'ctax_room', 0 );
function ctax_room() {

	$labels = array(
		'name'                       => _x( 'Room', 'Taxonomy General Name', 'smntcs-room-bookings' ),
		'singular_name'              => _x( 'Room', 'Taxonomy Singular Name', 'smntcs-room-bookings' ),
		'menu_name'                  => __( 'Rooms', 'smntcs-room-bookings' ),
		'all_items'                  => __( 'All Rooms', 'smntcs-room-bookings' ),
		'new_item_name'              => __( 'New Room Name', 'smntcs-room-bookings' ),
		'add_new_item'               => __( 'Add New Room', 'smntcs-room-bookings' ),
		'edit_item'                  => __( 'Edit Room', 'smntcs-room-bookings' ),
		'update_item'                => __( 'Update Room', 'smntcs-room-bookings' ),
		'view_item'                  => __( 'View Room', 'smntcs-room-bookings' ),
		'separate_items_with_commas' => __( 'Separate rooms with commas', 'smntcs-room-bookings' ),
		'add_or_remove_items'        => __( 'Add or remove rooms', 'smntcs-room-bookings' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'smntcs-room-bookings' ),
		'popular_items'              => __( 'Popular Rooms', 'smntcs-room-bookings' ),
		'search_items'               => __( 'Search Rooms', 'smntcs-room-bookings' ),
		'not_found'                  => __( 'Not Found', 'smntcs-room-bookings' ),
		'no_terms'                   => __( 'No rooms', 'smntcs-room-bookings' ),
		'items_list'                 => __( 'Rooms list', 'smntcs-room-bookings' ),
		'items_list_navigation'      => __( 'Rooms list navigation', 'smntcs-room-bookings' ),
		'back_to_items'             =>  __( '&larr; Back to Rooms', 'smntcs-room-bookings' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
		'show_in_rest'               => false,
		'meta_box_cb'                => false,

	);
	register_taxonomy( 'room', array( 'booking' ), $args );
}

/**
 * @todo give it some info ¯\_(ツ)_/¯
 */
add_action( 'room_add_form', 'hide_obsolete_room_ields', 10, 2 );
add_action( 'room_edit_form', 'hide_obsolete_room_ields', 10, 2 );
function hide_obsolete_room_ields( $taxonomy ) {
	?><style></style><?php
}

/**
 * @todo give it some info ¯\_(ツ)_/¯
 */
add_filter('manage_edit-room_columns', function ( $columns ) {
	if( isset( $columns['slug'] ) )
		unset( $columns['slug'] );

	if( isset( $columns['description'] ) )
		unset( $columns['description'] );

	if( isset( $columns['posts'] ) )
		unset( $columns['posts'] );

	$columns['ppd'] = __( 'Price per day', 'smntcs-room-bookings' );
	$columns['ppm'] = __( 'Price per month', 'smntcs-room-bookings' );
	$columns['ac']  = __( 'AC', 'smntcs-room-bookings' );

	return $columns;
} );

/**
 * @todo give it some info ¯\_(ツ)_/¯
 */
add_action('manage_room_custom_column', function ( $null, $column, $post_id ) {

	$data = get_option('room_' . $post_id);

	switch ( $column ) {
		case 'ppd':
			echo 'RP ' . number_format($data['ppd'], null, ',', '.');
			break;
		case 'ppm':
			echo 'RP ' . number_format($data['ppm'], null, ',', '.');
			break;
		case 'ac':
			echo isset ( $data['ac'] ) && $data['ac'] == 'on' ? '✓' : '✘';
			break;
	}

	//debug( $data );

	//debug( [ $null, $column, $post_id ] );

}, 10, 3);

/**
 * @todo give it some info ¯\_(ツ)_/¯
 */
add_action( 'room_add_form_fields', 'smntcs_room_bookings_add_form_fields', 10, 2 );
function smntcs_room_bookings_add_form_fields() {
	?>

	<div class="form-field">
		<label for="term_meta[ppd]"><?php _e( 'Price per day', 'smntcs-room-bookings' ); ?></label>
		<input type="number" name="term_meta[ppd]" id="term_meta[ppd]" value="">
		<p class="description"><?php _e( 'How much does the room costs per day?','smntcs-room-bookings' ); ?></p>
	</div>

	<div class="form-field">
		<label for="term_meta[ppm]"><?php _e( 'Price per month', 'smntcs-room-bookings' ); ?></label>
		<input type="number" name="term_meta[ppm]" id="term_meta[ppm]" value="">
		<p class="description"><?php _e( 'How much does the room costs per month?','smntcs-room-bookings' ); ?></p>
	</div>

	<div class="form-field">
		<label for="term_meta[ac]"><?php _e( 'Air conditioner', 'smntcs-room-bookings' ); ?></label>
		<input type="checkbox" name="term_meta[ac]" id="term_meta[ac]" value="true"> Air conditioner available
	</div>

	<?php
}

/**
 * @todo give it some info ¯\_(ツ)_/¯
 */
add_action( 'room_edit_form_fields', 'smntcs_room_bookings_edit_form_fields', 10, 2 );
function smntcs_room_bookings_edit_form_fields($term) {
	$t_id       = $term->term_id;
	$term_meta  = get_option( "room_$t_id" ); ?>

	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[ppd]"><?php _e( 'Price per day', 'smntcs-room-bookings' ); ?></label></th>
		<td>
			<input type="number" name="term_meta[ppd]" id="term_meta[ppd]" value="<?php echo esc_attr( $term_meta['ppd'] ) ? esc_attr( $term_meta['ppd'] ) : ''; ?>">
			<p class="description"><?php _e( 'How much does the room costs per day?','smntcs-room-bookings' ); ?></p>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[ppm]"><?php _e( 'Price per month', 'smntcs-room-bookings' ); ?></label></th>
		<td>
			<input type="number" name="term_meta[ppm]" id="term_meta[ppm]" value="<?php echo esc_attr( $term_meta['ppm'] ) ? esc_attr( $term_meta['ppm'] ) : ''; ?>">
			<p class="description"><?php _e( 'How much does the room costs per month?','smntcs-room-bookings' ); ?></p>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[ac]"><?php _e( 'Air conditioner', 'smntcs-room-bookings' ); ?></label></th>
		<td>
			<input type="checkbox" name="term_meta[ac]" id="term_meta[ac]" <?php echo isset ($term_meta['ac']) && esc_attr( $term_meta['ac'] == 'on' ) ? 'checked="checked"' : ''; ?>> Air conditioner available
			<p class="description"><?php _e( 'Does this room has an air conditioner?','smntcs-room-bookings' ); ?></p>
		</td>
	</tr>
	<?php
}

/**
 * @todo give it some info ¯\_(ツ)_/¯
 */
add_action( 'edited_room', 'smntcs_room_bookings_save_taxonomy_custom_meta', 10, 2 );
add_action( 'create_room', 'smntcs_room_bookings_save_taxonomy_custom_meta', 10, 2 );
function smntcs_room_bookings_save_taxonomy_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id       = $term_id;
		$term_meta  = get_option( "room_" . $t_id );
		$cat_keys   = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		update_option( "room_" . $t_id, $term_meta );
	}
}