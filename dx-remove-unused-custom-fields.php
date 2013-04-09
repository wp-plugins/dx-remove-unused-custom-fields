<?php
/**
 * Plugin Name: DX Remove Unused Custom Fields
 * Description: Delete custom fields from postmeta table for posts that no longer exist
 * Author: nofearinc
 * Author URI: http://devwp.eu/
 * Version: 0.1
 * License: GPLv2 or later
 *
 */
/*
 * Copyright (C) 2013 Mario Peshev

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*
*/


/**
 * Main class for removing custom fields
 * 
 * @author nofearinc
 *
 */
class DX_Remove_Unused_Custom_Fields {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
	}
	
	public function add_plugin_page() {
		add_options_page( 'Remove Unused CF','Remove Unused CF', 'manage_options', 'remove_unused_cf', array( $this, 'settings_page' ) );
	}
	
	/**
	 * Settings -> Remove unused custom fields
	 */
	public function settings_page() {
		// Get all post IDs in postmeta that don't exist in posts
		global $wpdb;
		
		$message = '';

		$sql = "SELECT DISTINCT post_id FROM $wpdb->postmeta WHERE post_id NOT IN "
				. "( SELECT DISTINCT id FROM $wpdb->posts )";

		// autocached by WP
		$post_ids = $wpdb->get_col( $sql);
				
		// Delete has been fired?
		if( isset( $_POST['delete'] ) ) {
			if( isset( $_POST['delete-cfs-nonce'] ) && wp_verify_nonce( $_POST['delete-cfs-nonce'], 'delete-cfs' ) ) {
				// Action!
				
				if( empty( $post_ids ) || ! is_array( $post_ids ) ) {
					$message = "<p>No posts to delete here.</p>";
				} else {
					$posts_in = implode(',', array_values( $post_ids ));

					// this has been defined manually above, no reason to expected SQL injections
					$delete_query = "DELETE FROM $wpdb->postmeta WHERE post_id IN({$posts_in})";
					$wpdb->query( $delete_query );
						
					$message = "<p>Postmeta for $posts_in has been removed.</p>";
					$post_ids = array();
				}
			}
		}
		include_once 'remove-unused-cf-admin-view.php';
	}
}

new DX_Remove_Unused_Custom_Fields();