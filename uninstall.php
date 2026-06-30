<?php
/**
 * Runs automatically when the plugin is deleted via the WordPress admin.
 * Removes sync relationship data left behind on all subsites.
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

$site_ids = get_sites(array('fields' => 'ids', 'number' => 0));

foreach ($site_ids as $site_id) {
    switch_to_blog($site_id);

    $wpdb->delete( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->postmeta,
        array('meta_key' => '_tk_master_product_id'), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        array('%s')
    );

    restore_current_blog();
}
