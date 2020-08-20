<?php
/**
 * main functions.
 *
 * @package calypso
 */

function ca_sanitize_checkbox( $value ) {
	return isset( $value ) && true === (bool) $value;
}

//script input sanitization function
function ca_sanitize_js_code($input){
	return base64_encode($input);
}
 //output escape function    
function ca_escape_js_output($input){
	return esc_textarea( base64_decode($input) );
}

function ca_text_sanitization( $input ) {
	if ( strpos( $input, ',' ) !== false) {
		$input = explode( ',', $input );
	}
	if( is_array( $input ) ) {
		foreach ( $input as $key => $value ) {
			$input[$key] = sanitize_text_field( $value );
		}
		$input = implode( ',', $input );
	}
	else {
		$input = sanitize_text_field( $input );
	}
	return $input;
}

function ca_google_font_sanitization( $input ) {
	$val =  json_decode( $input, true );
	if( is_array( $val ) ) {
		foreach ( $val as $key => $value ) {
			$val[$key] = sanitize_text_field( $value );
		}
		$input = json_encode( $val );
	}
	else {
		$input = json_encode( sanitize_text_field( $val ) );
	}
	return $input;
}

function ca_sanitize_integer( $input ) {
	return (int) $input;
}

/**
 * Alpha Color (Hex & RGBa) sanitization
 *
 * @param  string	Input to be sanitized
 * @return string	Sanitized input
 */

function ca_hex_rgba_sanitization( $input, $setting ) {

	
	if ( empty( $input ) || is_array( $input ) ) {
		return $setting->default;
	}

	if ( false === strpos( $input, 'rgba' ) ) {
		// If string doesn't start with 'rgba' then santize as hex color
		$input = sanitize_hex_color( $input );
	} else {
		// Sanitize as RGBa color
		$input = str_replace( ' ', '', $input );
		sscanf( $input, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
		$input = 'rgba(' . ca_in_range( $red, 0, 255 ) . ',' . ca_in_range( $green, 0, 255 ) . ',' . ca_in_range( $blue, 0, 255 ) . ',' . ca_in_range( $alpha, 0, 1 ) . ')';
	}
	return $input;
}

function ca_in_range( $input, $min, $max ){
	if ( $input < $min ) {
		$input = $min;
	}
	if ( $input > $max ) {
		$input = $max;
	}
	return $input;
}

function ca_is_json( $string ) {
	return is_string( $string ) && is_array( json_decode( $string, true ) ) ? true : false;
}

function ca_radio_sanitization( $input, $setting ) {
	//get the list of possible radio box or select options
	$choices = $setting->manager->get_control( $setting->id )->choices;

	if ( array_key_exists( $input, $choices ) ) {
		return $input;
	} else {
		return $setting->default;
	}
}

function ca_sanitize_hex_color( $color ) {
	

	if ( '' === $color ) {
		return '';
	}

	if ( false === strpos( $color, 'rgb' )  ) {
		// If string doesn't start with 'rgba' then santize as hex color
		return sanitize_hex_color( $color );
	} else {
		// Sanitize as RGBa color
		$color = str_replace( ' ', '', $color );
		sscanf( $color, 'rgb(%d,%d,%d)', $red, $green, $blue );

		$color = sprintf("#%02x%02x%02x", $red, $green, $blue);

		return $color;
	}

}

/**
 * Sanitize repeater control.
 *
 * @param object $value Control output.
 *
 * @return object
 */
function ca_repeater_sanitize( $value ) {
	$value_decoded = json_decode( $value, true );

	if ( ! empty( $value_decoded ) ) {
		foreach ( $value_decoded as $boxk => $box ) {
			foreach ( $box as $key => $value ) {

				$value_decoded[ $boxk ][ $key ] = wp_kses_post( force_balance_tags( $value ) );

			}
		}

		return json_encode( $value_decoded );
	}

	return $value;
}
function ca_sanitize_image( $input, $setting ) {
	return esc_url_raw( ca_validate_image( $input, $setting->default ) );
}


function ca_validate_image( $input, $default = '' ) {
	// Array of valid image file types
	// The array includes image mime types
	// that are included in wp_get_mime_types()
	$mimes = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif'          => 'image/gif',
		'png'          => 'image/png',
		'bmp'          => 'image/bmp',
		'tif|tiff'     => 'image/tiff',
		'ico'          => 'image/x-icon',
		'svg'          => 'image/svg'
	);
	// Return an array with file extension
	// and mime_type
	$file = wp_check_filetype( $input, $mimes );
	// If $input has a valid mime_type,
	// return it; otherwise, return
	// the default.
	return ( $file['ext'] ? $input : $default );
}

?>