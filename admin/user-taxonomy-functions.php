<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Adds the User Tags page in the admin.
 * Creates the admin page for the 'User Tags' taxonomy under the 'Users' menu.
 */
function user_taxonomy_user_tag_admin_page() {

	$user_tag_taxonomy = get_taxonomy( 'user_tag' );
	
    add_users_page(
		esc_attr( $user_tag_taxonomy->labels->menu_name ),
		esc_attr( $user_tag_taxonomy->labels->menu_name ),
		$user_tag_taxonomy->cap->manage_terms,
		'edit-tags.php?taxonomy=' . $user_tag_taxonomy->name
	);

}
add_action( 'admin_menu', 'user_taxonomy_user_tag_admin_page' );




/**
 * Make menu active Users and User Tags instead of Posts.
 * On 'User Tags' menu under the 'Users' menu active.
 */
function user_taxonomy_active_users_menu($submenu_file) {
    global $parent_file, $submenu_file;
    
    if (isset($_GET['taxonomy']) && $_GET['taxonomy'] === 'user_tag') {
        $parent_file = 'users.php';
        $submenu_file = 'edit-tags.php?taxonomy=user_tag';
    }
    
    return $submenu_file;
}
add_filter('submenu_file', 'user_taxonomy_active_users_menu');




/* Add section to the edit user page in the admin to select user_tag. */
add_action( 'show_user_profile', 'user_taxonomy_add_user_tag_section' );
add_action( 'edit_user_profile', 'user_taxonomy_add_user_tag_section' );

/**
 * Add user tag option to user edit page in admin panel to store user tag taxonomy as taxonomy as well as user metadata.
 *
 * @param object $user The user object currently being edited.
 */
function user_taxonomy_add_user_tag_section( $user ) {
    $tax = get_taxonomy( 'user_tag' );

    if ( !current_user_can( $tax->cap->assign_terms ) ) {
        return;
    }
    ?>

    <h3><?php esc_html_e( 'User Tags', 'user-taxonomy' ); ?></h3>

    <table class="form-table">
        <tr>
            <th><?php esc_html_e( 'Select User Tags', 'user-taxonomy' ); ?></th>
            <td>
                <div id="user-tagdiv" class="postbox">
                    <div class="inside">
                        <div class="taxonomydiv">
                            <!-- Search Box -->
                            <input type="text" id="user-tag-search" class="user-tag-search" placeholder="Search Tags...">

                            <!-- Scrollable List -->
                            <div id="user-tag-list" class="user-tag-list">
                                <ul class="categorychecklist form-no-clear">
                                    <?php 
                                    wp_terms_checklist(
                                        $user->ID, 
                                        array( 
                                            'taxonomy' => 'user_tag'
                                        ) 
                                    ); 
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <?php
}





/* Update the user_tag terms when the edit user page is updated. */
add_action( 'personal_options_update', 'user_taxonomy_save_user_tag_terms' );
add_action( 'edit_user_profile_update', 'user_taxonomy_save_user_tag_terms' );

/**
 * Saves the term selected on the edit user/profile page in the admin. This function is triggered when the page
 * is updated.  We just grab the posted data and use wp_set_object_terms() to save it.
 *
 * @param int $user_id The ID of the user to save the terms for.
 */
function user_taxonomy_save_user_tag_terms( $user_id ) {
    $tax = get_taxonomy( 'user_tag' );

    /* Make sure the current user can edit the user and assign terms before proceeding. */
    if ( !current_user_can( 'edit_user', $user_id ) || !current_user_can( $tax->cap->assign_terms ) ) {
        return false;
    }

    /* Check if any terms are selected; if not, clear the terms */
    if ( isset( $_POST['tax_input']['user_tag'] ) && is_array( $_POST['tax_input']['user_tag'] ) ) {
        $terms = array_map( 'intval', $_POST['tax_input']['user_tag'] ); // Sanitize input array
    } else {
        $terms = array(); // No terms selected, so remove all
    }

    /* Sets the terms for the user. */
    wp_set_object_terms( $user_id, $terms, 'user_tag', false );
    
    /* Sets the usermeta for the user. */
    update_user_meta( $user_id, 'user_tag', $terms );

    clean_object_term_cache( $user_id, 'user_tag' );
}





/**
 * Filter the 'sanitize_user' to disable username.
 * Disables the 'user_tag' username when someone registers. 
 *
 * @param string $username The username of the user before registration is complete.
 */
function user_taxonomy_disable_username( $username ) {

	if ( 'user_tag' === $username )
		$username = '';

	return $username;
}
add_filter( 'sanitize_user', 'user_taxonomy_disable_username' );