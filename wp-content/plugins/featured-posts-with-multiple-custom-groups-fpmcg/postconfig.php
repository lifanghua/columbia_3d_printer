<?php
/*Plugin Name: Featured Posts with Multiple Custom Groups (FPMCG)
Plugin URI: http://www.wafysol.com
Description: This plugin allows you to create unlimited custom groups of featured posts. Admin can create unlimited groups and assign the posts under one or multiple groups. The widget section allows the admin to show these groups of featured posts. The widget section is hugely customizable to enter HTML and CSS to give the featured posts blocks the desired look. <strong>Please refer to the help section in plugin settings page for more details.</strong>
Version: 1.1
Author: Sumit Surai
Author URI: http://www.sumitsurai.com
License: GPLv2 or later
*/

/*
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
*/

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
if ( is_admin() )
	require_once dirname( __FILE__ ) . '/admin.php';

include_once dirname( __FILE__ ) . '/widget.php';
include_once dirname( __FILE__ ) . '/class-meta-box.php';

$table_name = $wpdb->prefix . "post_type";

add_action( 'init', 'custom_init' );

function custom_init() {
    global $wp_roles;
		
    $labels = array(
        'name' => _x('Custom Post Type', 'post type general name'),
        'singular_name' => _x('Custom Post Type', 'post type singular name')

      );
      
      $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        'capability_type' => 'post',
        'show_in_menu' => 'false'
      );
      register_post_type('custom_post',$args);
}


