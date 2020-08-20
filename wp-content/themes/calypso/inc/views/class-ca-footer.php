<?php
/**
 * class for footer view
 *
 * @package calypso
 */

class Ca_Footer{
    public function __construct() {
        add_action( 'ca_do_footer', array( $this, 'footer_content' ) );
      //  add_filter( 'wp_nav_menu_args', array( $this, 'modify_primary_menu' ) );
    }

    public function footer_content() {
        ?>
        <div class="container">
            <?php $this->footer_sidebars(); ?>

        </div>
        <?php
    }

    private function footer_sidebars() {
       
        $sidebars = $this->get_active_sidebars();
        $number = count($sidebars);
        $columns_val = ca_get_column_layout();
        if ( in_array( $number, $columns_val ) ) {
			$columns_class = array_search( $number, $columns_val );
        }
        ?>
         <div class="content">
        <div class="row">
            <?php
            foreach ( $sidebars as $sidebar ) {
                    echo '<div class="footer-item col-sm '. $columns_class .'">';
                    dynamic_sidebar( $sidebar );
                    echo '</div>';
            }
            ?>
        </div>
        </div>
        <?php

    }

    private function get_active_sidebars() {
        $sidebars = array(
			'footer-one-widgets',
			'footer-two-widgets',
            'footer-three-widgets',
            'footer-four-widgets'
        );
        $res = array();
        foreach ($sidebars as $sidebar){
            $has_widgets = is_active_sidebar( $sidebar );
            if($has_widgets)
                $res[] = $sidebar;
        }
        return $res;
    }

   


}
new Ca_Footer();