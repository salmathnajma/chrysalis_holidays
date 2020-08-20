<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_engine_jsonld_creative_work extends gdrts_rich_snippet_mode_engine_jsonld {
    public $name = 'creative_work';
    public $type = 'CreativeWork';

    public function run() {
        $this->_basic_data();
        $this->_rating_data();

        $_featured_image = $this->_featured_image();

        if ($_featured_image) {
            $this->build['image'] = array(
                '@type' => 'ImageObject',
                'url' => $_featured_image[0],
                'width' => $_featured_image[1]."px",
                'height' => $_featured_image[2]."px"
            );
        }

        $this->build['author'] = $this->_get_author();
        $this->build['publisher'] = $this->_get_publisher();
        $this->build['mainEntityOfPage'] = $this->_get_main_entitiy();

        $_date_published = $this->snippet->item->date_published('c', gdrtsa_rich_snippets()->gmt());
        if ($_date_published) {
            $this->build['datePublished'] = $_date_published;
        }

        $_date_modified = $this->snippet->item->date_modified('c', gdrtsa_rich_snippets()->gmt());
        if ($_date_modified && $_date_published != $_date_modified) {
            $this->build['dateModified'] = $_date_modified;
        }
    }
}
