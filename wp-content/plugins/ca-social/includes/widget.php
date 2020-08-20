<?php
/**
 * Register the widget on `widgets_init`.
 * Registers this widget.
 */
add_action( 'widgets_init', 'load_widget_socialbar' );

function load_widget_socialbar() {
	register_widget( 'Widget_Socialbar' );
}

class Widget_Socialbar extends WP_Widget {

	/*
	 * Constructor 
	 * The constructor. Sets up the widget.
	 */
	
	function __construct() {
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_socialbar', 'description' => __( 'This widget is the social bar  that classically goes into the sidebar. ', 'cappathemes' ) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'widget-socialbar' );

		/* Create the widget. */
		parent::__construct( 'widget-socialbar', __('Socialbar', 'cappathemes' ), $widget_ops, $control_ops );
		
	}

    function widget( $args, $instance ) {
   
		extract( $args );

		/* Our variables from the widget settings. */
        $title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base );
        $use_image = $instance['use_image'];
		$fb_url = $instance['fb_url'];
		$twt_url = $instance['twt_url'];
		$gplus_url = $instance['gplus_url'];
		$lnk_url = $instance['lnk_url'];
		$utb_url = $instance['utb_url'];
		$inst_url = $instance['inst_url'];
		$description = $instance['description'];
		
		/* Setup tab pieces to be loaded in below. */
					
		echo $before_widget;
		
		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
		
		?>
 		<div class="social-media">
         <?php if($use_image){ ?>
			<?php if($fb_url){ ?>
				<a class='fb' href="<?php echo $fb_url; ?>" target="_blank">facebook</a>
			<?php } ?>
				<?php if($gplus_url){ ?>
				<a class='gplus' href="<?php echo $gplus_url; ?>" target="_blank">google+</a>
			<?php } ?>
			<?php if($twt_url){ ?>
				<a class='twt' href="<?php echo $twt_url; ?>" target="_blank">twitter</a>
			<?php } ?>
			<?php if($lnk_url){ ?>
				<a class='lnk' href="<?php echo $lnk_url; ?>" target="_blank">linkedin</a>
			<?php } ?>
			<?php if($utb_url){ ?>
				<a class='utb' href="<?php echo $utb_url; ?>" target="_blank">Youtube</a>
			<?php } ?>
			<?php if($inst_url){ ?>
				<a class='inst' href="<?php echo $inst_url; ?>" target="_blank">Instagram</a>
			<?php } ?>
        <?php }else{ ?>
            <?php if($fb_url){ ?>
				<a class='icon' href="<?php echo $fb_url; ?>" target="_blank"><i class="fa fa-facebook"></i></a>
			<?php } ?>
				<?php if($gplus_url){ ?>
                    <a class='icon' href="<?php echo $gplus_url; ?>" target="_blank"><i class="fa fa-google-plus-g"></i></a>
			<?php } ?>
			<?php if($twt_url){ ?>
                <a class='icon' href="<?php echo $twt_url; ?>" target="_blank"><i class="fa fa-twitter"></i></a>
			<?php } ?>
			<?php if($lnk_url){ ?>
                <a class='icon' href="<?php echo $lnk_url; ?>" target="_blank"><i class="fa fa-linkedin"></i></a>
			<?php } ?>
			<?php if($utb_url){ ?>
                <a class='icon' href="<?php echo $utb_url; ?>" target="_blank"><i class="fa fa-youtube"></i></a>
				<?php } ?>
			<?php if($inst_url){ ?>
                <a class='icon' href="<?php echo $inst_url; ?>" target="_blank"><i class="fa fa-instagram"></i></a>
			<?php } ?>
        <?php } ?>
			<div class="clear"></div>
			<?php if($description){ ?>
				<p><?php echo $description; ?></p>
			<?php } ?>
		</div>
        <?php echo $after_widget; ?>
		
<?php
   }

   /**
	* update()
	* Function to update the settings from
	* the form() function.
	* Params:
	* - Array $new_instance
	* - Array $old_instance
	*/
	
	function update ( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
        $instance['title'] = $new_instance['title'] ;
        $instance['use_image'] = $new_instance['use_image'] ;
		$instance['fb_url'] = $new_instance['fb_url'] ;
		$instance['twt_url'] = $new_instance['twt_url'] ;
		$instance['gplus_url'] = $new_instance['gplus_url'] ;
		$instance['lnk_url'] = $new_instance['lnk_url'] ;
		$instance['utb_url'] = $new_instance['utb_url'] ;
		$instance['inst_url'] = $new_instance['inst_url'] ;
		$instance['description'] = $new_instance['description'] ;
		
		return $instance;
		
	}

   /**
	* form()
	* The form on the widget control in the
	* widget administration area.
	* Make use of the get_field_id() and 
	* get_field_name() function when creating
	* your form elements. This handles the confusing stuff.
	* Params:
	* - Array $instance
	*/

   function form( $instance ) { 
       
		/* Set up some default widget settings. */
		$defaults = array(
                        'title' => '',
                        'use_image' => '',
						'fb_url' => '',
						'twt_url' => '',
						'gplus_url' => '',
						'lnk_url' => '',
						'utb_url' => '',
						'inst_url' => '',
						'description' => ''
					);
		
        $instance = wp_parse_args( (array) $instance, $defaults );
        
   
?>
		<p>
	       <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'cappathemes' ); ?>
	       <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
	       </label>
		</p>
        <p>
            <input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'use_image' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'use_image' ) ); ?>" <?php checked( rgar( $instance, 'use_image' ) ); ?> value="1" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'use_image' ) ); ?>"><?php esc_html_e( 'Use Image', 'cappathemes' ); ?></label>
		</p>
		<p>
	       <label for="<?php echo $this->get_field_id( 'fb_url' ); ?>"><?php _e( 'Facebook url : ', 'cappathemes' ); ?>
	       <input class="widefat" id="<?php echo $this->get_field_id( 'fb_url' ); ?>" name="<?php echo $this->get_field_name( 'fb_url' ); ?>" type="text" value="<?php echo $instance['fb_url']; ?>" />
	       </label>
		</p>
		<p>
	       <label for="<?php echo $this->get_field_id( 'twt_url' ); ?>"><?php _e( 'Twitter url :', 'cappathemes' ); ?>
	       <input class="widefat" id="<?php echo $this->get_field_id( 'twt_url' ); ?>" name="<?php echo $this->get_field_name( 'twt_url' ); ?>" type="text" value="<?php echo $instance['twt_url']; ?>" />
	       </label>
		</p>
		<p>
	       <label for="<?php echo $this->get_field_id( 'gplus_url' ); ?>"><?php _e( 'Google + url : ', 'cappathemes' ); ?>
	       <input class="widefat" id="<?php echo $this->get_field_id( 'gplus_url' ); ?>" name="<?php echo $this->get_field_name( 'gplus_url' ); ?>" type="text" value="<?php echo $instance['gplus_url']; ?>" />
	       </label>
		</p>
		<p>
	       <label for="<?php echo $this->get_field_id( 'lnk_url' ); ?>"><?php _e( 'LinkedIn url :', 'cappathemes' ); ?>
	       <input class="widefat" id="<?php echo $this->get_field_id( 'lnk_url' ); ?>" name="<?php echo $this->get_field_name( 'lnk_url' ); ?>" type="text" value="<?php echo $instance['lnk_url']; ?>" />
	       </label>
		</p>
		<p>
	       <label for="<?php echo $this->get_field_id( 'utb_url' ); ?>"><?php _e( 'Youtube url :', 'cappathemes' ); ?>
	       <input class="widefat" id="<?php echo $this->get_field_id( 'utb_url' ); ?>" name="<?php echo $this->get_field_name( 'utb_url' ); ?>" type="text" value="<?php echo $instance['utb_url']; ?>" />
	       </label>
		</p>
		<p>
	       <label for="<?php echo $this->get_field_id( 'inst_url' ); ?>"><?php _e( 'Instagram url :', 'cappathemes' ); ?>
	       <input class="widefat" id="<?php echo $this->get_field_id( 'inst_url' ); ?>" name="<?php echo $this->get_field_name( 'inst_url' ); ?>" type="text" value="<?php echo $instance['inst_url']; ?>" />
	       </label>
		</p>
		<p>
	       <label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description :', 'cappathemes' ); ?>
	       <textarea class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>"> <?php echo $instance['description']; ?> </textarea>
	       </label>
		</p>
		
        
<?php
	}
	
}

?>