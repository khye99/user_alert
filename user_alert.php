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
Author: Karen Ye, Daniel Kwon
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
/*
Copy this to debug:
$my_file = 'testing_again.txt';
$handle = fopen($my_file, 'w') or die('Cannot open file: ' .$my_file);
file_put_contents($my_file, "debug");
*/
defined ('ABSPATH') or die('Access file denied.');
class karenPlugin {
    function __construct() {
        
    }
    function activate() {
        if (! wp_next_scheduled ( 'my_daily' )) {
            wp_schedule_event(time(), 'daily', 'my_daily');
        }
        flush_rewrite_rules();
    }
    function deactivate() {
        wp_clear_scheduled_hook('my_daily');
        flush_rewrite_rules();
    }
    
}
if (class_exists('karenPlugin')) {
    $pluginObj = new karenPlugin();
}


function user_registeration( $user_id ) {
    $user_info = []; 
    $user = new WP_User($user_id); // getting user from database by #ID
    $email_address = $user->user_email;
    $user_name = $user->user_nicename;
    $full_name = $user->display_name;
    $first_name = get_user_meta( $user_id, 'first_name', true );
    $last_name = get_user_meta( $user_id, 'last_name', true );
    $perm = $user->wp_capabilities;
    array_push($user_info, $email_address, $user_name, $full_name, $first_name, $last_name, $perm);
    wustl_remote_post_json( $user_info);
}

add_action( 'user_register', 'user_registeration', 10, 1 );

function profile_updated( $user_id , $old_user_data ){
    $changedUser = new WP_User($user_id);
    $changedRole = $changedUser->get_role_caps();
    $prevRole = $old_user_data->get_role_caps();
    if ($changedRole != $prevRole){
        $user_info = []; 
        $user = new WP_User($user_id); // getting user from database by #ID
        $email_address = $user->user_email;
        $user_name = $user->user_nicename;
        $full_name = $user->display_name;
        $first_name = get_user_meta( $user_id, 'first_name', true );
        $last_name = get_user_meta( $user_id, 'last_name', true );
        $perm = $user->wp_capabilities;
        array_push($user_info, $email_address, $user_name, $full_name, $first_name, $last_name, $perm);
        wustl_remote_post_json( $user_info);
    }
}

add_action( 'profile_update', 'profile_updated', 10, 2 );

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
        array_push($userArr, $perm, $blogID);
        wustl_remote_post_json_users($userArr);
    }
} 
// activation
register_activation_hook(__FILE__, array($pluginObj, 'activate'));// this array will access the funciton in the class

// deactivation
register_deactivation_hook(__FILE__, array($pluginObj, 'deactivate'));
add_action( 'my_daily', 'my_new_event');
function my_new_event(){
    $timestamp = time();
    $my_file = $timestamp + '.txt';
    $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
    file_put_contents($my_file, get_current_blog_id());
    get_userInfo();
}
?>