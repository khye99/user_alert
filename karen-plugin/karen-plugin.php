<?php
/**
 * 
 * @package karen-plugin
 */
/*
Plugin Name: Karen Plugin
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

//wp_new_user_notification( int $user_id, null $deprecated = null, string $notify = '' );

// function my_function($user_id){
//     //do your stuff
//     echo "I really wish this would print somewhere";
//     echo '<div class="notice notice-warning is-dismissible">
//              <p>Please I beg you.</p>
//          </div>';
// }
// add_action('user_register','my_function');



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

    // $myObj->first_name = $_POST['first_name'];
    // $myObj->last_name = $_POST['last_name'];
    // $myObj->username = $_POST['user_login'];
    // $myObj->password = $_POST['pwd'];
    // $myObj->email_address = $_POST['email'];
    // $myJSON = json_encode($myObj);
    // file_put_contents($my_file, $myJSON);

    $user = new WP_User($user_id); // getting user from database by #ID
    $myObj->email_address = $user->user_email; 
    $myObj->nickname = $user->user_nicename;
    $myObj->dispalyName = $user->display_name;

    // $sql = " SELECT user_id,meta_key,meta_value
    // FROM {$wpdb->usermeta} 
    // WHERE ({$wpdb->usermeta}.meta_key = 'first_name' OR {$wpdb->usermeta}.meta_key = 'last_name')";
    // $ansatte = $wpdb->get_results($sql);
    // var_dump($sql);
    // $users = array();
    // foreach ($ansatte as $a) {
    //   $users[$a->user_id][$a->meta_key] = $a->meta_value;
    // }
    // var_dump($users);

    // foreach ($users as $u) {
    //     $foo =  $u['first_name'].' '.$u['last_name'];
    //     file_put_contents($my_file, $foo);
    // }

    $myObj->first_name = get_user_meta( $user_id, 'first_name', true );
    $myObj->last_name = get_user_meta( $user_id, 'last_name', true );
    $myJSON = json_encode($myObj);
    file_put_contents($my_file, $myJSON);
}
add_action( 'user_register', 'user_registeration', 10, 1 );

// function wp_mail( $to, $subject, $message, $headers = '' ) {
//     if( $headers == '' ) {
//       $headers = "MIME-Version: 1.0\n" .
//         "From: " . get_settings('admin_email') . "\n" . 
//         "Content-Type: text/plain; charset=\"" . get_settings('blog_charset') . "\"\n";
//     }
  
//     return @mail( $to, $subject, $message, $headers );
// }

// I guess echoing just doesn't work
// outside stand-alone function
// function customFunction($arg) {
//     echo $arg;
// }
// customFunction("Echo this out bitch");

// activation
register_activation_hook(__FILE__, array($pluginObj, 'activate'));// this array will access the funciton in the class
        /* identical to this:
            add_action('init', 'function_name');
        */

// deactivation
register_deactivation_hook(__FILE__, array($pluginObj, 'deactivate'));
