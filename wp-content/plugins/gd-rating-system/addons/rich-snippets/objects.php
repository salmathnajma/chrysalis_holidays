<?php

if (!defined('ABSPATH')) {
    exit;
}

abstract class gdrts_rich_snippet_mode_snippet {
    public $name = '';
    public $type = '';
    public $label = '';

    public $defaults = array();
    public $data = null;
    public $base = null;

    /** @var gdrts_rating_item */
    public $item = null;

    public $engines = array(
        'jsonld',
        'microdata'
    );

    public function __construct() {
        add_action('gdrts_rich_snippets_run_snippet_mode_'.$this->name, array($this, 'snippet'), 10, 2);
    }

    public function snippet($item, $base) {
        $this->item = $item;
        $this->base = $base;

        $this->defaults['rating'] = gdrtsa_rich_snippets()->get($this->item->name.'_snippet_rating');

        $this->meta_data_load();

        $engine = $this->_get_engine();
        $engine->run();
        $engine->show();

        gdrtsa_rich_snippets()->inserted = true;
    }

    public function meta_data_load() {
        $data = $this->item->get_snippet_value($this->name);

        $this->data = wp_parse_args($data, $this->defaults);
    }

    public function get_rating() {
        $split = explode('::', $this->base['methods']['rating'], 2);

        $method = $split[0];
        $series = count($split) == 2 ? $split[1] : '';

        return gdrts_get_summary_rating($this->item, $method, $series);
    }

    /** @return gdrts_rich_snippet_mode_engine */
    abstract protected function _get_engine();
}

abstract class gdrts_rich_snippet_mode_engine {
    public $name = '';
    public $type = '';

    /** @var gdrts_rich_snippet_mode_snippet */
    protected $snippet;

    protected $build = array();

    public function __construct($snippet) {
        $this->snippet = $snippet;
    }

    protected function _publisher_logo() {
        $logo = gdrtsa_rich_snippets()->get('snippet_organization_logo');

        if ($logo > 0) {
            return wp_get_attachment_image_src($logo, 'full');
        }

        return false;
    }

    protected function _featured_image() {
        if (has_post_thumbnail($this->snippet->item->id)) {
            $id = get_post_thumbnail_id($this->snippet->item->id);

            return wp_get_attachment_image_src($id, 'full');
        }

        return false;
    }

    public function get_item() {
        return $this->snippet->item;
    }

    abstract public function run();

    abstract public function show();
}

abstract class gdrts_rich_snippet_mode_engine_jsonld extends gdrts_rich_snippet_mode_engine {
    public function run() {
        $this->_basic_data();
        $this->_rating_data();
    }

    public function show() {
        $this->_validate();

        $build = apply_filters('gdrts_rich_snippet_jsonld_build_'.$this->name, $this->build, $this);

        if (is_array($build) && !empty($build)) {
            echo $this->_render($build);
        }
    }

    protected function _validate() {
        if (isset($this->build['headline']) && !empty($this->build['headline']) && mb_strlen($this->build['headline']) > 110) {
            $this->build['headline'] = mb_substr($this->build['headline'], 0, 107).'...';
        }
    }

    protected function _basic_data() {
        $this->build = array(
            '@context' => 'http://schema.org/',
            '@type' => $this->type,
            'url' => $this->snippet->item->url(),
            'name' => $this->snippet->item->title()
        );

        if (isset($this->snippet->data['headline']) && !empty($this->snippet->data['headline'])) {
            $this->build['headline'] = $this->snippet->data['headline'];
        }
    }

    protected function _rating_data() {
        if ($this->snippet->data['rating'] == 'rating' || $this->snippet->data['rating'] == 'both') {
            $_rating = $this->_get_aggregated_rating();

            if (!empty($_rating)) {
                $this->build['aggregateRating'] = $_rating;
            }
        }
    }

    protected function _render($snippet) {
        $out = '<script type="application/ld+json">';
        $out .= json_encode($snippet, JSON_PRETTY_PRINT);
        $out .= '</script>';

        return $out;
    }

    protected function _get_aggregated_rating() {
        $rating = $this->snippet->get_rating();

        if (!empty($rating) && isset($rating['count']) && $rating['count'] > 0) {
            return array(
                '@type' => 'aggregateRating',
                'ratingValue' => $rating['value'],
                'bestRating' => $rating['best'],
                'ratingCount' => $rating['count']
            );
        }

        return array();
    }

    protected function _get_publisher() {
        $name = gdrtsa_rich_snippets()->get('snippet_organization_name');

        $publisher = array(
            '@type' => 'Organization',
            'name' => empty($name) ? get_bloginfo('blogname') : $name,
            'url' => site_url()
        );

        $image = $this->_publisher_logo();

        if ($image) {
            $publisher['logo'] = array(
                '@type' => 'ImageObject',
                'url' => $image[0],
                'width' => $image[1]."px",
                'height' => $image[2]."px"
            );
        }

        return $publisher;
    }

    protected function _get_author() {
        return array(
            '@type' => 'Person',
            'name' => get_the_author_meta('display_name', $this->snippet->item->data->post_author),
            'url' => get_author_posts_url($this->snippet->item->data->post_author)
        );
    }

    protected function _get_main_entitiy() {
        return array(
            '@type' => 'WebPage',
            '@id' => $this->snippet->item->url()
        );
    }

    protected function _get_featured_image() {
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

    protected function _get_aggregate_offer() {
        $offer = array(
            '@type' => 'AggregateOffer'
        );

        $map = array(
            'currency' => 'priceCurrency',
            'low' => 'lowPrice',
            'high' => 'highPrice',
            'offers' => 'offerCount'
        );

        foreach ($map as $key => $ld) {
            if (!empty($this->snippet->data['aggregate_offer'][$key])) {
                $offer[$ld] = $this->snippet->data['aggregate_offer'][$key];
            }
        }

        return $offer;
    }

    protected function _get_offers_list() {
        $offers = array();

        $map = array(
            'currency' => 'priceCurrency',
            'price' => 'price',
            'name' => 'name',
            'valid' => 'priceValidUntil',
            'condition' => 'itemCondition',
            'availability' => 'availability'
        );

        foreach ($this->snippet->data['offers'] as $offer) {
            $o = array(
                '@type' => 'Offer'
            );

            foreach ($map as $key => $ld) {
                if ($key == 'price') {
                    $o[$ld] = $offer[$key];
                } else if (!empty($offer[$key])) {
                    $o[$ld] = in_array($key, array('condition', 'availability')) ? 'http://schema.org/'.$offer[$key] : $offer[$key];
                }
            }

            if (!empty($offer['seller'])) {
                $o['seller'] = array(
                    '@type' => 'Organization',
                    'name' => $offer['seller']
                );

                if (!empty($offer['seller_url'])) {
                    $o['seller']['url'] = $offer['seller_url'];
                }
            }

            $offers[] = $o;
        }

        return $offers;
    }
}

abstract class gdrts_rich_snippet_mode_engine_microdata extends gdrts_rich_snippet_mode_engine {
    public function run() {
        $this->_basic_data();
        $this->_rating_data();
    }

    public function show() {
        $this->_validate();

        $build = apply_filters('gdrts_rich_snippet_microdata_build_'.$this->name, $this->build, $this);

        echo $this->_render_span($build['root']);
    }

    protected function _validate() {
        if (isset($this->build['root']['items']['headline']) && !empty($this->build['root']['items']['headline']['content']) && mb_strlen($this->build['root']['items']['headline']['content']) > 110) {
            $this->build['root']['items']['headline']['content'] = mb_substr($this->build['root']['items']['headline']['content'], 0, 107).'...';
        }
    }

    protected function _basic_data() {
        $this->build['root'] = array(
            'tag' => 'span',
            'itemscope' => true,
            'itemtype' => 'http://schema.org/'.$this->type,
            'items' => array(
                'name' => array('tag' => 'meta', 'itemprop' => 'name', 'content' => $this->snippet->item->title()),
                'url' => array('tag' => 'meta', 'itemprop' => 'url', 'content' => $this->snippet->item->url())
            )
        );

        if (isset($this->snippet->data['headline']) && !empty($this->snippet->data['headline'])) {
            $this->build['root']['items']['headline'] = array('tag' => 'meta', 'itemprop' => 'headline', 'content' => $this->snippet->data['headline']);
        }
    }

    protected function _rating_data() {
        if ($this->snippet->data['rating'] == 'rating' || $this->snippet->data['rating'] == 'both') {
            $_rating = $this->_get_aggregated_rating();

            if (!empty($_rating)) {
                $this->build['root']['items']['aggregateRating'] = $_rating;
            }
        }
    }

    private function _get_aggregated_rating() {
        $rating = $this->snippet->get_rating();

        if (!empty($rating)) {
            return array(
                'tag' => 'span', 'itemscope' => true, 'itemprop' => 'aggregateRating', 'itemtype' => 'http://schema.org/AggregateRating',
                'items' => array(
                    'value' => array('tag' => 'meta', 'itemprop' => 'ratingValue', 'content' => $rating['value']),
                    'best' => array('tag' => 'meta', 'itemprop' => 'bestRating', 'content' => $rating['best']),
                    'count' => array('tag' => 'meta', 'itemprop' => 'ratingCount', 'content' => $rating['count'])
                )
            );
        }

        return array();
    }

    private function _render_meta($data) {
        if (isset($data['content'])) {
            return '<meta itemprop="'.$data['itemprop'].'" content="'.$data['content'].'" />';
        } else {
            $build = '<meta';

            if ($data['itemscope']) {
                $build .= ' itemscope';
            }

            unset($data['tag']);
            unset($data['itemscope']);

            foreach ($data as $tag => $value) {
                $build .= ' '.$tag.'="'.$value.'"';
            }

            $build .= '/>';

            return $build;
        }
    }

    private function _render_span($data) {
        $out = '<span itemscope itemtype="'.$data['itemtype'].'"';

        if (isset($data['itemprop'])) {
            $out .= ' itemprop="'.$data['itemprop'].'"';
        }

        $out .= '>';

        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                if (!isset($item['tag']) || $item['tag'] == 'span') {
                    $out .= $this->_render_span($item);
                } else {
                    $out .= $this->_render_meta($item);
                }
            }
        }

        $out .= '</span>';

        return $out;
    }

    protected function _get_publisher() {
        $name = gdrtsa_rich_snippets()->get('snippet_organization_name');

        $publisher = array('tag' => 'span', 'itemscope' => true, 'itemprop' => 'publisher', 'itemtype' => 'http://schema.org/Organization', 'items' => array(
            'name' => array('tag' => 'meta', 'itemprop' => 'name', 'content' => empty($name) ? get_bloginfo('blogname') : $name),
            'url' => array('tag' => 'meta', 'itemprop' => 'url', 'content' => site_url())
        ));

        $image = $this->_publisher_logo();

        if ($image) {
            $publisher['items'][] = array('tag' => 'span', 'itemscope' => true, 'itemprop' => 'logo', 'itemtype' => 'https://schema.org/ImageObject', 'items' => array(
                'url' => array('tag' => 'meta', 'itemprop' => 'url', 'content' => $image[0]),
                'width' => array('tag' => 'meta', 'itemprop' => 'width', 'content' => $image[1]."px"),
                'height' => array('tag' => 'meta', 'itemprop' => 'height', 'content' => $image[2]."px")
            ));
        }

        return $publisher;
    }

    protected function _get_author() {
        return array('tag' => 'span', 'itemscope' => true, 'itemprop' => 'author', 'itemtype' => 'http://schema.org/Person', 'items' => array(
            'name' => array('tag' => 'meta', 'itemprop' => 'name', 'content' => get_the_author_meta('display_name', $this->snippet->item->data->post_author)),
            'url' => array('tag' => 'meta', 'itemprop' => 'url', 'content' => get_author_posts_url($this->snippet->item->data->post_author))
        ));
    }

    protected function _get_main_entitiy() {
        return array('tag' => 'meta', 'itemscope' => true, 'itemType' => 'https://schema.org/WebPage', 'itemprop' => 'mainEntityOfPage', 'itemId' => $this->snippet->item->url());
    }
}

abstract class gdrts_rich_snippet_mode_admin {
    public $name = '';
    public $defaults = array();

    /** @var gdrts_rating_item */
    public $item = null;
    public $base = null;
    public $data = null;

    public function __construct() {
        add_action('gdrts_rich_snippet_admin_meta_content_init', array($this, 'meta_content_init'), 10, 2);
        add_action('gdrts_rich_snippet_admin_meta_content_load_'.$this->name, array($this, 'meta_content_load'));

        add_filter('gdrts_rich_snippet_admin_meta_content_save', array($this, 'meta_content_save'), 10, 3);
    }

    public function meta_content_init($item, $base = null) {
        $this->item = $item;
        $this->base = $base;

        $this->defaults['rating'] = gdrtsa_admin_rich_snippets()->get($this->item->name.'_snippet_rating');
    }

    public function meta_content_load() {
        $this->meta_data_load();

        include(gdrtsa_rich_snippets()->modes[$this->name]['path'].'meta.php');
    }

    public function meta_data_load() {
        $data = $this->item->get_snippet_value($this->name);

        $this->data = wp_parse_args($data, $this->defaults);
    }

    /** @param $item gdrts_rating_item */
    public function meta_data_save($item) {
        foreach ($this->data as $name => $value) {
            $key = 'rich-snippets_mode_'.$this->name.'_'.$name;

            if ($value != $this->defaults[$name]) {
                $item->set($key, $value);
            } else {
                $item->un_set($key);
            }
        }

        return $item;
    }

    protected function _save_offers($data) {
        $this->data['offers'] = array();

        if (isset($data[$this->name]['offers']) && is_array($data[$this->name]['offers']) && !empty($data[$this->name]['offers'])) {
            $empty = array(
                'currency' => '',
                'price' => '',
                'name' => '',
                'valid' => '',
                'condition' => '',
                'availability' => '',
                'seller' => '',
                'seller_url' => ''
            );

            foreach ($data[$this->name]['offers'] as $id => $offer) {
                $key = intval($id);

                if ($key > 0) {
                    $offer = array_map('d4p_sanitize_basic', $offer);
                    $offer = array_map('trim', $offer);
                    $offer = shortcode_atts($empty, $offer);

                    $clean = array();
                    foreach ($offer as $key => $value) {
                        if ($key == 'price') {
                            if ($value !== '') {
                                $clean[$key] = abs(floatval($value));
                            }
                        } else if (!empty($value)) {
                            $clean[$key] = $value;
                        }
                    }

                    if (!empty($clean)) {
                        $this->data['offers'][] = $clean;
                    }
                }
            }
        }
    }

    protected function _save_aggregated_offer($data) {
        $this->data['aggregate_offer'] = array();

        if (isset($data[$this->name]['aggregate_offer']) && is_array($data[$this->name]['aggregate_offer']) && !empty($data[$this->name]['aggregate_offer'])) {
            $empty = array(
                'currency' => '',
                'low' => '',
                'high' => '',
                'offers' => ''
            );

            $this->data['aggregate_offer'] = array_map('d4p_sanitize_basic', $data[$this->name]['aggregate_offer']);
            $this->data['aggregate_offer'] = shortcode_atts($empty, $this->data['aggregate_offer']);
            $this->data['aggregate_offer'] = array_filter($this->data['aggregate_offer']);
        }
    }

    abstract public function meta_content_save($item, $data, $mode);
}
