<?php    
    add_action( 'my_new_event', 'my_new_event_t');
    function my_new_event_t(){
        $my_file = 'plzwork.txt';
        $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
        file_put_contents($my_file, time());
    }

    wp_schedule_single_event( time() + 100, 'my_new_event' );
?>
