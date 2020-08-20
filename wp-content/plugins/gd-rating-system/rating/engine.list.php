<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_engine_list extends gdrts_base_engine {
    protected $_engine = 'list';
    protected $_filter = null;
    protected $_sql;

    public $items;
    public $current_item = -1;
    public $items_count = 0;
    public $in_the_loop = false;

    public function have_items() {
        if ($this->current_item + 1 < $this->items_count) {
            return true;
        } else if ($this->current_item + 1 == $this->items_count && $this->items_count > 0 ) {
            $this->rewind_items();
        }

        $this->in_the_loop = false;

        return false;
    }

    public function rewind_items() {
        $this->current_item = -1;

        if ($this->items_count > 0) {
            $this->_item = $this->items[0];

            gdrts()->set_item($this->_item);

            $this->update_method();
        }
    }

    public function the_item() {
        $this->in_the_loop = true;

        $this->next_item();
    }

    public function next_item() {
        $this->current_item++;

        $this->_item = $this->items[$this->current_item];

        gdrts()->set_item($this->_item);

        $this->update_method();
    }

    public function update_method() {
        switch ($this->_args['method']) {
            case 'stars-rating':
                gdrtsm_stars_rating()->update_list_item();
                break;
            case 'like-this':
                gdrtsm_like_this()->update_list_item();
                break;
            default:
                do_action('gdrts_loop_list_method_'.$this->_args['method'].'_update');
                break;
        }
    }

    public function render($args = array(), $method = array()) {
        $this->loop($args, $method);

        if ($this->abort) {
            return '';
        }

        $templates = apply_filters('gdrts_render_list_templates_pre', array(), $this->_args['entity'], $this->_args['name']);

        $_active_method = $this->_args['method'];

        if (empty($templates)) {
            switch ($_active_method) {
                case 'stars-rating':
                    $templates = gdrtsm_stars_rating()->loop()->templates_list($this->_args['entity'], $this->_args['name']);
                    break;
                case 'like-this':
                    $templates = gdrtsm_like_this()->loop()->templates_list($this->_args['entity'], $this->_args['name']);
                    break;
                default:
                    $templates = apply_filters('gdrts_render_list_templates_'.$this->_args['method'], array(), $this->_args['entity'], $this->_args['name']);
                    break;
            }
        }

        $templates = apply_filters('gdrts_render_list_templates', $templates, $this->_args['entity'], $this->_args['name']);

        $this->items = gdrts_query()->run($this->_args);
        $this->_sql = gdrts_query()->sql;

        $this->items_count = !is_null($this->items) && is_array($this->items) ? count($this->items) : 0;
        $this->current_item = -1;
        $this->in_the_loop = false;

        if ($this->items_count == 0) {
            $this->items = array();
        }

        if (gdrts_debug_on()) {
            gdrts()->debug_queue($this->_sql, 'rating list, sql');
        }

        $render = gdrts()->render_template($templates, 'list');

        if ($render !== false && !empty($render)) {
            do_action('gdrts_trigger_enqueue_'.$_active_method);
        }

        $this->do_loop_status(false);

        if ($this->_args['echo']) {
            echo $render;
        }

        return $render;
    }

    public function loop($args = array(), $method = array()) {
        $this->abort = false;

        $defaults = apply_filters('gdrts_list_block_args_defaults', array(
            'echo' => false, 
            'entity' => null, 
            'name' => null,
            'method' => 'stars-rating',
            'series' => null
        ));

        $this->_args = wp_parse_args($args, $defaults);

        $this->process_variants();

        $this->_args = apply_filters('gdrts_list_block_args_ready', $this->_args);

        if (gdrts_is_method_loaded($this->_args['method'])) {
            $this->do_loop_status();

            $this->_user = new gdrts_core_user();

            switch ($this->_args['method']) {
                case 'stars-rating':
                    gdrtsm_stars_rating()->prepare_loop_list($method, $this->_args, $this->_filter);
                    break;
                case 'like-this':
                    gdrtsm_like_this()->prepare_loop_list($method, $this->_args, $this->_filter);
                    break;
                default:
                    do_action('gdrts_loop_list_method_'.$this->_args['method'].'_prepare', $method, $this->_args, $this->_filter);
                    break;
            }

            $this->_args = apply_filters('gdrts_list_block_args_loop', $this->_args);
        } else {
            $this->abort = true;
        }
    }

    public function json() {
        $data = apply_filters('gdrts_loop_list_json_data', array(), $this->_args['method']);

        echo '<script class="gdrts-rating-data" type="application/json">';
        echo json_encode($data);
        echo '</script>';
    }

    private function process_variants() {
        $variant = $this->args('variant', array());

        $this->_filter = null;

        if (!empty($variant)) {
            if (in_array('type', $variant)) {
                if (!empty(gdrts()->page()->post_type)) {
                    $this->_args['entity'] = 'posts';
                    $this->_args['name'] = gdrts()->page()->post_type;
                } else {
                    if (in_array('hide', $variant)) {
                        $this->abort = true;
                    }
                }
            }

            if (in_array('term', $variant)) {
                $this->_args['object']['terms'] = gdrts()->page()->terms_children_ids();
            }

            if (in_array('rule', $variant)) {
                $this->_filter = array(
                    'terms' => gdrts()->page()->terms_hierarchy_ids()
                );
            }
        }
    }
}

global $_gdrts_engine_list;
$_gdrts_engine_list = new gdrts_engine_list();

function gdrts_list() {
    global $_gdrts_engine_list;
    return $_gdrts_engine_list;
}
