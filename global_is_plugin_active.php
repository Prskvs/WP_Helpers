<?php
/*
 * Check whether the plugin is active by checking the active_plugins list.
 */
function global_is_plugin_active( $plugin ) {
    return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || global_is_plugin_active_for_network( $plugin );
}

/*
 * Check whether the plugin is active for the entire network
 */
function global_is_plugin_active_for_network( $plugin ) {
    if ( !is_multisite() )
        return false;

    $plugins = get_site_option( 'active_sitewide_plugins' );
    if ( isset($plugins[$plugin]) )
        return true;

    return false;
}
