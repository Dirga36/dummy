<?php
/**
 * Plugin Name: Multisite Helper
 * Description: A lightweight plugin to display subsite info in the admin footer for multisite (subdomain) setups.
 * Version: 1.0.0
 * Author: Dirgantara
 * Network: true
**/

// Security check
if (!defined('ABSPATH')) exit;

// Add custom bulk action to Sites list table
add_filter('bulk_actions-sites-network', function($bulk_actions) {
    $bulk_actions['deactivate_sites'] = __('Deactivate Sites', 'multisite-helper');
    return $bulk_actions;
});

// Handle the custom bulk action
add_filter('handle_bulk_actions-sites-network', function($redirect_to, $doaction, $site_ids) {
    if ($doaction !== 'deactivate_sites') {
        return $redirect_to;
    }

    $count = 0;
    foreach ((array) $site_ids as $site_id) {
        if (get_site($site_id) && !is_main_site($site_id)) {
            update_blog_status($site_id, 'public', 0); // Optional: set public to 0
            update_blog_status($site_id, 'archived', 0); // Optional: unarchive
            update_blog_status($site_id, 'deleted', 0); // Optional: undelete
            update_blog_status($site_id, 'spam', 0); // Optional: unspam
            update_blog_status($site_id, 'mature', 0); // Optional: unmature
            update_blog_status($site_id, 'deleted', 1); // Deactivate (mark as deleted)
            $count++;
        }
    }
    $redirect_to = add_query_arg('bulk_deactivated_sites', $count, $redirect_to);
    return $redirect_to;
}, 10, 3);

// Show admin notice after bulk action
add_action('network_admin_notices', function() {
    if (!empty($_REQUEST['bulk_deactivated_sites'])) {
        $count = intval($_REQUEST['bulk_deactivated_sites']);
        printf(
            '<div id="message" class="updated notice is-dismissible"><p>%s</p></div>',
            esc_html(sprintf(_n('%s site deactivated.', '%s sites deactivated.', $count, 'multisite-helper'), $count))
        );
    }
});
