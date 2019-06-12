<?php
function wustl_formidable_remote_post_json( $request_data = array() ) {
	// Bail if request is not an array or is empty.
	if ( ! is_array( $request_data ) || empty( $request_data ) ) {
		return;
	}

	$photo_signup_service_url = sanitize_url( wp_unslash( get_site_option( 'photo_cp_signup_service_url' ) ) );

	if ( empty( $photo_signup_service_url ) ){
		return;
	}

	$secretPassword = sanitize_text_field( wp_unslash( get_site_option( 'photo_cp_signup_service_key' ) ) );
	$requestingSource = sanitize_text_field( wp_unslash( get_site_option( 'photo_cp_signup_service_source' ) ) );

	$returnDataArray[ 'auth' ] = array(
		'Key'                      => $secretPassword,
		'RequestingSource'         => $requestingSource
	);
	$returnDataArray[ 'form' ] = $request_data;

	$returnData = json_encode( $returnDataArray );

	// Set up our request arguments.
	$post_args = array(
		'body'		=> $returnData,
		'headers' => array(
			'content-type' => 'application/json'
		)
	);

    // $path = dirname( __FILE__ ) . "/form.txt";
    // $myfile = fopen( $path, "w" ) or die( "Unable to open file!" );
    // $json = file_get_contents( $returnData );
    // $obj = json_decode( $json );
    // fwrite( $myfile, print_r( $obj, true ) );
    // fclose( $myfile );

	// Send the POST request using the HTTP API.
    wp_remote_post( $photo_signup_service_url, $post_args ); 
    // WP custom function with given destination URL and the array filled with the content we want to pass
}






//Admin Form
function photo_cp_signup_admin_form() {

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to manage options for this site.' ) );
	}

	$settings_saved = false;

	if ( ! empty( $_POST ) && check_admin_referer( 'save_photo_cp_signup_settings', 'photo_cp_signup_nonce' ) ) {
		// Update Service Settings
		$service_url = ( isset( $_POST[ 'photo_cp_signup_service_url' ] ) ) ? esc_url_raw( wp_unslash( $_POST[ 'photo_cp_signup_service_url' ] ) ): '';
		$service_key = ( isset( $_POST[ 'photo_cp_signup_service_key' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'photo_cp_signup_service_key' ] ) ) : '';
		$service_source = ( isset( $_POST[ 'photo_cp_signup_service_source' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'photo_cp_signup_service_source' ] ) ) : '';

		update_site_option( 'photo_cp_signup_service_url', $service_url );
		update_site_option( 'photo_cp_signup_service_key', $service_key );
		update_site_option( 'photo_cp_signup_service_source', $service_source );
		
		$settings_saved = true;
	}

	?>
	<div class="wrap">
		<h1>Remote Json Settings</h1>

		<form method="post" action="options-general.php?page=photo-form-plugin.php">
			<input type="hidden" name="action" value="update_photo_cp_signup" />
			<?php wp_nonce_field( 'save_photo_cp_signup_settings', 'photo_cp_signup_nonce' ) ?>

			<h2> Service Settings </h2>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">WashU Internal Service URL</th>
					<td><input type="text" name="photo_cp_signup_service_url" value="<?php echo esc_attr( wp_unslash( get_site_option( 'photo_cp_signup_service_url' ) ) ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row">Service Key</th>
					<td><input type="text" name="photo_cp_signup_service_key" value="<?php echo esc_attr( wp_unslash( get_site_option( 'photo_cp_signup_service_key' ) ) ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row">Service Source</th>
					<td><input type="text" name="photo_cp_signup_service_source" value="<?php echo esc_attr( wp_unslash( get_site_option( 'photo_cp_signup_service_source' ) ) ); ?>" /></td>
				</tr>
			</table>

			<?php submit_button(); ?>
		</form>
	</div>


<?php
}

//Admin Menu for form
function photo_cp_signup_add_admin_menu() {
	add_options_page(
		'User Alert Remote Json',
		'User Alert Remote Json',
		'manage_options',
		'photo-form-plugin.php',
		'photo_cp_signup_admin_form'
	);
}
add_action( 'admin_menu', 'photo_cp_signup_add_admin_menu' );




// Enqueue scripts
function wustl_cp_signup_add_plugin_scripts() {
        wp_enqueue_script( 'my_amazing_script', plugins_url( 'my_amazing_script.js', __FILE__ ), 'jquery', "1.0.0", true );
}
add_action( 'wp_enqueue_scripts', 'wustl_cp_signup_add_plugin_scripts' );




function washuFormidableRemotePost(){
	if ( ! $_POST ) {
		return;
	} else {
		$request_data = $_POST;
	}
	// Once we've varified we have a valid request, we send it and die.
	wustl_formidable_remote_post_json( $request_data );
	die();
}
add_action( 'wp_ajax_nopriv_washuFormidableRemotePost', 'washuFormidableRemotePost' );
add_action( 'wp_ajax_washuFormidableRemotePost', 'washuFormidableRemotePost' );






// function formIdCheck() {
//     $my_file = 'plzwork.txt';
//     $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
//     file_put_contents($my_file, "plz plz work gosh");
//  	$formIDsArray = explode( ",", str_replace( ' ', '', get_site_option( 'form_ids' ) ) );
// 	//wp_localize_script( 'user_script', 'formIDsArray');
//     wp_enqueue_script('pw-script', plugin_dir_url( __FILE__ ) . 'user_script.js');
// }
// add_action( 'wp_enqueue_scripts', 'formIdCheck' );

?>