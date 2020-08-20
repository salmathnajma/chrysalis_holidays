<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_core_standalone_render {
    public function __construct() { }

    public function stars($args = array()) {
        $defaults = array(
            'stars' => 5,
            'rating' => 0,
            'responsive' => true,
            'style_type' => 'font',
            'style_name' => 'star',
            'style_size' => 32,
            'style_class' => '',
            'font_color_empty' => '#dddddd',
            'font_color_current' => '#dd0000',
            'title' => ''
        );

        $atts = wp_parse_args($args, $defaults);

        if ($atts['style_type'] == 'image') {
            $render = $this->_render_stars_image($atts);
        } else {
            $render = $this->_render_stars_font($atts);
        }

        do_action('gdrts_trigger_enqueue_stars-rating');

        return $render;
    }

    public function badge($args = array()) {
        $defaults = array(
            'rating' => '0.0',
            'style_type' => 'font',
            'style_name' => 'star',
            'style_size' => 140,
            'style_color' => '#b61a20',
            'style_class' => '',
            'title' => '',
            'font_size' => 32,
            'font_color' => '#f5ebce'
        );

        $atts = wp_parse_args($args, $defaults);

        if ($atts['style_type'] == 'image') {
            $render = $this->_render_badge_image($atts);
        } else {
            $render = $this->_render_badge_font($atts);
        }

        do_action('gdrts_trigger_enqueue_stars-rating');

        return $render;
    }

    public function like($args = array()) {
        $defaults = array(
            'votes' => 0,
            'style_type' => 'font',
            'style_name' => 'like',
            'style_size' => 24,
            'style_theme' => 'standard',
            'style_class' => '',
            'labels_liked' => 'Liked',
            'show_votes' => true,
            'title' => ''
        );

        $atts = wp_parse_args($args, $defaults);

        if ($atts['style_type'] == 'image') {
            $render = $this->_render_like_image($atts);
        } else {
            $render = $this->_render_like_font($atts);
        }

        do_action('gdrts_trigger_enqueue_like-this');

        return $render;
    }

    private function _render_stars_image($atts = array()) {
        $current = 100 * ($atts['rating'] / $atts['stars']);

        $render = '<div class="'.$this->_render_stars_classes($atts).'" style="width: '.($atts['stars'] * $atts['style_size']).'px; height: '.$atts['style_size'].'px;">';
            $render.= '<span title="'.$atts['title'].'" class="gdrts-stars-empty" style="background-size: '.$atts['style_size'].'px;">';
                $render.= '<span class="gdrts-stars-current" style="width: '.$current.'%; background-size: '.$atts['style_size'].'px;"></span>';
            $render.= '</span>';
        $render.= '</div>';

        return $render;
    }

    private function _render_stars_font($atts = array()) {
        $current = 100 * ($atts['rating'] / $atts['stars']);
        $thechar = gdrts()->get_font_star_char($atts['style_type'], $atts['style_name']);

        $render = '<div data-responsive="'.($atts['responsive'] ? 1 : 0).'" data-size="'.$atts['style_size'].'" data-max="'.$atts['stars'].'" data-type="'.$atts['style_type'].'" data-name="'.$atts['style_name'].'" data-char="'.$thechar.'" class="'.$this->_render_stars_classes($atts).'" style="height: '.$atts['style_size'].'px;">';
            $render.= '<span title="'.$atts['title'].'" class="gdrts-stars-empty" style="color: '.$atts['font_color_empty'].'; font-size: '.$atts['style_size'].'px; line-height: '.$atts['style_size'].'px;">';
                $render.= '<span class="gdrts-stars-current" style="color: '.$atts['font_color_current'].'; width: '.$current.'%"></span>';
            $render.= '</span>';
        $render.= '</div>';

        return $render;
    }

    private function _render_stars_classes($atts = array()) {
        $list = array(
            'gdrts-custom-stars-block',
            'gdrts-'.$atts['style_type'].'-'.$atts['style_name'],
            'gdrts-stars-length-'.$atts['stars']
        );

        if ($atts['style_type'] == 'image') {
            $list[] = 'gdrts-with-image';
        } else {
            $list[] = 'gdrts-with-fonticon';
            $list[] = 'gdrts-fonticon-'.$atts['style_type'];
        }

        if (!empty($atts['style_class'])) {
            $list = array_merge($list, (array)$atts['style_class']);
        }

        return join(' ', $list);
    }

    private function _render_badge_image($atts = array()) {
        $render = '<div title="'.$atts['title'].'" style="width: '.$atts['style_size'].'px; height: '.$atts['style_size'].'px;" class="gdrts-badge-wrapper gdrts-badge-image gdrts-image-'.$atts['style_name'].' '.$atts['style_class'].'">';
            $render.= '<div style="background-size: '.$atts['style_size'].'px auto; width: '.$atts['style_size'].'px; height: '.$atts['style_size'].'px;" class="gdrts-badge-icon">'.'</div>';
            $render.= '<div class="gdrts-badge-text" style="line-height: '.$atts['style_size'].'px;font-size: '.$atts['font_size'].'px;color: '.$atts['font_color'].';">'.$atts['rating'].'</div>';
        $render.= '</div>';

        return $render;
    }

    private function _render_badge_font($atts = array()) {
        $render = '<div title="'.$atts['title'].'" style="width: '.$atts['style_size'].'px; height: '.$atts['style_size'].'px;" class="gdrts-badge-wrapper gdrts-badge-font gdrts-fonticon-'.$atts['style_type'].' '.$atts['style_class'].'">';
            $render.= '<div class="gdrts-badge-icon" style="color: '.$atts['style_color'].'; font-size: '.$atts['style_size'].'px; line-height: '.$atts['style_size'].'px;">'.gdrts()->get_font_star_char($atts['style_type'], $atts['style_name']).'</div>';
            $render.= '<div class="gdrts-badge-text" style="line-height: '.$atts['style_size'].'px;font-size: '.$atts['font_size'].'px;color: '.$atts['font_color'].';">'.$atts['rating'].'</div>';
        $render.= '</div>';

        return $render;
    }

    private function _render_like_image($atts = array()) {
        $img = '<span class="gdrts-like-image" style="width: '.$atts['style_size'].'px; height: '.$atts['style_size'].'px; background-size: '.$atts['style_size'].'px '.(4 * $atts['style_size']).'px;"></span>';
        $html = '<span class="gdrts-like-this-symbol gdrts-like-symbol" title="'.$atts['labels_liked'].'" data-rating="like">'.$img.'</span>';
        $html.= '<span class="gdrts-like-this-suffix">'.$atts['labels_liked'].'</span>';

        $render = '<div class="'.$this->_render_like_classes($atts).'">';
            $render.= '<div class="gdrts-like" style="font-size: '.$atts['style_size'].'px;">';
                $render.= '<div class="gdrts-like-link">'.$html.'</div>';

                if ($atts['show_votes']) {
                    $render.= '<div class="gdrts-like-count"><span>'.$atts['votes'].'</span></div>';
                }
            $render.= '</div>';
        $render.= '</div>';

        return $render;
    }

    private function _render_like_font($atts = array()) {
        $thechar = gdrts()->get_font_like_chars($atts['style_type'], $atts['style_name']);

        $html = '<span class="gdrts-like-this-symbol gdrts-like-symbol" title="'.$atts['labels_liked'].'" data-rating="like"></span>';
        $html.= '<span class="gdrts-like-this-suffix">'.$atts['labels_liked'].'</span>';

        $render = '<div class="'.$this->_render_like_classes($atts).'" data-type="'.$atts['style_type'].'" data-name="'.$atts['style_name'].'" data-char="'.$thechar['like'].'">';
            $render.= '<div class="gdrts-like" style="font-size: '.$atts['style_size'].'px;">';
                $render.= '<div class="gdrts-like-link">'.$html.'</div>';

                if ($atts['show_votes']) {
                    $render.= '<div class="gdrts-like-count"><span>'.$atts['votes'].'</span></div>';
                }
            $render.= '</div>';
        $render.= '</div>';

        return $render;
    }

    private function _render_like_classes($atts = array()) {
        $list = array(
            'gdrts-custom-like-block',
            'gdrts-likes-theme-'.$atts['style_theme'],
            'gdrts-'.$atts['style_type'].'-'.$atts['style_name']
        );

        if ($atts['style_type'] == 'image') {
            $list[] = 'gdrts-with-image';
        } else {
            $list[] = 'gdrts-with-fonticon';
            $list[] = 'gdrts-fonticon-'.$atts['style_type'];
        }

        if (!empty($atts['style_class'])) {
            $list = array_merge($list, $atts['style_class']);
        }

        return join(' ', $list);
    }
}

function gdrts_render_custom_stars_block($atts = array()) {
    $_stars = new gdrts_core_standalone_render();

    return $_stars->stars($atts);
}

function gdrts_render_custom_star_badge($atts = array()) {
    $_stars = new gdrts_core_standalone_render();

    return $_stars->badge($atts);
}

function gdrts_render_custom_like_block($atts = array()) {
    $_stars = new gdrts_core_standalone_render();

    return $_stars->like($atts);
}
