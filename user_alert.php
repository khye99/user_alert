<?php
require 'user_settings.php';
/**
 * 
 * @package user_alert
 */
/*
Plugin Name: User Alert Plugin
Plugin URI: http://karen-plugin.com
Description: This is my first attempt on writing a custom plugin
Version: 1.0.0
Author: Karen Ye
Author URI: http://ikarenye.com
License: GPLv2 or later
Text Domain: karen-plugin
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

Copyright 2005-2019 Automattic, Inc.
*/ 

// You search for a built in method in wordpress, if those things are not defined or be reached, 
// something is catchy and fishy yo
// The following options all do the same thing, to secure the plugin

defined ('ABSPATH') or die('Access file denied.');

class karenPlugin {
    function __construct() {
        add_action('init', array($this, 'custom_post_type'));
    }

    function activate() {
        $this->custom_post_type();
        if (! wp_next_scheduled ( 'my_hourly' )) {
            wp_schedule_event(time(), 'hourly', 'my_hourly');
        }
        flush_rewrite_rules();
    }

    function deactivate() {
        wp_clear_scheduled_hook('my_hourly');
        flush_rewrite_rules();
    }

    function custom_post_type() {
        register_post_type('book', ['public' => true, 'label' => 'Books']);
    }

    
}



if (class_exists('karenPlugin')) {
    $pluginObj = new karenPlugin();
}

function general_admin_notice(){
    global $pagenow;
    if ( $pagenow == 'index.php' ) {
         echo '<div class="notice notice-success is-dismissible">
             <p>This notice appears on the dashboards page. -Karen</p>
         </div>';
    }
}
add_action('admin_notices', 'general_admin_notice');

function user_registeration( $user_id ) {
    $my_file = 'exportedfile.txt';
    $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates fil

    $user_info = []; 
    $user = new WP_User($user_id); // getting user from database by #ID

    $email_address = $user->user_email;
    $user_name = $user->user_nicename;
    $full_name = $user->display_name;
    $first_name = get_user_meta( $user_id, 'first_name', true );
    $last_name = get_user_meta( $user_id, 'last_name', true );
    $perm = $user->wp_capabilities;

    //$all = get_user_meta( $user_id ); // to see all the information provided
    //$returnData = json_encode($perm);
	//file_put_contents($my_file, $returnData);
    array_push($user_info, $email_address, $user_name, $full_name, $first_name, $last_name, $perm);

    wustl_remote_post_json( $user_info);
}
add_action( 'user_register', 'user_registeration', 10, 1 );

function get_userInfo() {
    $result = count_users(); // total # of users in table
    $blogID = get_current_blog_id();
 
    $blogusers = get_users( 'blog_id={$blogID}&orderby=nicename' );
    // Array of WP_User objects.

	file_put_contents($my_file, $returnData);
    foreach ( $blogusers as $user ) {
        $userArr = $user->to_array();

        $user = new WP_User($userArr['ID']);
        $perm = $user->wp_capabilities;
        array_push($userArr, $perm);

        wustl_remote_post_json_users($userArr);
    }
} 

// activation
register_activation_hook(__FILE__, array($pluginObj, 'activate'));// this array will access the funciton in the class
        /* identical to this:
            add_action('init', 'function_name');
        */

// deactivation
register_deactivation_hook(__FILE__, array($pluginObj, 'deactivate'));


add_action( 'my_hourly', 'my_new_event');
function my_new_event(){
    $timestamp = time();
    $my_file = $timestamp + '.txt';
    $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
    file_put_contents($my_file, 'working crons');
    get_userInfo();
}
?>
