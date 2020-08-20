<?php
/**
 * Custom control for button
 *
 * @package calypso
 */
if ( class_exists( 'WP_Customize_Control' ) ) {

    class Ca_Button_Control extends WP_Customize_Control {

        public $type = 'image_radio_button';

        public $button_text = '';

        public $link = '';

        public $button_class = '';

        public $icon_class = '';

        public function render_content() {
            if ( ! empty( $this->label ) ) {
                echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
            }
            if ( ! empty( $this->button_text ) ) {
    
                $params = ' href="#" ';
                if ( ! empty( $this->link ) ) {
                    $params = ' href="' . esc_url( $this->link ) . '" target="_blank" ';
                }
                echo '<a ' . $params . ' type="button" class="button menu-shortcut ' . esc_attr( $this->button_class ) . '" tabindex="0">';
                if ( ! empty( $this->button_class ) ) {
                    echo '<i class="fa ' . esc_attr( $this->icon_class ) . '" style="margin-right: 10px"></i>';
                }
                    echo esc_html( $this->button_text );
                echo '</a>';
            }
        }

    }
}