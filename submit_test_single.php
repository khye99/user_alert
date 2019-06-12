<?php
$path = dirname( __FILE__ ) . "/form.txt";
$myfile = fopen( $path, "w" ) or die( "Unable to open file!" );
$json = file_get_contents( 'php://input' );
$obj = json_decode( $json );
fwrite( $myfile, print_r( $obj, true ) );
fclose( $myfile );
?>