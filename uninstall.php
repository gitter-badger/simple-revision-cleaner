<?php
/**
 * Fired when plugin is uninstalled
 *
 * @since 0.1
 * @author Daniil Zhitnitskii
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

//get all the sites for multisite, if not a multisite, set blog id to 1
if (is_multisite()) {
	$blogs_ids = get_sites();
} else {
	$blogs_ids = [1];
}

foreach( $blogs_ids as $b ) {

	//if multisite, each iteration changes site
	if ( is_multisite() ) {
		switch_to_blog( $b->blog_id );
	}

	//get all the options from database
	$all_options    = wp_load_alloptions();
	$plugin_options = [];

	//extract plugin options from all options
	foreach ( $all_options as $name => $value ) {

		if ( stristr( $name, 'fr_net_' ) || stristr( $name, 'fr_freeze_' ) || stristr( $name, 'fr_time_' )) {

			$plugin_options[ $name ] = $value;
		}
	}


	//delete plugin options
	foreach ( $plugin_options as $key => $value ) {
		if ( get_option( $key ) || get_option( $key, 'nonex' ) !== 'nonex' ) {
			delete_option( $key );
		}
	}
}