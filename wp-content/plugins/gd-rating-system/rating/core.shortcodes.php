<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_core_shortcodes extends d4p_shortcodes_core {
    public $prefix = 'gdrts';
    public $shortcake_title = 'GD Rating System';

    public function init() {
        $this->shortcodes = array(
            'rating_value' => array(
                'name' => __("Rating Value", "gd-rating-system"),
                'atts' => array('type' => 'posts.post', 'id' => 0, 'item_id' => 0,
                                'method' => 'stars-rating', 'value' => 'rating', 'tag' => 'span')
            ),
            'rating_value_auto' => array(
                'name' => __("Rating Value - Auto item", "gd-rating-system"),
                'atts' => array('method' => 'stars-rating', 'value' => 'rating', 'tag' => 'span')
            ),
            'has_voted' => array(
                'name' => __("User Has Voted", "gd-rating-system"),
                'atts' => array('type' => 'posts.post', 'id' => 0, 'item_id' => 0,
                                'method' => 'stars-rating', 'user_id' => 0, 'tag' => 'span')
            ),
            'has_voted_auto' => array(
                'name' => __("User Has Voted - Auto item", "gd-rating-system"),
                'atts' => array('method' => 'stars-rating', 'user_id' => 0, 'tag' => 'span')
            ),
            'has_not_voted' => array(
                'name' => __("User Has Not Voted", "gd-rating-system"),
                'atts' => array('type' => 'posts.post', 'id' => 0, 'item_id' => 0,
                                'method' => 'stars-rating', 'user_id' => 0, 'tag' => 'span')
            ),
            'has_not_voted_auto' => array(
                'name' => __("User Has Not Voted - Auto item", "gd-rating-system"),
                'atts' => array('method' => 'stars-rating', 'user_id' => 0, 'tag' => 'span')
            ),
            'stars_rating' => array(
                'name' => __("Stars Rating", "gd-rating-system"),
                'atts' => $this->_shared_single_attribures() +
                          array('distribution' => '',
                                'style_type' => '', 'style_image_name' => '', 'style_size' => '', 'font_color_empty' => '', 'font_color_current' => '', 'font_color_active' => '', 'style_class' => '')
            ),
            'stars_rating_auto' => array(
                'name' => __("Stars Rating - Auto Item", "gd-rating-system"),
                'atts' => $this->_shared_single_auto_attribures() +
                          array('distribution' => '',
                                'style_type' => '', 'style_image_name' => '', 'style_size' => '', 'font_color_empty' => '', 'font_color_current' => '', 'font_color_active' => '', 'style_class' => '')
            ),
            'stars_rating_list' => array(
                'name' => __("Stars Ratings List", "gd-rating-system"),
                'atts' => $this->_shared_list_attributes() +
                          array('style_type' => 'font', 'style_image_name' => 'star', 'style_size' => 20, 'font_color_empty' => '',
                                'font_color_current' => '', 'font_color_active' => '', 'style_class' => '')
            ),
            'like_this' => array(
                'name' => __("Like This", "gd-rating-system"),
                'atts' => $this->_shared_single_attribures() +
                          array('style_type' => '', 'style_theme' => '', 'style_image_name' => '', 'style_size' => '', 'style_class' => '')
            ),
            'like_this_auto' => array(
                'name' => __("Like This - Auto Item", "gd-rating-system"),
                'atts' => $this->_shared_single_auto_attribures() +
                          array('style_type' => '', 'style_theme' => '', 'style_image_name' => '', 'style_size' => '', 'style_class' => '')
            ),
            'like_this_list' => array(
                'name' => __("Like This List", "gd-rating-system"),
                'atts' => $this->_shared_list_attributes() +
                          array('style_theme' => '', 'style_type' => 'font', 'style_image_name' => 'thumb', 'style_size' => 20, 'style_class' => '')
            )
        );
    }

	private function _shared_list_attributes() {
		return array('type' => 'posts.post', 'class' => '', 'orderby' => 'rating', 'order' => 'DESC', 'limit' => 5,
		             'rating_min' => 0, 'votes_min' => 0, 'template' => 'shortcode',
		             'status' => '', 'post_type' => '', 'terms' => '', 'author' => '');
	}

	private function _shared_single_attribures() {
		return array('title' => '', 'url' => '', 'type' => 'posts.post', 'id' => 0, 'item' => 0, 'item_id' => 0,
		             'class' => '', 'template' => '', 'alignment' => '', 'disable_rating' => false);
	}

	private function _shared_single_auto_attribures() {
		return array('class' => '', 'template' => '', 'alignment' => '', 'disable_rating' => false);
	}

    private function _outside_wrapper($content, $name, $atts, $extra_class = '', $tag = 'div') {
        gdrts()->load_shortcode($name, $atts);

        $render = $this->_wrapper($content, $name, $extra_class, $tag);

        gdrts()->unload_shortcode();

        return $render;
    }

    protected function _atts($code, $atts = array()) {
        $default = apply_filters('gdrts_shortcode_attributes', $this->shortcodes[$code]['atts'], $code);

        $atts = shortcode_atts($default, $atts);

        if (isset($atts['item']) && $atts['item'] > 0) {
            if ($atts['item_id'] == 0) {
                $atts['item_id'] = $atts['item'];

                unset($atts['item']);
            }
        }

        if (gdrts_debug_on()) {
            gdrts()->debug_queue($atts, $code.', shortcode');
        }

        return $atts;
    }

    public function shortcode_has_voted($atts, $content = '') {
        $name = 'has_voted';

        if ($this->in_shortcake_preview($name)) {
            return $this->shortcake_preview($atts, $name);
        }

        $atts = $this->_atts($name, $atts);

        $item = gdrts_get_rating_item($atts);

        if ($item === false) {
            return '';
        }

        $data = gdrts()->convert_method_series_pair($atts['method']);

        if ($item->has_voted($data['method'], $data['series'], $atts['user_id'])) {
            return $this->_outside_wrapper($content, 'has-voted', $atts, '', $atts['tag']);
        }

        return '';
    }

    public function shortcode_has_voted_auto($atts, $content = '') {
        $name = 'has_voted_auto';

        if ($this->in_shortcake_preview($name)) {
            return $this->shortcake_preview($atts, $name);
        }

        $atts = $this->_atts($name, $atts);

        $item = gdrts_get_rating_item_by_post();

        if ($item === false) {
            return '';
        }

        $data = gdrts()->convert_method_series_pair($atts['method']);

        if ($item->has_voted($data['method'], $data['series'], $atts['user_id'])) {
            return $this->_outside_wrapper($content, 'has-voted', $atts, '', $atts['tag']);
        }

        return '';
    }

    public function shortcode_has_not_voted($atts, $content = '') {
        $name = 'has_not_voted';

        if ($this->in_shortcake_preview($name)) {
            return $this->shortcake_preview($atts, $name);
        }

        $atts = $this->_atts($name, $atts);

        $item = gdrts_get_rating_item($atts);

        if ($item === false) {
            return '';
        }

        $data = gdrts()->convert_method_series_pair($atts['method']);

        if (!$item->has_voted($data['method'], $data['series'], $atts['user_id'])) {
            return $this->_outside_wrapper($content, 'has-voted', $atts, '', $atts['tag']);
        }

        return '';
    }

    public function shortcode_has_not_voted_auto($atts, $content = '') {
        $name = 'has_not_voted_auto';

        if ($this->in_shortcake_preview($name)) {
            return $this->shortcake_preview($atts, $name);
        }

        $atts = $this->_atts($name, $atts);

        $item = gdrts_get_rating_item_by_post();

        if ($item === false) {
            return '';
        }

        $data = gdrts()->convert_method_series_pair($atts['method']);

        if (!$item->has_voted($data['method'], $data['series'], $atts['user_id'])) {
            return $this->_outside_wrapper($content, 'has-voted', $atts, '', $atts['tag']);
        }

        return '';
    }

    public function shortcode_rating_value($atts) {
        $name = 'rating_value';

        if ($this->in_shortcake_preview($name)) {
            return $this->shortcake_preview($atts, $name);
        }

        $atts = $this->_atts($name, $atts);

        $item = gdrts_get_rating_item($atts);

        if ($item === false) {
        	return '';
        }

        $value = $item->get_method_value($atts['value'], 0, $atts['method']);

        return $this->_outside_wrapper($value, 'rating-value', $atts, '', $atts['tag']);
    }

    public function shortcode_rating_value_auto($atts) {
        $name = 'rating_value_auto';

        if ($this->in_shortcake_preview($name)) {
            return $this->shortcake_preview($atts, $name);
        }

        $atts = $this->_atts($name, $atts);

        $item = gdrts_get_rating_item_by_post();

        if ($item === false) {
        	return '';
        }

        $value = $item->get_method_value($atts['value'], 0, $atts['method']);

        return $this->_outside_wrapper($value, 'rating-value', $atts, '', $atts['tag']);
    }

    public function shortcode_stars_rating($atts) {
        $name = 'stars_rating';

        if ($this->in_shortcake_preview($name)) {
            return $this->shortcake_preview($atts, $name);
        }

        $atts = $this->_atts($name, $atts);

        gdrts()->load_embed();

        return $this->_outside_wrapper(_gdrts_embed_stars_rating($atts), $name, $atts, $atts['class']);
    }

    public function shortcode_stars_rating_auto($atts) {
        $name = 'stars_rating_auto';

        if ($this->in_shortcake_preview($name)) {
            return $this->shortcake_preview($atts, $name);
        }

        $atts = $this->_atts($name, $atts);

        gdrts()->load_embed();

        return $this->_outside_wrapper(_gdrts_embed_stars_rating_auto($atts), $name, $atts, $atts['class']);
    }

    public function shortcode_stars_rating_list($atts) {
        $name = 'stars_rating_list';

        if ($this->in_shortcake_preview($name)) {
            return $this->shortcake_preview($atts, $name);
        }

        $atts = $this->_atts($name, $atts);
        $atts['source'] = 'shortcode';

        gdrts()->load_embed();

        return $this->_outside_wrapper(_gdrts_embed_stars_rating_list($atts), $name, $atts, $atts['class']);
    }

    public function shortcode_like_this($atts) {
        $name = 'like_this';

        if ($this->in_shortcake_preview($name)) {
            return $this->shortcake_preview($atts, $name);
        }

        $atts = $this->_atts($name, $atts);

        gdrts()->load_embed();

        return $this->_outside_wrapper(_gdrts_embed_like_this($atts), $name, $atts, $atts['class']);
    }

    public function shortcode_like_this_auto($atts) {
        $name = 'like_this_auto';

        if ($this->in_shortcake_preview($name)) {
            return $this->shortcake_preview($atts, $name);
        }

        $atts = $this->_atts($name, $atts);

        gdrts()->load_embed();

        return $this->_outside_wrapper(_gdrts_embed_like_this_auto($atts), $name, $atts, $atts['class']);
    }

    public function shortcode_like_this_list($atts) {
        $name = 'like_this_list';

        if ($this->in_shortcake_preview($name)) {
            return $this->shortcake_preview($atts, $name);
        }

        $atts = $this->_atts($name, $atts);
        $atts['source'] = 'shortcode';

        gdrts()->load_embed();

        return $this->_outside_wrapper(_gdrts_embed_like_this_list($atts), $name, $atts, $atts['class']);
    }
}

global $_gdrts_shortcodes;

$_gdrts_shortcodes = new gdrts_core_shortcodes();
