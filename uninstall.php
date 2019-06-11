<?php
/** 
 * 
 * Trigger this file on Plugin uninstall
 * @package karen-plugin
*/

if( ! defined('WP_UNISTALL_PLUGIN') ) {
    die;
}

// Clear Database stored data
// delete the books we created, or sql query

// post-type is the book identital to name for register_post_type
// -1 is basically getting all of the posts, results of the query, it's a custom wordpress query
// In PHP, -1 means getting everything

// $books = get_posts( array( 'post_type' => 'book', 'numberposts' => -1) );

// foreach( $books as $book ) {
//     // need to access the ID of the book to delete it
//     // second argument when false: it's not force delete, for example if it's already in trash leave it
//     // second argument when true: it is force delete, just delete absolutely everything
//     wp_delete_post($book->ID, true);
// }

// Access the datacase via SQL. DNAGER! be careful
global $wpdb; // this is the accessing query method
// wp_posts : the table where WP stores all of the stuff
$wpdb->query("DELETE FROM wp_posts WHERE post_type = 'book' ");

// wp_postmeta : the database table with all the metaboxes 
$wpdb->query("DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)");
// we are selecting all the id from the wb_posts that are NOT 'book' anymore since it comes after the last line

$wpdb->query("DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)");
