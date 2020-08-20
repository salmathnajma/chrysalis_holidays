<?php
/**
 * Customizer settings manager.
 *
 * @package calypso
 */

class Ca_Settings {

    public function init() { }

    public function __construct() {
        $this->register_settings();
    }

    private function register_settings() {

        new Ca_Frontpage_Customizer_Settings();
        new Ca_Appearence_Customizer_Settings();
        new Ca_Analytics_Customizer_Settings();
        new Ca_Typography_Customizer_Settings();
        new Ca_Blog_Customizer_Settings();
    
    }

    public function register_additional_settings() {

        $frontpage = new Ca_Frontpage_Customizer_Settings();
        $frontpage->render_additional_settings();

    }

    

}

?>