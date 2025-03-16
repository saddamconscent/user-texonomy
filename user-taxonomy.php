<?php
/*
 * Plugin Name:       User Taxonomy
 * Plugin URI:        https://user-taxonomy.com/
 * Description:       A User Taxonomy Plugin allows you to create custom categories for WordPress users, similar to how post taxonomies (like categories and tags) work. This helps in organizing users based on roles, departments, interests, or other custom groupings.
 * Requires at least: 5.6
 * Requires PHP:      7.4
 * Version:           1.0.0
 * Author:            Saddam Hussain
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain:       user-taxonomy
 * Domain Path:       /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// Define global variables...

define( 'USER_TAXONOMY_NAME', 'User Taxonomy for WordPress' );
define( 'USER_TAXONOMY_SLUG', 'user-taxonomy' );
define( 'USER_TAXONOMY_VERSION', '1.0.0' );
define( 'USER_TAXONOMY_URL', plugin_dir_url( __FILE__ ) );
define( 'USER_TAXONOMY_PATH', plugin_dir_path( __FILE__ ) );
define( 'USER_TAXONOMY_BASENAME', plugin_basename( __FILE__ ) );
define( 'USER_TAXONOMY_REL_DIR', dirname( USER_TAXONOMY_BASENAME ) );


// Enqueue Select2 for dropdown filter admin panel
function enqueue_select2_for_user_tag() {
    wp_enqueue_script('select2-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), USER_TAXONOMY_VERSION, true);
    wp_enqueue_style('select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', array(), USER_TAXONOMY_VERSION);
    wp_enqueue_script( 'user-taxonomy-js', USER_TAXONOMY_URL . 'assets/js/user-taxonomy.js', array(), USER_TAXONOMY_VERSION, true );
    wp_enqueue_style( 'style-css', USER_TAXONOMY_URL . 'assets/css/style.css', array(), USER_TAXONOMY_VERSION );
}
add_action('admin_enqueue_scripts', 'enqueue_select2_for_user_tag');


// Include necessary files.
require_once USER_TAXONOMY_PATH . 'admin/taxonomy.php';
require_once USER_TAXONOMY_PATH . 'admin/user-taxonomy-functions.php';
require_once USER_TAXONOMY_PATH . 'admin/user-taxonomy-filter.php';