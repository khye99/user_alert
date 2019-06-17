# user_alert
Custom plugin for notifying CRM of creation of users

submit_test_single.php:

```xml
<?php
$path = dirname( __FILE__ ) . "/form.txt";
$myfile = fopen( $path, "w" ) or die( "Unable to open file!" );
$json = file_get_contents( 'php://input' );
$obj = json_decode( $json );
fwrite( $myfile, print_r( $obj, true ) );
fclose( $myfile );
?>
```

submit_test_users.php:
```xml
<?php
$path = dirname( __FILE__ ) . rand(1,100) . '.txt';
$myfile = fopen( $path, "w" ) or die( "Unable to open file!" );
$json = file_get_contents( 'php://input' );
$obj = json_decode( $json );
fwrite( $myfile, print_r( $obj, true ) );
fclose( $myfile );
?>
```
These two files exist for testing purposes of the JSON data transmission.
