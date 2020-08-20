<?php
/**
 * class for filtering default theme codes
 *
 * @package calypso
 */

class Ca_Filters{
    public function __construct() {
        add_filter( 'excerpt_length', array( $this, 'change_excerpt_length' ), 999 );
        add_filter( 'excerpt_more', array( $this, 'change_excerpt_more' ) );
        add_filter( 'comment_form_fields', array( $this, 'comment_message' ) );
        add_filter( 'comment_form_default_fields', array( $this, 'comment_form_args' ) );
    }

    public function change_excerpt_length() {
		return get_theme_mod( 'ca_blog_excerpt_length', 40 );
    }

    public function change_excerpt_more( $more ) {
		global $post;

		$custom_more_tag = '<a class="moretag" href="' . esc_url( get_permalink( $post->ID ) ) . '"> ' . esc_html__( 'Read more', 'calypso' ) . '</a>';

		return $custom_more_tag;
    }
    /**
	 * Move comment field above user details.
	 *
	 * @param array $fields comment form fields.
	 */
	public function comment_message( $fields ) {

		if ( array_key_exists( 'comment', $fields ) ) {
			$comment_field = $fields['comment'];
			unset( $fields['comment'] );
			$fields['comment'] = $comment_field;
		}

		if ( array_key_exists( 'cookies', $fields ) ) {
			$cookie_field = $fields['cookies'];
			unset( $fields['cookies'] );
			$fields['cookies'] = $cookie_field;
		}

		return $fields;
    }
    
    /**
	 * Add markup to comment form fields.
	 *
	 * @param array $fields Comment form fields.
	 *
	*/
	public function comment_form_args( $fields ) {
		$req      = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );

		$fields['author'] = '<div class="row"> <div class="col-md-4"> <div class="form-group label-floating is-empty"> <label class="control-label">' . esc_html__( 'Name', 'calypso' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label><input id="author" name="author" class="form-control" type="text"' . $aria_req . ' /> <span class="ca-input"></span> </div> </div>';
		$fields['email']  = '<div class="col-md-4"> <div class="form-group label-floating is-empty"> <label class="control-label">' . esc_html__( 'Email', 'calypso' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label><input id="email" name="email" class="form-control" type="email"' . $aria_req . ' /> <span class="ca-input"></span> </div> </div>';
		$fields['url']    = '<div class="col-md-4"> <div class="form-group label-floating is-empty"> <label class="control-label">' . esc_html__( 'Website', 'calypso' ) . '</label><input id="url" name="url" class="form-control" type="url"' . $aria_req . ' /> <span class="ca-input"></span> </div> </div> </div>';
		return $fields;
	}
    
}
new Ca_Filters();