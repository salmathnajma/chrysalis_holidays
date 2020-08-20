<?php
/**
* Plugin Name: Comment Rating Field Plugin
* Plugin URI: https://www.wpzinc.com/plugins/comment-rating-field-pro-plugin
* Version: 3.5.2
* Author: WP Zinc
* Author URI: https://www.wpzinc.com
* Description: Adds a 5 star rating field to the comments form in WordPress.
* License: GPL2
*/

/**
 * Comment Rating Field Plugin Class
 * 
 * @package Comment Rating Field Plugin
 * @author  Tim Carr
 * @version 2.1.0
 */
class CommentRatingFieldPlugin {

    /**
     * Holds the class object.
     *
     * @since   2.1.1
     *
     * @var     object
     */
    public static $instance;

    /**
     * Plugin
     *
     * @since   2.1.1
     *
     * @var     object
     */
    public $plugin = '';

    /**
     * Dashboard
     *
     * @since   2.1.1
     *
     * @var     object
     */
    public $dashboard = '';

    /**
     * Constructor.
     *
     * @since   1.0.0
     */
    public function __construct() {

        // Plugin Details
        $this->plugin                   = new stdClass;
        $this->plugin->name             = 'comment-rating-field-plugin';
        $this->plugin->displayName      = 'Comment Rating Field';
        $this->plugin->version          = '3.5.2';
        $this->plugin->buildDate        = '2018-02-01 18:00:00';
        $this->plugin->requires         = 3.6;
        $this->plugin->tested           = '4.9.2';
        $this->plugin->folder           = plugin_dir_path( __FILE__ );
        $this->plugin->url              = plugin_dir_url( __FILE__ );
        $this->plugin->documentation_url= 'https://www.wpzinc.com/documentation/comment-rating-field-pro';
        $this->plugin->support_url      = 'https://www.wpzinc.com/support';
        $this->plugin->upgrade_url      = 'https://www.wpzinc.com/plugins/comment-rating-field-pro';
        $this->plugin->review_name      = 'comment-rating-field-plugin';
        $this->plugin->review_notice = sprintf( __( 'Thanks for using %s to collect review ratings from web site visitors!', $this->plugin->name ), $this->plugin->displayName );

        // Upgrade Reasons
        $this->plugin->upgrade_reasons = array(
            array(
                __( 'Choose Maximum Rating Scale', $this->plugin->name), 
                __( 'You\'re not restricted to a 5 star rating scale; choose a maximum between 3 and 10', $this->plugin->name ),
            ),
            array(
                __( 'Unlimited Rating Fields for any Post Type or Taxonomy Terms', $this->plugin->name ), 
                __( 'Add more than one rating field to your comment forms for Pages, Posts, Custom Post Types and/or Taxonomy Terms', $this->plugin->name ),
            ),
            array(
                __( 'Different Rating Fields by Post Type and Taxonomy', $this->plugin->name ), 
                __( 'Each rating field group can be targeted to a specific Post Type and/or Taxonomy, allowing different fields for different sections of your web site.', $this->plugin->name ),
            ),
            array(
                __( 'Google Rich Snippet Support', $this->plugin->name ), 
                __( 'Choose a schema (e.g. Review, Product, Place, Person) for your Ratings.  Visitors can see the average rating on your Google search results.', $this->plugin->name ),
            ),
            array(
                __( 'Rating Field Placement', $this->plugin->name ), 
                __( 'Rating fields on your comment form can display before all fields, before the comment field or after the comment field.', $this->plugin->name ),
            ),
            array(
                __( 'Rating Control', $this->plugin->name ), 
                __( 'Disable ratings on replies, limit the number of ratings per User per Post.  Also limit which WordPress User Roles can leave ratings.', $this->plugin->name ),
            ),
            array(
                __( 'Rating Output', $this->plugin->name ), 
                __( 'Average ratings can be displayed in excerpts, content and/or RSS feeds, either as whole numbers or rounded up to 2 decimal places.  Choose to display average rating, rating breakdown, number of ratings and more.', $this->plugin->name ),
            ),
            array(
                __( 'Amazon Bar Chart Style', $this->plugin->name ), 
                __( 'Ratings can be output in a bar chart breakdown style, similar to Amazon.', $this->plugin->name ),
            ),
            array(
                __( 'Filter and Sort Ratings', $this->plugin->name ), 
                __( 'Users can filter and sort comments by rating.', $this->plugin->name ),
            ),
            array(
                __( 'Jetpack, WooCommerce and Simple Comment Editing Support', $this->plugin->name ), 
                __( 'Pro is compatible with Jetpack, WooCommerce and SCE.', $this->plugin->name ),
            ),
            array(
                __( 'Advanced Shortcodes', $this->plugin->name ), 
                __( 'Use advanced shortcode to display the rating output anywhere within your content, for any Post ID.', $this->plugin->name ),
            ),
            array(
                __( 'Widgets', $this->plugin->name ), 
                __( 'Display the Top Rated Posts as a Widget in your WordPress Sidebars.', $this->plugin->name ),
            ),
        );             	
        
        // Dashboard Submodule
        if ( ! class_exists( 'WPZincDashboardWidget' ) ) {
            require_once( $this->plugin->folder . '_modules/dashboard/dashboard.php' );
        }
        $this->dashboard = new WPZincDashboardWidget( $this->plugin );

        // Global
        require_once( $this->plugin->folder . 'includes/global/ajax.php' );
        require_once( $this->plugin->folder . 'includes/global/common.php' );
        require_once( $this->plugin->folder . 'includes/global/fields.php' );
        require_once( $this->plugin->folder . 'includes/global/groups.php' );
        require_once( $this->plugin->folder . 'includes/global/rating-input.php' );
        require_once( $this->plugin->folder . 'includes/global/rating-output.php' );
        require_once( $this->plugin->folder . 'includes/global/settings.php' );
        require_once( $this->plugin->folder . 'includes/global/shortcode.php' );

        // Init non-static classes
        $ajax = Comment_Rating_Field_Pro_AJAX::get_instance();
        $common = Comment_Rating_Field_Pro_Common::get_instance();
        $input = Comment_Rating_Field_Pro_Rating_Input::get_instance();
        $output = Comment_Rating_Field_Pro_Rating_Output::get_instance();
        $shortcode = Comment_Rating_Field_Pro_Shortcode::get_instance();
        
        // Admin
        if ( is_admin() ) {
            require_once( $this->plugin->folder . 'includes/admin/admin.php' );
            require_once( $this->plugin->folder . 'includes/admin/comments.php' );
            require_once( $this->plugin->folder . 'includes/admin/editor.php' );
            require_once( $this->plugin->folder . 'includes/admin/install.php' );

            // Init non-static classes
            $admin = Comment_Rating_Field_Pro_Admin::get_instance();
            $admin_comments = Comment_Rating_Field_Pro_Admin_Comments::get_instance();
            $admin_editor = Comment_Rating_Field_Pro_Editor::get_instance();
            $admin_install = Comment_Rating_Field_Pro_Install::get_instance();
            
            // Run upgrade routines
            add_action( 'init', array( $this, 'upgrade' ) );
        }

    }

    /**
     * Runs the upgrade routine once the plugin has loaded
     *
     * @since   3.5.1
     */
    public function upgrade() {

        // Run upgrade routine 
        Comment_Rating_Field_Pro_Install::get_instance()->upgrade();

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since   2.1.1
     *
     * @return  object Class.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
            self::$instance = new self;
        }

        return self::$instance;

    }
}

// Initialise class
$comment_rating_field_plugin = CommentRatingFieldPlugin::get_instance();

// Register activation hooks
register_activation_hook( __FILE__, array( 'Comment_Rating_Field_Pro_Install', 'activate' ) );
add_action( 'activate_wpmu_site', array( 'Comment_Rating_Field_Pro_Install', 'activate_wpmu_site' ) );