<?php
/**
 * defines all about partials
 *
 * @package calypso
 */
class Ca_About_Partials {

    public function __construct() {
        
        $this->render_partials();
    }

    private function render_partials(){

        global $wp_customize;

        $wp_customize->selective_refresh->add_partial( 'ca_about_image', array(
            'selector' => 'section#about',
            'container_inclusive' => true,
            'render_callback' => function() {
                echo $this->ca_get_about_image();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_hide_about_section', array(
            'selector' => 'section#about',
            'container_inclusive' => true,
           'render_callback' => function() {
                echo $this->ca_get_about_section();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_about_content', array(
            'selector' => '.ca-about-content',
           'render_callback' => function() {
                echo $this->ca_get_about_content();
             },
        ) );
    }

    function ca_get_about_image(){
        $about = new Ca_About_Section();
        $background = $about->get_background();
        if($background){
            $class_to_add   = 'section-image';
            $section_style .= 'style="background-image: url(\'' . esc_url( $background ) . '\');"';
           
            ?>
            <section class="ca-about <?php echo esc_attr( $class_to_add ); ?>" id="about" data-sorder="ca_about" <?php echo wp_kses_post( $section_style ); ?>>
                <div class="container">
                    <div class="row ca-about-content">
                        <?php $about->render_content(); ?>
                    </div>
                </div>
            </section>
            <?php
        }
    }

    function ca_get_about_content(){
        $about = new Ca_About_Section();
        $about->render_content();
     
    }

    function ca_get_about_section(){
        $about = new Ca_About_Section();
        $about->render_section();
    }
}
