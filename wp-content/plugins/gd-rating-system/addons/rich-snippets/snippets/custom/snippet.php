<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_snippet_custom extends gdrts_rich_snippet_mode_snippet {
    public $name = 'custom';
    public $type = 'Custom';
    public $label = 'Custom';

    public $defaults = array(
        'rating' => 'both',
        'name' => '',
        'features' => array()
    );

    public function snippet($item, $base) {
        $this->item = $item;
        $this->base = $base;

        $this->defaults['rating'] = gdrtsa_rich_snippets()->get($this->item->name.'_snippet_rating');
        $this->defaults['name'] = gdrtsa_rich_snippets()->get($this->item->name.'_snippet_custom_type_name');
        $this->defaults['features'] = gdrtsa_rich_snippets()->get($this->item->name.'_snippet_custom_type_features');

        $this->meta_data_load();

        $engine = $this->_get_engine();
        $engine->run();
        $engine->show();

        gdrtsa_rich_snippets()->inserted = true;
    }

    protected function _get_engine() {
        if ($this->base['display'] == 'jsonld') {
            return new gdrts_rich_snippet_mode_engine_jsonld_custom($this);
        }

        return null;
    }
}

new gdrts_rich_snippet_mode_snippet_custom();
