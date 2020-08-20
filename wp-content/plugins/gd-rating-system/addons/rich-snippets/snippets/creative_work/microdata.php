<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_engine_microdata_creative_work extends gdrts_rich_snippet_mode_engine_microdata {
    public $name = 'creative_work';
    public $type = 'CreativeWork';

    public function run() {
        $this->_basic_data();
        $this->_rating_data();

        $_featured_image = $this->_featured_image();

        if ($_featured_image) {
            $this->build['root']['items']['image'] = array('tag' => 'span', 'itemscope' => true, 'itemprop' => 'image', 'itemtype' => 'https://schema.org/ImageObject', 'items' => array(
                'url' => array('tag' => 'meta', 'itemprop' => 'url', 'content' => $_featured_image[0]),
                'width' => array('tag' => 'meta', 'itemprop' => 'width', 'content' => $_featured_image[1]."px"),
                'height' => array('tag' => 'meta', 'itemprop' => 'height', 'content' => $_featured_image[2]."px")
            ));
        }

        $this->build['root']['items']['author'] = $this->_get_author();
        $this->build['root']['items']['publisher'] = $this->_get_publisher();
        $this->build['root']['items']['mainEntityOfPage'] = $this->_get_main_entitiy();

        $_date_published = $this->snippet->item->date_published('c', gdrtsa_rich_snippets()->gmt());
        if ($_date_published) {
            $this->build['root']['items']['published'] = array('tag' => 'meta', 'itemprop' => 'datePublished', 'content' => $_date_published);
        }

        $_date_modified = $this->snippet->item->date_modified('c', gdrtsa_rich_snippets()->gmt());
        if ($_date_modified && $_date_published != $_date_modified) {
            $this->build['root']['items']['modified'] = array('tag' => 'meta', 'itemprop' => 'dateModified', 'content' => $_date_modified);
        }
    }
}
