<?php
/**
 * Customizer controls manager.
 *
 * @package calypso
 */

class Ca_Controls {

    public function init() {

    }

    public function __construct() {
        $this->register_controls();
    }

    private function register_controls() {

        $this->render_frontpage_controls();
        $this->render_appearance_controls();
        $this->render_analytics_controls();
        $this->render_blog_controls();

        new Ca_Typography_Controls();

    }

    public function register_additional_controls() {
        
        new Ca_Subscribe_Controls();
        new Ca_Contact_Controls();
    }

    public function render_blog_controls(){
        new Ca_Blog_Settings_Controls();
    }

   

    public function render_appearance_controls(){
        
        new Ca_Layout_Controls();
        new Ca_Page_Header_Controls();

    }

    public function render_analytics_controls(){
        
        new Ca_Tracking_Controls();
        
    }

    public function render_frontpage_controls(){

        new Ca_Welcome_Controls();
        new Ca_Features_Controls();
        new Ca_Slider_Controls();
        new Ca_About_Controls();
        new Ca_Frontpage_Blog_Controls();

    }


    

   

    

}

?>