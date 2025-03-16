<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the 'user tag' taxonomy for users.
 */
function user_taxonomy_register_custom_taxonomy() {

    $args = array(
        'public'            => true,
        'hierarchical'      => false,
        'show_admin_column' => true,
        'show_ui'           => true,
        'show_in_menu'      => 'users.php',
        'show_tagcloud'     => true,
        'labels' => array(
            'name' => __( 'User Tag' ),
            'singular_name' => __( 'User Tag' ),
            'menu_name' => __( 'User Tags' ),
            'search_items' => __( 'Search User Tags' ),
            'popular_items' => __( 'Popular User Tags' ),
            'all_items' => __( 'All User Tags' ),
            'edit_item' => __( 'Edit User Tag' ),
            'update_item' => __( 'Update User Tag' ),
            'add_new_item' => __( 'Add New User Tag' ),
            'new_item_name' => __( 'New User Tag Name' ),
            'separate_items_with_commas' => __( 'Separate User Tags with commas' ),
            'add_or_remove_items' => __( 'Add or remove User Tags' ),
            'choose_from_most_used' => __( 'Choose from the most popular User Tags' ),
        ),
        'rewrite' => array(
            'with_front' => true,
            'slug' => 'author/user_tag'
        ),
        'capabilities' => array(
            'manage_terms' => 'edit_users',
            'edit_terms'   => 'edit_users',
            'delete_terms' => 'edit_users',
            'assign_terms' => 'read',
        ),
        'update_count_callback' => 'user_taxonomy_update_user_tag_count'
    );

    register_taxonomy( 'user_tag', 'user', $args );
}
add_action('init', 'user_taxonomy_register_custom_taxonomy');





/**
 * Function for updating the 'user_tag' taxonomy count.  
 *
 * See the _update_post_term_count() function in WordPress for more info.
 *
 * @param array $terms List of Term taxonomy IDs
 * @param object $taxonomy Current taxonomy object of terms
 */
function user_taxonomy_update_user_tag_count( $terms, $taxonomy ) {

	global $wpdb;

	foreach ( (array) $terms as $term ) {

		$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d", $term ) );

		do_action( 'edit_term_taxonomy', $term, $taxonomy );
		$wpdb->update( $wpdb->term_taxonomy, compact( 'count' ), array( 'term_taxonomy_id' => $term ) );
		do_action( 'edited_term_taxonomy', $term, $taxonomy );
        
	}

}