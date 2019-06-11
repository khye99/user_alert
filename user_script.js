( function( $ ){

	jQuery( document ).ready( function( $ ){
		$( document ).on( 'frmFormComplete', function( event, form, response ) {
			event.preventDefault();

			var thisform = $(form.form_val).val();
			if ( $.inArray( thisform, formIDsArray ) ) {
				var formArray = $( form ).serializeArray();

				function objectifyForm( formArray ) { //serialize data function
					var returnArray = {};
					var fieldsArray = {};
					for ( var i = 0; i < formArray.length; i++ ) {
						// field name attributes
						var field_name = formArray[i]['name'];
						var field_name_length=field_name.length;
						var length_minus_one=field_name_length-1;
						// redefine name attribute to be = field ID
						var field_id = ($(":input[name=" + field_name.replace( /(:|\.|\[|\]|,|=|@)/g, "\\$1" ) + "]", form).attr('id') );

						// build returnArray key:value pair
						if ( field_id ) { // field has a custom ID
							// deal with multi-value fields
							// (i.e. checkboxes, multi-select dropdowns)
							// as their own array
							if ( ! field_name.substring(length_minus_one, field_name_length) == '[]' ) {
								if ( ! ( field_id in fieldsArray ) ) {
									fieldsArray[field_id] = [];
								}
								fieldsArray[field_id].push( formArray[i]['value'] );
							} else {
								fieldsArray[field_id] = formArray[i]['value'];
							}
						} else { // nope, field only has a 'name'
							if ( ! field_name.substring(0, 9) == 'item_meta' ) {  // don't bother including item_meta[] in our array
								returnArray[field_name] = formArray[i]['value'];
							}
						}
					}
					// contain all specially-named form fields inside its own array
					returnArray['formfields'] = fieldsArray;
					returnArray['action'] = 'washuFormidableRemotePost';
					return returnArray;
				} // end function

				var $formObj = objectifyForm( formArray );

				$.ajax( {
					type: "POST",
					url: ajaxurl,
					data: $formObj,
					success:function( data ) {
						console.log( data );
					},
					error: function( errorThrown ) {
						console.log( errorThrown );
					}
				} );
			} // end if in array
		} );
	} );

} )( jQuery );