<?php
/**
 * Customizer section manager.
 *
 * @package calypso
 */

class Ca_Sections {

    public function init() {}

    public function __construct() {
        $this->register_sections();
    }

    private function register_sections() {

        new Ca_Appearence_Customizer_Sections();
        new Ca_Analytics_Customizer_Sections();
        new Ca_Typography_Customizer_Sections();
        new Ca_Frontpage_Customizer_Sections();
        new Ca_Blog_Customizer_Sections();
   
    }

    public function register_additional_sections() {
        $frontpage = new Ca_Frontpage_Customizer_Sections();
        $frontpage->render_additional_sections();
    }

   
   
}

?>