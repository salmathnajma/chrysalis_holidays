<?php
/**
 * class for home page slider section view
 *
 * @package calypso
 */

class Ca_Slider_Section{
    public function __construct() {
		$this->hook_section();
	}

	private function hook_section() {
        $section_priority = apply_filters( 'ca_slider_priority',10);
        add_action( 'home_sections', array( $this, 'render_section' ), absint( $section_priority ) );
	}

	public function render_section() {
		$hide_section = get_theme_mod( 'ca_hide_slider_section', false );
        $style = '';
        $slider_content  = get_theme_mod( 'ca_slider_content');
		if ( (bool) $hide_section === true || !$slider_content) {
			if ( is_customize_preview() ) {
				$style = 'style="display: none"';
			} else {
				return;
			}	
        }

        $ca_slider_layout = apply_filters( 'ca_slider_layout', 'col-12' );
        $ca_slider_content_layout = apply_filters( 'ca_slider_content_layout', 'single' );


        do_action('before_slider');
		?>

        <section class="home-section ca-slider" id="slider" data-sorder="ca_slider" <?php echo wp_kses_post( $style ); ?>>
            <div class="container">
                <?php
                if($ca_slider_content_layout == 'single')
                    echo '<div class="row">';
                    if($ca_slider_layout)
                        echo "<div class='".$ca_slider_layout."'>";
                    
                    echo '<div class="carousel slide carousel-fade" data-ride="carousel" id="caSlider">';
                    $this->render_content($slider_content, $ca_slider_content_layout);
                    echo "</div>";

                    if($ca_slider_layout)
                        echo "</div>";
                if($ca_slider_content_layout == 'single')
                    echo "</div>";
                ?>
            </div>
		</section>
		<?php
        do_action('after_slider');

	}

	public function render_content($slider_content, $layout = 'single') {
        $slider_content = json_decode( $slider_content );
        if($slider_content){
            
            if($layout == 'single'){
                $slide_count = count($slider_content);
                $c = 0;
                echo '<ol class="carousel-indicators">';
                foreach ( $slider_content as $slider ) :
                    if($c == 0)
                        $class = " active ";
                    else
                        $class = "";
                    echo '<li data-target="#caSlider" data-slide-to="'.$c.'" class="'.$class.'"></li>';

                    $c++;
                endforeach;
                echo '</ol>';

                echo "<div class='carousel-inner'>";
                $i = 0; 
                $class = '';
                foreach ( $slider_content as $slider ) :
                    $image = ! empty( $slider->image_url ) ? apply_filters( 'ca_translate_single_string', $slider->image_url, 'Slider section' ) : '';
                    $title = ! empty( $slider->title ) ? apply_filters( 'ca_translate_single_string', $slider->title, 'Slider section' ) : '';
                    $text = ! empty( $slider->text ) ? apply_filters( 'ca_translate_single_string', $slider->text, 'Slider section' ) : '';
                    $link = ! empty( $slider->link ) ? apply_filters( 'ca_translate_single_string', $slider->link, 'Slider section' ) : '';

                    $extension =  substr($image, strrpos($image, '.' )+1); 

                    if($i == 0)
                        $class = " active ";
                    else
                        $class = "";
                    ?>
                    <div class="carousel-item <?php echo $class; ?>">
                        <a style="display:block" href="<?php echo $link; ?>">
                            <div class="image-container">
                            <?php if($extension == 'mp4'){ ?>
                                <video  title="1" id="bgvid" autoplay loop muted poster="http://www.thefrasier.com/wp-content/themes/frasier/images/Frasier_Home_120314.jpg"><source src="<?php echo $image; ?>" type="video/webm">Your browser does not support the video tag.</video>
                            <?php }else{ ?>
                                <img className="d-block w-100" src="<?php echo $image; ?>"/>
                            <?php } ?>    
                           </div>
                            <div class="slide-content">
                                <?php


                                if($title && $link)
                                    echo '<h2 class="slide-title"><a href="'.$link.'">'.$title.' <i class="fa fa-angle-right" aria-hidden="true"></i></a></h2>';
                         
                                if($text)
                                    echo '<p class="slide-text">' .$text. '</p>';

                                if($link && !$title)
                                    echo ' <a class="link" href="'. $link .'">More</a>';
                            
                                ?>
                            </div>
                        </a>
                    </div>
                    <?php
                    $i++;
                endforeach;
                echo "</div>";
            }elseif($layout == 'double'){
                $slide_count = count($slider_content);
                $c = 0;
              
                echo "<div class='carousel-inner'>";
                $i = 0; 
                $class = '';
                foreach ( $slider_content as $slider ) :
                    $image = ! empty( $slider->image_url ) ? apply_filters( 'ca_translate_single_string', $slider->image_url, 'Slider section' ) : '';
                    $title = ! empty( $slider->title ) ? apply_filters( 'ca_translate_single_string', $slider->title, 'Slider section' ) : '';
                    $text = ! empty( $slider->text ) ? apply_filters( 'ca_translate_single_string', $slider->text, 'Slider section' ) : '';
                    $link = ! empty( $slider->link ) ? apply_filters( 'ca_translate_single_string', $slider->link, 'Slider section' ) : '';

                    $extension =  substr($image, strrpos($image, '.' )+1); 

                    if($i == 0)
                        $class = " active ";
                    else
                        $class = "";
                    ?>
                    <div class="carousel-item <?php echo $class; ?>">
                        <div class="row">
                          
                            <div class="slide-content col-sm-12 col-md-6">
                                <?php


                                if($title && $link)
                                    echo '<h2 class="slide-title"><a href="'.$link.'">'.htmlspecialchars_decode($title).'</a></h2>';
                                
                                if($text)
                                    echo '<p class="slide-text">' .$text. '</p>';

                                if($link)
                                    echo ' <a class="link" href="'. $link .'">More</a>';
                            
                                ?>
                            </div>
                            <div class="image-container col-sm-12 col-md-6">
                                <?php if($extension == 'mp4'){ ?>
                                    <video  title="1" id="bgvid" autoplay loop muted poster="http://www.thefrasier.com/wp-content/themes/frasier/images/Frasier_Home_120314.jpg"><source src="<?php echo $image; ?>" type="video/webm">Your browser does not support the video tag.</video>
                                <?php }else{ ?>
                                    <img className="d-block w-100" src="<?php echo $image; ?>"/>
                                <?php } ?>
                                
                               
                            </div>
                        </div>
                    </div>
                    <?php
                    $i++;
                endforeach;
                echo "</div>";
                ?>
                <div class="slide-footer">
                <div class="num">1/<?php echo $i ?></div>
                <script>
                ( function( $ ) {
                    var totalItems = $('#caSlider .carousel-item').length;
                    var currentIndex = $('div.active').index() + 1;
                    $('#caSlider').bind('slid.bs.carousel', function() {
                        currentIndex = $('div.carousel-item.active').index() + 1;
                        $('#caSlider .num').html(''+currentIndex+'/'+totalItems+'');
                    });
                } )( jQuery );
                
                </script>
                
                <a class="carousel-control-prev" href="#caSlider" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#caSlider" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
                <?php
            }elseif($layout == 'showcounts'){
                $slide_count = count($slider_content);
                $c = 0;
                
                echo "<div class='carousel-inner'>";
                $i = 0; 
                $class = '';
                foreach ( $slider_content as $slider ) :
                    $image = ! empty( $slider->image_url ) ? apply_filters( 'ca_translate_single_string', $slider->image_url, 'Slider section' ) : '';
                    $title = ! empty( $slider->title ) ? apply_filters( 'ca_translate_single_string', $slider->title, 'Slider section' ) : '';
                    $text = ! empty( $slider->text ) ? apply_filters( 'ca_translate_single_string', $slider->text, 'Slider section' ) : '';
                    $link = ! empty( $slider->link ) ? apply_filters( 'ca_translate_single_string', $slider->link, 'Slider section' ) : '';
                   
                    $extension =  substr($image, strrpos($image, '.' )+1); 

                    if($i == 0)
                        $class = " active ";
                    else
                        $class = "";
                    ?>
                    <div class="carousel-item <?php echo $class; ?>">
                        <a style="display:block" href="<?php echo $link; ?>">
                            <div class="image-container">
                            <?php if($extension == 'mp4'){ ?>
                                <video  title="1" id="bgvid" autoplay loop muted poster="http://www.thefrasier.com/wp-content/themes/frasier/images/Frasier_Home_120314.jpg"><source src="<?php echo $image; ?>" type="video/webm">Your browser does not support the video tag.</video>
                            <?php }else{ ?>
                                <img className="d-block w-100" src="<?php echo $image; ?>"/>
                            <?php } ?>    
                           </div>
                            <div class="slide-content">
                                <?php


                                if($title && $link)
                                    echo '<h2 class="slide-title"><a href="'.$link.'">'.$title.'<i class="fa fa-angle-right" aria-hidden="true"></i></a></h2>';
                                
                                if($text)
                                    echo '<p class="slide-text">' .$text. '</p>';

                                if($link && !$title)
                                    echo ' <a class="link" href="'. $link .'">More</a>';
                            
                                ?>
                            </div>
                        </a>
                    </div>
                    <?php
                    $i++;
                endforeach;
                echo "</div>";
                ?>
                 <div class="slide-footer">
                <div class="num">1/<?php echo $i ?></div>
                <script>
                ( function( $ ) {
                    var totalItems = $('#caSlider .carousel-item').length;
                    var currentIndex = $('div.active').index() + 1;
                    $('#caSlider').bind('slid.bs.carousel', function() {
                        currentIndex = $('div.carousel-item.active').index() + 1;
                        $('#caSlider .num').html(''+currentIndex+'/'+totalItems+'');
                    });
                } )( jQuery );
                
                </script>
                
                <a class="carousel-control-prev" href="#caSlider" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#caSlider" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <?php
            }

            
        }
        
	}



}

new Ca_Slider_Section();