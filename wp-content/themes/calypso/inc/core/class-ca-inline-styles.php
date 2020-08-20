<?php
/**
 * class for include inline styles
 *
 * @package calypso
 */
class Ca_Inline_Styles {

    public $font_weight = 'regular';
    public $anchor_color = '#2CD007';
    public $font_color = '#222';
    public $dark_bg_font_color = '#fff';

    public $body_font_family = 'Open Sans';
    public $body_font_size = '16';
   
    public $heading_font_family = 'Montserrat';
    public $heading_font_size = '20';
    public $title_font_size = '24';


    public function __construct() {

        $this->register_google_font();

        $this->render_inline_styles();
        
    }

    public function register_google_font() {
       

        $heading_fontfamily = get_theme_mod( 'ca_heading_fontfamily' );
        $body_fontfamily = get_theme_mod( 'ca_body_fontfamily' );

        if($heading_fontfamily){
            $this->enqueue_google_font( $heading_fontfamily, 'heading' );
        }
        if($body_fontfamily){
            $this->enqueue_google_font( $body_fontfamily, 'body' );
        }
    }

    private function enqueue_google_font( $font_face, $handle ) {

        $font_face = json_decode($font_face);

        $family = ($font_face->font) ? $font_face->font : $this->body_font_family;
        $weight = ($font_face->regularweight) ? $font_face->regularweight : $this->font_weight;

        $base_url = '//fonts.googleapis.com/css';

        $font = trim( $family );
        if ( ! empty( $weight ) ) {
			$font .= ':' . $weight ;
        }
        
        $query_args = array(
			'family' => urlencode( $font ),
        );
        
        $url = add_query_arg( $query_args, $base_url );

		// Enqueue style
		wp_enqueue_style( 'ca-google-font-' . $handle, $url, array(), false );
    }

    function render_inline_styles(){
        $custom_css = '';

        $custom_css .= $this->get_menu_styles();
        $custom_css .= $this->get_heading_styles();
        $custom_css .= $this->get_title_styles();
        $custom_css .= $this->get_body_styles();
        $custom_css .= $this->get_frontpage_styles();
        

        wp_add_inline_style( 'calypso-style', $custom_css );
    }

    function get_menu_styles(){

        $menu_fontsize = get_theme_mod( 'ca_menu_fontsize' , $this->body_font_size);
        $menu_fontcolor = get_theme_mod( 'ca_menu_fontcolor', $this->font_color );

        $remsize = $menu_fontsize / 16;
        $remsize .= 'rem';
      
        $custom_css = "
        .navbar .navbar-nav > li > a, .navbar-expand-md .navbar-nav .nav-link,
        .navbar .navbar-nav > li .dropdown-menu li > a{
            font-size: ".$remsize."; 
            color:".$menu_fontcolor.";

        }";

        return $custom_css;
    }

    function get_heading_styles(){
        
        $fontfamily = get_theme_mod( 'ca_heading_fontfamily' );
        $fontsize = get_theme_mod( 'ca_heading_fontsize' , $this->heading_font_size);
      
        $fontfamily = json_decode($fontfamily);

        $family = (isset($fontfamily->font)) ? $fontfamily->font : $this->heading_font_family;
        $weight = (isset($fontfamily->regularweight)) ? $fontfamily->regularweight : $this->font_weight;

        $remsize = $fontsize / 16;
        $remsize .= 'rem';

        $custom_css = "h1,h2,h3,h4,h5,h6, h1 a,h2 a,h3 a,h4 a,h5 a,h6 a{
            font-family : '".$family."';
            font-weight : ".$weight.";
        }";

        $custom_css .= "h2.entry-title{
            font-size: ".$remsize."; 
        }";

        return $custom_css;
    }

    function get_title_styles(){

        $fontsize = get_theme_mod( 'ca_title_fontsize', $this->title_font_size );

        $remsize = $fontsize / 16;
        $remsize .= 'rem';
      
        $custom_css = "h1.ca-title, h1.ca-title a{
             font-size: ".$remsize."; 
        }";

        return $custom_css;

    }

    function get_body_styles(){
       
        $fontsize = get_theme_mod( 'ca_body_fontsize', $this->body_font_size );
        $fontcolor = get_theme_mod( 'ca_body_fontcolor', $this->font_color );

        $fontfamily = get_theme_mod( 'ca_body_fontfamily' );

        $fontfamily = json_decode($fontfamily);

        $family = (isset($fontfamily->font)) ? $fontfamily->font : $this->body_font_family;
        $weight = (isset($fontfamily->regularweight)) ? $fontfamily->regularweight : $this->font_weight;

        $remsize = $fontsize / 16;
        $remsize .= 'rem';

        $custom_css = "body, button, input, select, optgroup, textarea,p {
            font-family : '".$family."';
            font-weight : ".$weight.";
            font-size: ".$remsize."; 
            color:".$fontcolor.";
        }";

        $a_fontcolor = get_theme_mod( 'ca_anchor_fontcolor', $this->anchor_color );

        $custom_css .= "a {
            color:".$a_fontcolor.";
        }";

        return $custom_css;
    }

    function get_frontpage_styles(){
        $ca_section_title_fontsize = get_theme_mod( 'ca_section_title_fontsize', $this->heading_font_size );
        
        $ca_section_des_fontsize = get_theme_mod( 'ca_section_des_fontsize', $this->body_font_size );
        
        $ca_welcome_title_fontsize = get_theme_mod( 'ca_welcome_title_fontsize', $this->title_font_size  );
        $ca_welcome_fontcolor = get_theme_mod( 'ca_welcome_fontcolor', $this->dark_bg_font_color);

        $ca_welcome_des_fontsize = get_theme_mod( 'ca_welcome_des_fontsize', $this->body_font_size );
        
        $ca_section_title_remsize = $ca_section_title_fontsize / 16;
        $ca_section_title_remsize .= 'rem';
        $custom_css = ".home-section h2.ca-title {
            font-size: ".$ca_section_title_remsize."; 
        }";

        $ca_section_des_remsize = $ca_section_des_fontsize / 16;
        $ca_section_des_remsize .= 'rem';
        $custom_css .= ".home-section .ca-description {
            font-size: ".$ca_section_des_remsize."; 
        }";

        $ca_welcome_title_remsize = $ca_welcome_title_fontsize / 16;
        $ca_welcome_title_remsize .= 'rem';
        $custom_css .= "#welcome.home-section h1.welcome-title {
            font-size: ".$ca_welcome_title_remsize."; 
            color:".$ca_welcome_fontcolor.";
        }";

        $ca_welcome_des_remsize = $ca_welcome_des_fontsize / 16;
        $ca_welcome_des_remsize .= 'rem';
        $custom_css .= "#welcome.home-section .welcome-sub-title {
            font-size: ".$ca_welcome_des_remsize."; 
            color:".$ca_welcome_fontcolor.";
        }";

        return $custom_css;
    }
}
?>