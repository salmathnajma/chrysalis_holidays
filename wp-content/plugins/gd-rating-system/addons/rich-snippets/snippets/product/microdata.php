<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_engine_microdata_product extends gdrts_rich_snippet_mode_engine_microdata {
    public $name = 'product';
    public $type = 'Product';

    public function run() {
        $this->build['root'] = array(
            'tag' => 'span',
            'itemscope' => true,
            'itemtype' => 'http://schema.org/'.$this->type,
            'items' => array(
                'name' => array('tag' => 'meta', 'itemprop' => 'name', 'content' => $this->snippet->item->title()),
                'url' => array('tag' => 'meta', 'itemprop' => 'url', 'content' => $this->snippet->item->url()),
                'brand' => array('tag' => 'meta', 'itemprop' => 'brand', 'content' => $this->snippet->data['brand']),
                'description' => array('tag' => 'meta', 'itemprop' => 'description', 'content' => $this->snippet->data['description'])
            )
        );

        $_featured_image = $this->_featured_image();

        if ($_featured_image) {
            $this->build['root']['items']['image'] = array('tag' => 'span', 'itemscope' => true, 'itemprop' => 'image', 'itemtype' => 'https://schema.org/ImageObject', 'items' => array(
                'url' => array('tag' => 'meta', 'itemprop' => 'url', 'content' => $_featured_image[0]),
                'width' => array('tag' => 'meta', 'itemprop' => 'width', 'content' => $_featured_image[1]."px"),
                'height' => array('tag' => 'meta', 'itemprop' => 'height', 'content' => $_featured_image[2]."px")
            ));
        }

        foreach (array('sku', 'mpn', 'gtin8', 'gtin13', 'gtin14') as $key) {
            $value = $this->snippet->data[$key];

            if (!empty($value)) {
                $this->build['root']['items'][$key] = array('tag' => 'meta', 'itemprop' => $key, 'content' => $value);
            }
        }

        if ($this->snippet->data['offer'] == 'aggregate_offer') {
            $this->build['root']['items']['offers'] = $this->_get_aggregate_offer();
        } else if ($this->snippet->data['offer'] == 'offers') {
            $this->build['root']['items'] = array_merge($this->build['root']['items'], $this->_get_offers_list());
        }

        $this->_rating_data();
    }

    protected function _get_aggregate_offer() {
        $offer = array(
            'tag' => 'span', 'itemscope' => true, 'itemprop' => 'Offers', 'itemtype' => 'http://schema.org/AggregateOffer',
            'items' => array()
        );

        $map = array(
            'currency' => 'priceCurrency',
            'low' => 'lowPrice',
            'high' => 'highPrice',
            'offers' => 'offerCount'
        );

        foreach ($map as $key => $ld) {
            if (!empty($this->snippet->data['aggregate_offer'][$key])) {
                $offer['items'][$ld] = array('tag' => 'meta', 'itemprop' => $ld, 'content' => $this->snippet->data['aggregate_offer'][$key]);
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
                'tag' => 'span', 'itemscope' => true, 'itemprop' => 'Offers', 'itemtype' => 'http://schema.org/Offer',
                'items' => array()
            );

            foreach ($map as $key => $ld) {
                if ($key == 'price') {
                    $o['items'][$ld] = array('tag' => 'meta', 'itemprop' => $ld, 'content' => $offer[$key]);
                } else if (!empty($offer[$key])) {
                    $mod = in_array($key, array('condition', 'availability')) ? 'http://schema.org/'.$offer[$key] : $offer[$key];
                    $o['items'][$ld] = array('tag' => 'meta', 'itemprop' => $ld, 'content' => $mod);
                }
            }

            if (!empty($offer['seller'])) {
                $o['items']['seller'] = array(
                    'tag' => 'span', 'itemscope' => true, 'itemprop' => 'seller', 'itemtype' => 'http://schema.org/Organization',
                    'items' => array(
                        'seller' => array('tag' => 'meta', 'itemprop' => 'name', 'content' => $offer['seller'])
                    )
                );

                if (!empty($offer['seller_url'])) {
                    $o['items']['seller']['items']['url'] = array('tag' => 'meta', 'itemprop' => 'url', 'content' => $offer['seller_url']);
                }
            }

            $offers[] = $o;
        }

        return $offers;
    }
}
