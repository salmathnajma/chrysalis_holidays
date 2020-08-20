<?php
/*
Plugin Name: CI Comment Rating
Description: Adds a star rating system to WordPress comments
Version: 1.0.0
Author: The CSSIgniter Team
Author URI: https://cssigniter.com/
*/
//Enqueue the plugin's styles.
add_action( 'wp_enqueue_scripts', 'ci_comment_rating_styles' );
function ci_comment_rating_styles() {

	wp_register_style( 'ci-comment-rating-styles', plugins_url( '/', __FILE__ ) . 'assets/style.css' );

	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'ci-comment-rating-styles' );
}

//-----------------------Create the rating interface----------------------------------------------------------

add_action( 'comment_form_logged_in_after', 'ci_comment_rating_rating_field' );
add_action( 'comment_form_after_fields', 'ci_comment_rating_rating_field' );
function ci_comment_rating_rating_field () {
	?>
	<label for="rating">Rating<span class="required">*</span></label>
	<fieldset class="comments-rating">
		<span class="rating-container">
			<?php for ( $i = 5; $i >= 1; $i-- ) :
				echo  $i ;  
				?>
				
				<input type="radio" id="rating-<?php echo esc_attr( $i ); ?>" name="rating" value="<?php echo esc_attr( $i ); ?>" /><label for="rating-<?php echo ( $i ); ?>"><?php echo esc_html( $i ); ?></label>
			<?php endfor; ?>
			<input type="radio" id="rating-0" class="star-cb-clear" name="rating" value="0" /><label for="rating-0">0</label>
		</span>
	</fieldset>
	<?php
}
//Save the rating submitted by the user.
add_action( 'comment_post', 'ci_comment_rating_save_comment_rating' );
function ci_comment_rating_save_comment_rating( $comment_id ) {
	if ( ( isset( $_POST['rating'] ) ) && ( '' !== $_POST['rating'] ) )
	$rating = intval( $_POST['rating'] );
	add_comment_meta( $comment_id, 'rating', $rating );
}

//Make the rating required.
add_filter( 'preprocess_comment', 'ci_comment_rating_require_rating' );
function ci_comment_rating_require_rating( $commentdata ) {
	if ( ! is_admin() && ( ! isset( $_POST['rating'] ) || 0 === intval( $_POST['rating'] ) ) )
	wp_die( __( 'Error: You did not add a rating. Hit the Back button on your Web browser and resubmit your comment with a rating.' ) );
	return $commentdata;
}

//Display the rating on a submitted comment.
add_filter( 'comment_text', 'ci_comment_rating_display_rating');
function ci_comment_rating_display_rating( $comment_text ){

	if ( $rating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {
		$stars = '<p class="stars">';
		for ( $i = 1; $i <= $rating; $i++ ) {
			$stars .= '<span class="dashicons dashicons-star-filled"></span>';
		}
		if ($rating<5) {
			for ( $i = 1; $i <=(5 - $rating); $i++ ) {
				$stars .= '<span class="dashicons dashicons-star-unfilled"></span> ';
			}
		}
		$stars .= '</p>';
		$comment_text = $comment_text . $stars;
		return $comment_text;
	} else {
		return $comment_text;
	}
}

?>