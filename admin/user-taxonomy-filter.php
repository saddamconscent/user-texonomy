<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
________________________________________________________________________

  *** Filtering Users by User Tags in users list admin panel ***

________________________________________________________________________

*
* Add a new dropdown filter above the Users table in the admin panel.
* This dropdown should appear after the bulk role change interface.
*** Dynamic AJAX-powered User Tag Search ***
* Implement a searchable, dynamic dropdown for selecting User Tags.
* Use Select2 or Selectize.js for enhanced UI and AJAX-based data loading.
* The dropdown should fetch and display available user tags dynamically.
*** Filter Users Based on Selected Tags ***
* On selecting a User Tag and submitting the filter, the Users screen would reload and display only users with the selected tag.
*
*/

// Add User Tag Filter Dropdown to Users Table using Select2
function user_taxonomy_user_tag_filter() {
    $terms = get_terms(array(
        'taxonomy'   => 'user_tag',
        'hide_empty' => false,
    ));
    $current_filter = isset($_GET['user_tag_filter']) ? $_GET['user_tag_filter'] : '';
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let bulkActions = document.querySelector(".bulkactions");
            if (bulkActions) {
                let filterForm = document.createElement("div");
                filterForm.style.display = "inline-block";
                filterForm.style.marginLeft = "10px";
                filterForm.innerHTML = `
                    <label for="user_tag_filter" class="screen-reader-text"><?php esc_html_e('Filter by User Tag', 'user-taxonomy'); ?></label>
                    <select name="user_tag_filter" id="user_tag_filter" class="user-tag-select2">
                        <option value=""><?php esc_html_e('All User Tags', 'user-taxonomy'); ?></option>
                        <?php foreach ($terms as $term) { ?>
                            <option value="<?php echo esc_attr($term->term_id); ?>" <?php selected($current_filter, $term->term_id); ?>>
                                <?php echo esc_html($term->name); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <?php submit_button(__('Filter'), '', 'filter_action', false, ['style' => 'margin-left: 5px;']); ?>
                `;
                bulkActions.insertAdjacentElement("afterend", filterForm);
                
                // Initialize Select2
                jQuery(document).ready(function($) {
                    $('.user-tag-select2').select2({
                        width: '200px',
                        placeholder: "Select User Tag",
                        allowClear: true
                    });
                });
            }
        });
    </script>
    <?php
}
add_action('admin_footer', 'user_taxonomy_user_tag_filter');





/**
 * Modify user query based on selected filter
 *
 * Add meta_query to wp_get_user query
 * @param string $query The wp_get_user query before execute the query.
 */
function user_taxonomy_modify_get_user_query($query) {
    global $pagenow;
    if (is_admin() && 'users.php' === $pagenow && !empty($_GET['user_tag_filter'])) {
        $query->set('meta_query', array(
            array(
                'key'     => 'user_tag',
                'value'   => $_GET['user_tag_filter'],
                'compare' => "LIKE"
            ),
        ));
    }
}
add_action('pre_get_users', 'user_taxonomy_modify_get_user_query');