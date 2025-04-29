<?php
/**
 * Plugin Name: Post Date Editor
 * Plugin URI:  https://github.com/DigitalBKK/post-date-editor
 * Description: Quick admin page to view / change a post's Published and Last-Modified dates by ID.
 * Version:     1.4.0
 * Author:      DigitalBKK
 * Author URI:  https://digitalbkk.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: post date editor
 * Domain Path: /languages
 * 
 * This plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * 
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this plugin. If not, see http://www.gnu.org/licenses/gpl-2.0.txt.
 */

if (!defined('ABSPATH')) {
    exit; // no direct access
}

/**
 * Add menu item.
 */
add_action('admin_menu', function () {
    add_management_page(
        'Post Date Editor',     // Page title
        'Post Date Editor',     // Menu label
        'manage_options',       // Capability
        'post-date-editor',     // Slug
        'cpde_render_admin'     // Callback
    );
});

/**
 * Add admin notices.
 */
add_action('admin_notices', function () {
    if (!isset($_GET['page']) || $_GET['page'] !== 'post-date-editor' || !check_admin_referer('post-date-editor', false)) {
        return;
    }

    if (isset($_GET['updated']) && current_user_can('manage_options')) {
        printf(
            '<div class="updated notice is-dismissible"><p>%s</p></div>',
            esc_html__('Post dates updated successfully.', 'post date editor')
        );
    }

    if (isset($_GET['error']) && current_user_can('manage_options')) {
        printf(
            '<div class="error notice is-dismissible"><p>%s</p></div>',
            esc_html__('Error updating post dates. Please try again.', 'post date editor')
        );
    }
});

/**
 * Render admin screen & handle actions.
 */
function cpde_render_admin()
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have permission to access this page.', 'post date editor'));
    }
    ?>
    <div class="wrap">
        <h1>Post Date Editor</h1>

        <!-- Search options -->
        <div class="cpde-search-options">
            <div class="cpde-search-tabs">
                <button type="button" class="cpde-tab-button active"
                    data-tab="id"><?php esc_html_e('Search by ID', 'post date editor'); ?></button>
                <button type="button" class="cpde-tab-button"
                    data-tab="title"><?php esc_html_e('Search by Title', 'post date editor'); ?></button>
            </div>

            <!-- ID Search -->
            <div class="cpde-search-panel active" id="cpde-id-search">
                <div class="cpde-input-section">
                    <label for="cpde_post_id"><?php esc_html_e('Enter Post ID:', 'post date editor'); ?></label>
                    <div class="cpde-input-wrapper">
                        <input type="number" id="cpde_post_id" class="regular-text" min="1" />
                        <button type="button" class="button button-secondary"
                            id="cpde_fetch_post"><?php esc_html_e('Fetch Post', 'post date editor'); ?></button>
                    </div>
                </div>
            </div>

            <!-- Title Search -->
            <div class="cpde-search-panel" id="cpde-title-search">
                <div class="cpde-input-section">
                    <label for="cpde_post_search"><?php esc_html_e('Search by title:', 'post date editor'); ?></label>
                    <input type="text" id="cpde_post_search" class="regular-text"
                        placeholder="<?php esc_attr_e('Type to search posts...', 'post date editor'); ?>" />
                    <div id="cpde_search_results" class="cpde-search-results"></div>
                </div>
            </div>
        </div>

        <!-- Post preview card -->
        <div class="cpde-preview-card" style="display: none;">
            <div class="cpde-preview-header">
                <div class="cpde-preview-image"></div>
                <div class="cpde-preview-title">
                    <h2></h2>
                    <div class="cpde-preview-meta"></div>
                </div>
            </div>
            <div class="cpde-preview-content">
                <div class="cpde-preview-excerpt"></div>
                <div class="cpde-preview-details"></div>
            </div>
            <div class="cpde-preview-footer">
                <a href="#" class="button view-post" target="_blank">View Post</a>
            </div>
        </div>

        <!-- Edit form -->
        <form id="cpde_edit_form" method="post" style="display: none;">
            <?php wp_nonce_field('cpde_ajax_nonce', 'cpde_nonce'); ?>
            <input type="hidden" id="cpde_selected_post_id" name="post_id" value="" />

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="cpde_published_date">Published date</label></th>
                    <td>
                        <input type="datetime-local" id="cpde_published_date" name="cpde_published_date" required />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="cpde_modified_date">Last modified date</label></th>
                    <td>
                        <input type="datetime-local" id="cpde_modified_date" name="cpde_modified_date" required />
                    </td>
                </tr>
            </table>

            <?php submit_button('Save Dates'); ?>
        </form>
    </div>
    <?php
}

/**
 * Clean up on plugin deactivation.
 */
register_deactivation_hook(__FILE__, function () {
    // Remove capabilities if needed in the future
});

/**
 * Enqueue admin scripts and styles
 */
add_action('admin_enqueue_scripts', function ($hook) {
    if ('tools_page_post-date-editor' !== $hook) {
        return;
    }

    // Create directories if they don't exist
    wp_mkdir_p(plugin_dir_path(__FILE__) . 'js');
    wp_mkdir_p(plugin_dir_path(__FILE__) . 'css');

    // Enqueue Select2 from WordPress core
    wp_enqueue_style('select2', includes_url('css/select2.min.css'), array(), '4.0.13');
    wp_enqueue_script('select2', includes_url('js/select2.full.min.js'), array('jquery'), '4.0.13', true);

    // Enqueue our custom scripts and styles
    wp_enqueue_script(
        'cpde-admin',
        plugins_url('js/admin.js', __FILE__),
        array('jquery', 'select2'),
        '1.1.0',
        true
    );

    wp_localize_script('cpde-admin', 'cpdeAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('cpde_ajax_nonce'),
        'strings' => array(
            'saving' => __('Saving...', 'post date editor'),
            'saved' => __('Saved!', 'post date editor'),
            'error' => __('Error occurred. Please try again.', 'post date editor'),
            'fetching' => __('Fetching post...', 'post date editor'),
            'notFound' => __('No post found with that ID.', 'post date editor'),
            'searchPlaceholder' => __('Search by post ID or title...', 'post date editor')
        )
    ));

    wp_enqueue_style(
        'cpde-admin',
        plugins_url('css/admin.css', __FILE__),
        array('select2'),
        '1.1.0'
    );
});

/**
 * AJAX handler for fetching post
 */
add_action('wp_ajax_cpde_fetch_post', function () {
    check_ajax_referer('cpde_ajax_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied');
    }

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    if (!$post_id) {
        wp_send_json_error('Invalid post ID');
    }

    $post = get_post($post_id);
    if (!$post) {
        wp_send_json_error('Post not found');
    }

    wp_send_json_success(array(
        'id' => $post->ID,
        'title' => $post->post_title,
        'status' => get_post_status_object($post->post_status)->label,
        'author' => get_the_author_meta('display_name', $post->post_author),
        'excerpt' => wp_trim_words(strip_shortcodes($post->post_content), 20),
        'permalink' => get_permalink($post->ID),
        'featured_image' => get_the_post_thumbnail_url($post->ID, 'thumbnail'),
        'comment_count' => $post->comment_count,
        'categories' => wp_get_post_categories($post->ID, array('fields' => 'names')),
        'post_type' => get_post_type_object($post->post_type)->labels->singular_name,
        'post_date' => gmdate('Y-m-d\TH:i', strtotime($post->post_date)),
        'post_modified' => gmdate('Y-m-d\TH:i', strtotime($post->post_modified))
    ));
});

/**
 * AJAX handler for saving dates
 */
add_action('wp_ajax_cpde_save_dates', function () {
    check_ajax_referer('cpde_ajax_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied');
    }

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    if (!$post_id) {
        wp_send_json_error('Invalid post ID');
    }

    // Validate inputs exist, unslash, then sanitize
    $published = isset($_POST['published_date']) ? sanitize_text_field(wp_unslash($_POST['published_date'])) : '';
    $modified = isset($_POST['modified_date']) ? sanitize_text_field(wp_unslash($_POST['modified_date'])) : '';

    if (empty($published) || empty($modified)) {
        wp_send_json_error('Missing date values');
    }

    // Convert HTML5 datetime-local to MySQL DATETIME
    $published = str_replace('T', ' ', $published) . ':00';
    $modified = str_replace('T', ' ', $modified) . ':00';

    $result = wp_update_post([
        'ID' => $post_id,
        'post_date' => $published,
        'post_date_gmt' => get_gmt_from_date($published),
        'post_modified' => $modified,
        'post_modified_gmt' => get_gmt_from_date($modified),
    ]);

    if (is_wp_error($result)) {
        wp_send_json_error($result->get_error_message());
    }

    wp_send_json_success();
});

/**
 * AJAX handler for post search
 */
add_action('wp_ajax_cpde_search_posts', function () {
    check_ajax_referer('cpde_ajax_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied');
    }

    // Validate search input exists, unslash, then sanitize
    $search = isset($_GET['search']) ? sanitize_text_field(wp_unslash($_GET['search'])) : '';
    $results = array();

    // Search by title
    $query = new WP_Query(array(
        'post_type' => array('post', 'page'), // Only posts and pages
        'post_status' => 'any',
        'posts_per_page' => 10,
        's' => $search,
        'orderby' => 'title',
        'order' => 'ASC'
    ));

    foreach ($query->posts as $post) {
        // Get a meaningful title
        $title = $post->post_title;
        if (empty(trim($title))) {
            $title = wp_trim_words(strip_shortcodes($post->post_content), 10, '...'); // Use content excerpt if no title
            if (empty(trim($title))) {
                $title = '(No Title)'; // Fallback if no content either
            }
        }

        $results[] = array(
            'id' => $post->ID,
            'title' => $title,
            'post_date' => gmdate('Y-m-d', strtotime($post->post_date)),
            'status' => get_post_status_object($post->post_status)->label,
            'author' => get_the_author_meta('display_name', $post->post_author)
        );
    }

    wp_send_json_success(array('results' => $results));
});
