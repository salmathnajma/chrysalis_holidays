<?php
/**
 * class for home page blog section view
 *
 * @package calypso
 */

class Ca_Features_Section{
    public function __construct() {
		$this->hook_section();
	}

	private function hook_section() {
        $section_priority = apply_filters( 'ca_feature_priority', 20 );
        add_action( 'home_sections', array( $this, 'render_section' ), absint( $section_priority ) );
	}

	public function render_section() {
		$hide_section = get_theme_mod( 'ca_hide_feature_section', false );
        $section_style = '';
		if ( (bool) $hide_section === true ) {
            if ( is_customize_preview() ) {
                $section_style .= 'display: none;';
            } else {
                return;
            }
        }

        $ca_feature_layout = get_theme_mod( 'ca_feature_layout', 'center' );

        $title = get_theme_mod( 'ca_feature_title','Features'  );
        $description = get_theme_mod( 'ca_feature_description');

        $default_content = current_user_can( 'edit_theme_options' ) ? $this->get_features_default() : false;
        $features_content  = get_theme_mod( 'ca_features_content', $default_content );


        $section_style = 'style="' . $section_style . '"';

        

        do_action('before_features');
		?>
        <section class="home-section ca-features" id="features" data-sorder="ca_features" <?php echo wp_kses_post( $section_style ); ?>>
            <div class="container">
                <?php if($ca_feature_layout == 'center'){ ?>
                    <div class="row d-block col-12 text-center ca-features-title-section">
                        <?php 
                        if ( ! empty( $title ) || is_customize_preview() ) {
                            echo '<h2 class="ca-title ca-features-title">' . wp_kses_post( $title ) . '</h2>';
                        }
                        if ( ! empty( $description ) || is_customize_preview() ) {
                            echo '<p class="ca-description ca-title-description ca-features-description" >' .  $description  . '</p>';
                        }
                        ?>
                    </div>
                    <?php
                    $this->show_features_content( $features_content );
                    ?>
                <?php }else{ ?>
                    
                    
                    <div class="row">
                    <?php if($ca_feature_layout == 'left'){ ?>
                        <div class="col-md-4 text-center ca-features-title-section">
                            <?php 
                            if ( ! empty( $title ) || is_customize_preview() ) {
                                echo '<h2 class="ca-title ca-features-title">' . wp_kses_post( $title ) . '</h2>';
                            }
                            if ( ! empty( $description ) || is_customize_preview() ) {
                                echo '<p class="ca-description ca-title-description ca-features-description" >' .  $description  . '</p>';
                            }
                            ?>
                        </div>
                        <?php } ?>
                        
                        <div class="col-md-8">
                        <?php
                            $this->show_features_content( $features_content );
                            ?>
                        </div>
                        <?php if($ca_feature_layout == 'right'){ ?>
                        <div class="col-md-4 text-center ca-features-title-section">
                            <?php 
                            if ( ! empty( $title ) || is_customize_preview() ) {
                                echo '<h2 class="ca-title ca-features-title">' . wp_kses_post( $title ) . '</h2>';
                            }
                            if ( ! empty( $description ) || is_customize_preview() ) {
                                echo '<p class="ca-description ca-title-description ca-features-description" >' .  $description  . '</p>';
                            }
                            ?>
                        </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </section>
		<?php
        do_action('after_features');
    }
    
    function show_features_content( $features_content, $is_callback = false ) {
        if ( ! $is_callback ) {?>
            <div class="ca-features-content">
            <?php
        }

       
        if ( ! empty( $features_content ) ) :

            $features_content = json_decode( $features_content );
            if ( ! empty( $features_content ) ) {
                echo '<div class="row">';
                foreach ( $features_content as $features_item ) :
                    $icon = ! empty( $features_item->icon_value ) ? apply_filters( 'ca_translate_single_string', $features_item->icon_value, 'Features section' ) : '';
                    $image = ! empty( $features_item->image_url ) ? apply_filters( 'ca_translate_single_string', $features_item->image_url, 'Features section' ) : '';
                    $title = ! empty( $features_item->title ) ? apply_filters( 'ca_translate_single_string', $features_item->title, 'Features section' ) : '';
                    $text = ! empty( $features_item->text ) ? apply_filters( 'ca_translate_single_string', $features_item->text, 'Features section' ) : '';
                    $link = ! empty( $features_item->link ) ? apply_filters( 'ca_translate_single_string', $features_item->link, 'Features section' ) : '';
                    $color = ! empty( $features_item->color ) ? $features_item->color : '';
                    $color2 = ! empty( $features_item->color2 ) ? $features_item->color2 : '';
                    $choice = ! empty( $features_item->choice ) ? $features_item->choice : 'customizer_repeater_icon';
                    ?>
                    <div class="col-xs-12 <?php echo apply_filters( 'ca_features_per_row_class','col-md-4' ); ?> feature-box">
                        <div class="ca-info">
                            <?php
                            if ( ! empty( $link ) ) {
                                $link_html = '<a href="' . esc_url( $link ) . '"';
                                if ( function_exists( 'ca_is_external_url' ) ) {
                                    $link_html .= ca_is_external_url( $link );
                                }
                                $link_html .= '>';
                                echo wp_kses_post( $link_html );
                            }
    
                            switch ( $choice ) {
                                case 'customizer_repeater_image':
                                    if ( ! empty( $image ) ) {
                                        ?>
                                        <div class="card card-plain">
                                            <img src="<?php echo esc_url( $image ); ?>"/>
                                            </div>
                                            <?php
                                    }
                                    break;
                                case 'customizer_repeater_icon':
                                    if ( ! empty( $icon ) ) {
                                     
                                        ?>

                                        <div class="icon">
                    <i class="fa <?php echo esc_attr( $icon ); ?>" <?php echo ( ! empty( $color ) ? 'style="color:' . $color . '"' : '' ); ?> onMouseOver="this.style.color='<?php echo $color2; ?>'" onMouseOut="this.style.color='<?php echo $color; ?>'"></i>
                                            </div>
                                            <?php
                                    }
                                    break;
                            }
                                ?>
                                <?php if ( ! empty( $title ) ) : ?>
                                    <h4 class="info-title"><?php echo esc_html( $title ); ?></h4>
                                <?php endif; ?>
                                <?php if ( ! empty( $link ) ) : ?>
                            </a>
                        <?php endif; ?>
                <?php if ( ! empty( $text ) ) : ?>
                                <p><?php echo wp_kses_post( html_entity_decode( $text ) ); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                endforeach;
                echo '</div>';
            }// End if().
            endif;
        if ( ! $is_callback ) {
        ?>
            </div>
            <?php
        }

    }

    function get_features_default() {
        return apply_filters(
            'ca_features_default_content', json_encode(
                array(
                    array(
                        'icon_value' => 'fa-wechat',
                        'title'      => esc_html__( 'Responsive', 'calypso' ),
                        'text'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'calypso' ),
                        'link'       => '#',
                        'color'      => '#e91e63',
                    ),
                    array(
                        'icon_value' => 'fa-check',
                        'title'      => esc_html__( 'Quality', 'calypso' ),
                        'text'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'calypso' ),
                        'link'       => '#',
                        'color'      => '#00bcd4',
                    ),
                    array(
                        'icon_value' => 'fa-support',
                        'title'      => esc_html__( 'Support', 'calypso' ),
                        'text'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'calypso' ),
                        'link'       => '#',
                        'color'      => '#4caf50',
                    ),
                )
            )
        );
    }
	

}

new Ca_Features_Section();