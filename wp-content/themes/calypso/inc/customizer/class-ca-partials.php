<?php
/**
 * Customizer partial manager.
 *
 * @package calypso
 */

class Ca_Partials {

    public function init() { }

    public function __construct() {
        $this->register_partials();
    }

    private function register_partials() {

        new Ca_General_Partials();
        $this->register_frontpage_partials();
        new Ca_Layout_Partials();
        new Ca_Blog_Settings_Partials();
    }

    public function register_frontpage_partials(){
        new Ca_Welcome_Partials();
        new Ca_Features_Partials();
        new Ca_About_Partials();
        new Ca_Frontpage_Blog_Partials();
        new Ca_Subscribe_Partials();
        new Ca_Contact_Partials();

    }
}

?>