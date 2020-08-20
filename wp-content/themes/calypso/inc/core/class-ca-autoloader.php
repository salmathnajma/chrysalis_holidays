<?php
/**
 * This is the theme core class. Used to include all classes
 *
 * @package calypso
 */

class Ca_Autoloader {

    private $default_classes = array();

    public function __construct() {
        $this->default_classes = array(
            /* Core classes */
            'Ca_Core' => CA_CORE_DIR,
            'Ca_Backend'  => CA_CORE_DIR,
            'Ca_Frontend'  => CA_CORE_DIR,
            'Ca_Inline_Styles'  => CA_CORE_DIR,

            /* Abstract core classses */
            //'Ca_Register_Customizer_Controls'  => CA_CORE_DIR . 'abstract/',
            //'Ca_Home_Page_Section_Controls'  => CA_CORE_DIR . 'abstract/',

            /* Customizer type classses */
            //'Ca_Customizer_Control'  => CA_CORE_DIR . 'types/',
            //'Ca_Customizer_Panel'  => CA_CORE_DIR . 'types/',
            //'Ca_Customizer_Partial'  => CA_CORE_DIR . 'types/',
            //'Ca_Customizer_Section'  => CA_CORE_DIR . 'types/',

             /* Customizer Classes */
             'Ca_Customizer' => CA_CUSTOMIZER_DIR,
             'Ca_Panels' => CA_CUSTOMIZER_DIR,
             'Ca_Sections' => CA_CUSTOMIZER_DIR,
             'Ca_Settings' => CA_CUSTOMIZER_DIR,
             'Ca_Controls' => CA_CUSTOMIZER_DIR,
             'Ca_Partials' => CA_CUSTOMIZER_DIR,

             /* Sections */
             'Ca_Frontpage_Customizer_Sections' => CA_CUSTOMIZER_DIR . 'sections/',
             'Ca_Typography_Customizer_Sections' => CA_CUSTOMIZER_DIR . 'sections/',
             'Ca_Appearence_Customizer_Sections' => CA_CUSTOMIZER_DIR . 'sections/',
             'Ca_Analytics_Customizer_Sections' => CA_CUSTOMIZER_DIR . 'sections/',
             'Ca_Blog_Customizer_Sections' => CA_CUSTOMIZER_DIR . 'sections/',

             /* Settings */
             'Ca_Frontpage_Customizer_Settings' => CA_CUSTOMIZER_DIR . 'settings/',
             'Ca_Appearence_Customizer_Settings' => CA_CUSTOMIZER_DIR . 'settings/',
             'Ca_Analytics_Customizer_Settings' => CA_CUSTOMIZER_DIR . 'settings/',
             'Ca_Blog_Customizer_Settings' => CA_CUSTOMIZER_DIR . 'settings/',
             'Ca_Typography_Customizer_Settings' => CA_CUSTOMIZER_DIR . 'settings/',

             /* Controls */
             'Ca_Welcome_Controls' => CA_CUSTOMIZER_DIR . 'controls/front-page/',
             'Ca_Features_Controls' => CA_CUSTOMIZER_DIR . 'controls/front-page/',
             'Ca_Slider_Controls' => CA_CUSTOMIZER_DIR . 'controls/front-page/',
             'Ca_About_Controls' => CA_CUSTOMIZER_DIR . 'controls/front-page/',
             'Ca_Frontpage_Blog_Controls' => CA_CUSTOMIZER_DIR . 'controls/front-page/',
             'Ca_Contact_Controls' => CA_CUSTOMIZER_DIR . 'controls/front-page/',
             'Ca_Subscribe_Controls' => CA_CUSTOMIZER_DIR . 'controls/front-page/',

             'Ca_Layout_Controls' => CA_CUSTOMIZER_DIR . 'controls/general/',
             'Ca_Tracking_Controls' => CA_CUSTOMIZER_DIR . 'controls/general/',
             'Ca_Page_Header_Controls' => CA_CUSTOMIZER_DIR . 'controls/general/',
             'Ca_Typography_Controls' => CA_CUSTOMIZER_DIR . 'controls/general/',

             'Ca_Blog_Settings_Controls' => CA_CUSTOMIZER_DIR . 'controls/blogs/',

             /* Partials */
             'Ca_General_Partials' => CA_CUSTOMIZER_DIR . 'partials/general/',
             'Ca_Layout_Partials' => CA_CUSTOMIZER_DIR . 'partials/general/',

             'Ca_Welcome_Partials' => CA_CUSTOMIZER_DIR . 'partials/front-page/',
             'Ca_Features_Partials' => CA_CUSTOMIZER_DIR . 'partials/front-page/',
             'Ca_About_Partials' => CA_CUSTOMIZER_DIR . 'partials/front-page/',
             'Ca_Frontpage_Blog_Partials' => CA_CUSTOMIZER_DIR . 'partials/front-page/',
             'Ca_Subscribe_Partials' => CA_CUSTOMIZER_DIR . 'partials/front-page/',
             'Ca_Contact_Partials' => CA_CUSTOMIZER_DIR . 'partials/front-page/',

             'Ca_Blog_Settings_Partials' => CA_CUSTOMIZER_DIR . 'partials/blogs/',

             /* Custom Controls */
             'Ca_Image_Radiobutton_Control' => CA_CUSTOMIZER_DIR . 'custom-controls/image-radiobutton/',
             'Ca_Notice_Control' => CA_CUSTOMIZER_DIR . 'custom-controls/notice/',
             'Ca_Editor_Control' => CA_CUSTOMIZER_DIR . 'custom-controls/custom-editor/',
             'Ca_Page_Editor_Helper' => CA_CUSTOMIZER_DIR . 'custom-controls/page-editor/',
             'Ca_Page_Editor_Control' => CA_CUSTOMIZER_DIR . 'custom-controls/page-editor/',
             'Ca_Google_Fonts_Control' => CA_CUSTOMIZER_DIR . 'custom-controls/google-fonts/',
             'Ca_Slider_Control' => CA_CUSTOMIZER_DIR . 'custom-controls/slider/',
             'Ca_Alpha_Color_Control' => CA_CUSTOMIZER_DIR . 'custom-controls/alpha-color/',
             'Ca_Heading_Control' => CA_CUSTOMIZER_DIR . 'custom-controls/heading/',
             'Ca_Repeater_Control' => CA_CUSTOMIZER_DIR . 'custom-controls/repeater/',
             'Ca_Button_Control' => CA_CUSTOMIZER_DIR . 'custom-controls/',
             

  
            /* View Classes */
            'Ca_Bootstrap_Nav' => CA_VIEW_DIR,
            'Ca_Filters' => CA_VIEW_DIR,
            'Ca_Header' => CA_VIEW_DIR,
            'Ca_Footer' => CA_VIEW_DIR,
           
            'Ca_Layout_Manager' => CA_VIEW_DIR. 'layout/',
            'Ca_Blog_Layout' => CA_VIEW_DIR. 'layout/',
            'Ca_Sidebar_Layout' => CA_VIEW_DIR. 'layout/',

            'Ca_Welcome_Section' => CA_VIEW_DIR . 'front-page/',
            'Ca_Slider_Section' => CA_VIEW_DIR . 'front-page/',
            'Ca_Features_Section' => CA_VIEW_DIR . 'front-page/',
            'Ca_About_Section' => CA_VIEW_DIR . 'front-page/',
            'Ca_Blog_Section' => CA_VIEW_DIR . 'front-page/',
            'Ca_Subscribe_Section' => CA_VIEW_DIR . 'front-page/',
            'Ca_Contact_Section' => CA_VIEW_DIR . 'front-page/',

            'Ca_Blog_Feature_Views' => CA_VIEW_DIR . 'blogs/',
        );
    }
    
    /* Include classes to the theme */
    public function load_classes( $class_name = array() ) {
        
        $class_to_load = ($class_name) ? array_merge($this->default_classes, $class_name) : $this->default_classes;
        $test = '';
         foreach($class_to_load as $key => $val){
            $class  = 'class-' . str_replace( '_', '-', strtolower( $key ) ) . '.php';
            $file = $val . $class;
            if ( file_exists( $file ) ) {
      //          $test .=$file.'</br>';
                include $file;
               
            }
        }

      
     
        
         return true;
        
	}

    
}



?>