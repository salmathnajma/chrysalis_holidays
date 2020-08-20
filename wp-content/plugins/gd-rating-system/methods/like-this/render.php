<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_render_single_like_this extends gdrts_method_render_single {
    public function owner() {
        return gdrtsm_like_this();
    }

    public function classes($extra = '', $echo = true) {
        $classes = apply_filters('gdrts_render_single_like_this_args_classes', $this->get_classes($extra), $this);
        $classes = apply_filters('gdrts_like_this_loop_single_classes', $classes);

        $render = join(' ', $classes);

        if ($echo) {
            echo $render;
        } else {
            return $render;
        }
    }

    public function rating($atts = array(), $echo = true) {
        $_rating = $this->value('rating', false);
        $_user_has_voted = $this->owner()->user()->has_voted();

        if ($_user_has_voted) {
            if ($_rating == 1) {
                $likes = __("You like this.", "gd-rating-system");
            } else {
                $_rating_mod = $_rating - 1;

                $likes = sprintf(_n("You and %s other like this.", "You and %s others like this.", $_rating_mod, "gd-rating-system"), $this->owner()->calculate_compact_votes($_rating_mod));
            }
        } else {
            $likes = sprintf(_n("%s person likes this.", "%s people like this.", $_rating, "gd-rating-system"), $this->owner()->calculate_compact_votes($_rating));
        }

        $defaults = array(
            'before' => '',
            'after' => '',
            'likes' => $likes
        );

        $atts = apply_filters('gdrts_render_single_like_this_args_rating', 
            wp_parse_args($atts, $defaults), $this, $_rating, $_user_has_voted);

        $render = $atts['before'].$atts['likes'].$atts['after'];

        if ($echo) {
            echo $render;
        } else {
            return $render;
        }
    }

    public function text_feed($atts = array(), $echo = true) {
        $_rating = $this->value('rating', false);

        $defaults = array(
            'before' => '', 'after' => '', 
            'likes' => sprintf(_n("%s person likes this.", "%s people like this.", $_rating, "gd-rating-system"), $this->owner()->calculate_compact_votes($_rating))
        );

        $atts = apply_filters('gdrts_render_single_like_this_args_text_feed', 
            wp_parse_args($atts, $defaults), $this, $_rating);

        $render = $atts['before'].$atts['likes'].$atts['after'];

        if ($echo) {
            echo $render;
        } else {
            return $render;
        }
    }

    public function likes($atts = array(), $echo = true) {
        $defaults = array(
            'allow_rating' => true,
            'show_votes' => true,
            'labels' => null
        );

        $atts = apply_filters('gdrts_render_single_like_this_args_likes', wp_parse_args($atts, $defaults), $this);

        switch ($this->owner()->args('style_type')) {
            default:
            case 'font':
                $render = $this->_render_likes_font($atts);
                break;
            case 'text':
                $render = $this->_render_likes_text($atts);
                break;
            case 'image':
                $render = $this->_render_likes_image($atts);
                break;
        }

        if ($echo) {
            echo $render;
        } else {
            return $render;
        }
    }

    public function list_users($input, $atts = array(), $echo = true) {
        $defaults = array('type' => 'plain', 'sep' => ', ', 'show_url' => true, 
            'show_name' => true, 'show_avatar' => true, 'avatar_size' => 16, 
            'class_user' => 'gdrts_user_single', 'class_wrapper' => 'gdrts_users_lists', 
            'show_more' => __(" and %s more...", "gd-rating-system"));

        $args = apply_filters('gdrts_render_single_like_this_args_list_users', wp_parse_args($atts, $defaults), $this);

        $items = array();
        $users = gdrts_prepare_list_of_users($input['list'], $args['show_avatar'], $args['avatar_size']);

        foreach ($users as $user) {
            $tag = $args['type'] == 'list' ? 'li' : 'span';

            $item = '<'.$tag.' class="'.$args['class_user'].'">';

            if ($args['show_url']) {
                $item.= '<a href="'.$user['url'].'">';
            }

            if ($args['show_avatar']) {
                $item.= $user['avatar'];
            }

            if ($args['show_name']) {
                $item.= '<span>'.$user['name'].'</span>';
            }

            if ($args['show_url']) {
                $item.= '</a>';
            }

            $item.= '</'.$tag.'>';

            $items[] = $item;
        }

        $render = '<div class="'.$args['class_wrapper'].'">';

        if ($args['type'] == 'list') {
            $render.= '<ul>';
        }

        $render.= join($args['sep'], $items);

        if ($args['type'] == 'list') {
            $render.= '</ul>';
        }

        if ($input['total'] > $input['count']) {
            $render.= '<span class="gdrts-show-more">'.sprintf($args['show_more'], $input['total'] - $input['count']).'</span>';
        }

        $render.= '</div>';

        if ($echo) {
            echo $render;
        } else {
            return $render;
        }
    }

    protected function _render_classes($active = true, $extras_classes = array()) {
        $list = array(
            'gdrts-rating-element',
            'gdrts-like-this',
            'gdrts-block-like',
            $active ? 'gdrts-state-active' : 'gdrts-state-inactive',
            'gdrts-likes-theme-'.$this->owner()->args('style_theme'),
            'gdrts-'.$this->owner()->args('style_type').'-'.$this->owner()->args('style_name')
        );

        if ($this->owner()->args('style_type') == 'image') {
            $list[] = 'gdrts-with-image';
        } else if ($this->owner()->args('style_type') == 'text') {
            $list[] = 'gdrts-with-text';
        } else {
            $list[] = 'gdrts-with-fonticon';
            $list[] = 'gdrts-fonticon-'.$this->owner()->args('style_type');
        }

        if (gdrts_single()->is_loop_save()) {
            $list[] = 'gdrts-loop-saving';
        }

        if (!empty($extras_classes)) {
            $list = array_merge($list, $extras_classes);
        }

        $list = apply_filters('gdrts_render_single_like_this_args_likes_classes', $list, $this);

        return join(' ', $list);
    }

    protected function _render_likes_text($atts) {
        $active = $atts['allow_rating'] && $this->owner()->calc('allowed') && $this->owner()->calc('open');
        $labels = is_null($atts['labels']) ? (array)$this->owner()->args('labels') : $atts['labels'];

        $extra = $this->owner()->user()->has_voted() ? ' gdrts-like-hover' : '';

        if ($this->owner()->user()->has_voted()) {
            $html = '<span class="gdrts-like-this-suffix gdrts-clear-symbol" data-rating="clear">'.$labels['liked'].'</span>';
        } else {
            $html = '<span class="gdrts-like-this-suffix gdrts-like-symbol" data-rating="like">'.$labels['like'].'</span>';
        }
        
        $render = '<div class="'.$this->_render_classes($active).'">';
            if ($active) {
                $render.= $this->_accessibility_block($labels);
            }

            $render.= '<div class="gdrts-like" style="font-size: '.$this->owner()->args('style_size').'px;">';
                $render.= '<div class="gdrts-like-link'.$extra.'">'.$html.'</div>';

                if ($atts['show_votes']) {
                    $render.= '<div class="gdrts-like-count"><span>'.$this->owner()->calc('votes_display').'</span></div>';
                }
            $render.= '</div>';
        $render.= '</div>';

        return $render;
    }

    protected function _render_likes_font($atts) {
        $active = $atts['allow_rating'] && $this->owner()->calc('allowed') && $this->owner()->calc('open');
        $labels = is_null($atts['labels']) ? (array)$this->owner()->args('labels') : $atts['labels'];

        $extra = '';

        if ($this->owner()->user()->has_voted()) {
            $extra.= ' gdrts-like-hover';

            $html = '<span class="gdrts-like-this-symbol gdrts-liked-symbol"></span>';
            $html.= '<span class="gdrts-like-this-symbol gdrts-clear-symbol" style="display: none;" title="'.$labels['unlike'].'" data-rating="clear"></span>';
        } else {
            $html = '<span class="gdrts-like-this-symbol gdrts-like-symbol" title="'.$labels['like'].'" data-rating="like"></span>';
        }

        $html.= '<span class="gdrts-like-this-suffix">'.($this->owner()->user()->has_voted() ? $labels['liked'] : $labels['like']).'</span>';

        $render = '<div class="'.$this->_render_classes($active).'">';
            if ($active) {
                $render.= $this->_accessibility_block($labels);
            }

            $render.= '<div class="gdrts-like" style="font-size: '.$this->owner()->args('style_size').'px;">';
                $render.= '<div class="gdrts-like-link'.$extra.'">'.$html.'</div>';

                if ($atts['show_votes']) {
                    $render.= '<div class="gdrts-like-count"><span>'.$this->owner()->calc('votes_display').'</span></div>';
                }
            $render.= '</div>';
        $render.= '</div>';

        return $render;
    }

    protected function _render_likes_image($atts) {
        $size = $this->owner()->args('style_size');

        $img = '<span class="gdrts-like-image" style="width: '.$size.'px; height: '.$size.'px; background-size: '.$size.'px '.(4 * $size).'px;"></span>';

        $active = $atts['allow_rating'] && $this->owner()->calc('allowed') && $this->owner()->calc('open');
        $labels = is_null($atts['labels']) ? (array)$this->owner()->args('labels') : $atts['labels'];

        $extra = '';

        if ($this->owner()->user()->has_voted()) {
            $extra.= ' gdrts-like-hover';

            $html = '<span class="gdrts-like-this-symbol gdrts-liked-symbol">'.$img.'</span>';
            $html.= '<span class="gdrts-like-this-symbol gdrts-clear-symbol" style="display: none;" title="'.$labels['unlike'].'" data-rating="clear">'.$img.'</span>';
        } else {
            $html = '<span class="gdrts-like-this-symbol gdrts-like-symbol" title="'.$labels['like'].'" data-rating="like">'.$img.'</span>';
        }

        $html.= '<span class="gdrts-like-this-suffix">'.$labels['like'].'</span>';

        $render = '<div class="'.$this->_render_classes($active).'">';
            if ($active) {
                $render.= $this->_accessibility_block($labels);
            }

            $render.= '<div class="gdrts-like" style="font-size: '.$this->owner()->args('style_size').'px;">';
                $render.= '<div class="gdrts-like-link'.$extra.'">'.$html.'</div>';

                if ($atts['show_votes']) {
                    $render.= '<div class="gdrts-like-count"><span>'.$this->owner()->calc('votes_display').'</span></div>';
                }
            $render.= '</div>';
        $render.= '</div>';

        return $render;
    }

    protected function _accessibility_block($labels) {
        $render = '<div class="gdrts-sr-only"><label class="gdrts-sr-label">'.__("Do you like this?", "gd-rating-system").'</label>';

        if ($this->owner()->user()->has_voted()) {
            $render.= '<button class="gdrts-sr-button" data-rating="clear">'.$labels['unlike'].'</button>';
        } else {
            $render.= '<button class="gdrts-sr-button" data-rating="like">'.$labels['like'].'</button>';
        }

        $render.= '</div>';

        return $render;
    }
}

class gdrts_render_list_like_this extends gdrts_method_render_list {
    public function owner() {
        return gdrtsm_like_this();
    }

    public function classes($extra = '', $echo = true) {
        $classes = apply_filters('gdrts_render_list_like_this_args_classes', $this->get_classes($extra), $this);
        $classes = apply_filters('gdrts_like_this_loop_list_classes', $classes);

        $render = join(' ', $classes);

        if ($echo) {
            echo $render;
        } else {
            return $render;
        }
    }

    public function rating($atts = array(), $echo = true) {
        $_rating = $this->value('rating', false);

        $defaults = array(
            'before' => '', 'after' => '', 
            'likes' => sprintf(_n("%s person likes this.", "%s people like this.", $_rating, "gd-rating-system"), $this->owner()->calculate_compact_votes($_rating))
        );

        $atts = apply_filters('gdrts_render_list_like_this_args_rating', 
            wp_parse_args($atts, $defaults), $this, $_rating);

        $render = $atts['before'].$atts['likes'].$atts['after'];

        if ($echo) {
            echo $render;
        } else {
            return $render;
        }
    }

    public function likes($atts = array(), $echo = true) {
        $defaults = array(
            'show_votes' => true,
            'labels' => null
        );

        $atts = apply_filters('gdrts_render_list_like_this_args_likes', wp_parse_args($atts, $defaults), $this);

        switch ($this->owner()->args('style_type')) {
            default:
            case 'font':
                $render = $this->_render_likes_font($atts);
                break;
            case 'image':
                $render = $this->_render_likes_image($atts);
                break;
        }

        if ($echo) {
            echo $render;
        } else {
            return $render;
        }
    }

    protected function _render_classes($extras_classes = array()) {
        $list = array(
            'gdrts-rating-element',
            'gdrts-like-this',
            'gdrts-block-like',
            'gdrts-state-inactive',
            'gdrts-likes-theme-'.$this->owner()->args('style_theme'),
            'gdrts-'.$this->owner()->args('style_type').'-'.$this->owner()->args('style_name')
        );

        if ($this->owner()->args('style_type') == 'image') {
            $list[] = 'gdrts-with-image';
        } else {
            $list[] = 'gdrts-with-fonticon';
            $list[] = 'gdrts-fonticon-'.$this->owner()->args('style_type');
        }

        if (!empty($extras_classes)) {
            $list = array_merge($list, $extras_classes);
        }

        $list = apply_filters('gdrts_render_list_like_this_args_likes_classes', $list, $this);

        return join(' ', $list);
    }

    protected function _render_likes_font($atts) {
        $labels = is_null($atts['labels']) ? (array)$this->owner()->args('labels') : $atts['labels'];

        $html = '<span class="gdrts-like-this-symbol gdrts-like-symbol" title="'.$labels['like'].'" data-rating="like"></span>';
        $html.= '<span class="gdrts-like-this-suffix">'.$labels['like'].'</span>';

        $render = '<div class="'.$this->_render_classes().'">';
            $render.= '<div class="gdrts-like" style="font-size: '.$this->owner()->args('style_size').'px;">';
                $render.= '<div class="gdrts-like-link">'.$html.'</div>';

                if ($atts['show_votes']) {
                    $render.= '<div class="gdrts-like-count"><span>'.$this->owner()->calc('votes_display').'</span></div>';
                }
            $render.= '</div>';
        $render.= '</div>';

        return $render;
    }

    protected function _render_likes_image($atts) {
        $size = $this->owner()->args('style_size');

        $img = '<span class="gdrts-like-image" style="width: '.$size.'px; height: '.$size.'px; background-size: '.$size.'px '.(4 * $size).'px;"></span>';

        $labels = is_null($atts['labels']) ? (array)$this->owner()->args('labels') : $atts['labels'];

        $html = '<span class="gdrts-like-this-symbol gdrts-like-symbol" title="'.$labels['like'].'" data-rating="like">'.$img.'</span>';
        $html.= '<span class="gdrts-like-this-suffix">'.$labels['like'].'</span>';

        $render = '<div class="'.$this->_render_classes().'">';
            $render.= '<div class="gdrts-like" style="font-size: '.$this->owner()->args('style_size').'px;">';
                $render.= '<div class="gdrts-like-link">'.$html.'</div>';

                if ($atts['show_votes']) {
                    $render.= '<div class="gdrts-like-count"><span>'.$this->owner()->calc('votes_display').'</span></div>';
                }
            $render.= '</div>';
        $render.= '</div>';

        return $render;
    }
}
