<?php
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
        flush_rewrite_rules();

    }
    function deactivate() {
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
    $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file



    $user = new WP_User($user_id); // getting user from database by #ID
    $myObj->email_address = $user->user_email; 
    $myObj->nickname = $user->user_nicename;
    $myObj->dispalyName = $user->display_name;



    $myObj->first_name = get_user_meta( $user_id, 'first_name', true );
    $myObj->last_name = get_user_meta( $user_id, 'last_name', true );
    $myJSON = json_encode($myObj);
    file_put_contents($my_file, $myJSON);
}
add_action( 'user_register', 'user_registeration', 10, 1 );


// activation
register_activation_hook(__FILE__, array($pluginObj, 'activate'));// this array will access the funciton in the class
        /* identical to this:
            add_action('init', 'function_name');
        */

// deactivation
register_deactivation_hook(__FILE__, array($pluginObj, 'deactivate'));