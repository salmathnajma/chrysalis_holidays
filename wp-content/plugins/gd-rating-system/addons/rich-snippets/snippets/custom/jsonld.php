<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_engine_jsonld_custom extends gdrts_rich_snippet_mode_engine_jsonld {
    public $name = 'custom';
    public $type = 'Thing';

    public function run() {
        $_snippet_name = !empty($this->snippet->data['name']) ? $this->snippet->data['name'] : $this->type;

        $this->build = array(
            '@context' => 'http://schema.org/',
            '@type' => $_snippet_name,
            'url' => $this->snippet->item->url(),
            'name' => $this->snippet->item->title()
        );

        $this->_rating_data();

        if (in_array('image', $this->snippet->data['features'])) {
            $_featured_image = $this->_featured_image();

            if ($_featured_image) {
                $this->build['image'] = array(
                    '@type' => 'ImageObject',
                    'url' => $_featured_image[0],
                    'width' => $_featured_image[1]."px",
                    'height' => $_featured_image[2]."px"
                );
            }
        }

        if (in_array('author', $this->snippet->data['features'])) {
            $this->build['author'] = $this->_get_author();
        }

        if (in_array('publisher', $this->snippet->data['features'])) {
            $this->build['publisher'] = $this->_get_publisher();
        }

        $_date_published = $this->snippet->item->date_published('c', gdrtsa_rich_snippets()->gmt());
        $_date_modified = $this->snippet->item->date_modified('c', gdrtsa_rich_snippets()->gmt());

        if (in_array('published', $this->snippet->data['features'])) {
            if ($_date_published) {
                $this->build['datePublished'] = $_date_published;
            }
        }

        if (in_array('modified', $this->snippet->data['features'])) {
            if ($_date_modified && $_date_published != $_date_modified) {
                $this->build['dateModified'] = $_date_modified;
            }
        }
    }
}