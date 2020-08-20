<?php

if (!defined('ABSPATH')) {
    exit;
}

function gdrts_rich_snippets_render_single_offer_block($_this, $class = '', $id = '%GDRTSOFFER%', $data = array()) {
    $defaults = array(
        'currency' => '',
        'price' => '',
        'name' => '',
        'valid' => '',
        'condition' => '',
        'availability' => '',
        'seller' => '',
        'seller_url' => ''
    );

    $data = wp_parse_args($data, $defaults);

    ?>
    <div class="gdrts-offer-wrapper gdrts-rich-snippet-block-item <?php echo $class; ?>">
        <div class="gdrts-metabox-row __p-zero-margin">
            <div class="__column-third __on-left">
                <p>
                    <label for="gdrts-rich-snippets-mode-<?php echo $_this->name; ?>-offers-<?php echo $id; ?>-currency"><?php _e("Currency", "gd-rating-system"); ?></label>
                    <?php d4p_render_select(gdrts_snippets_schema()->list_currencies(), array('class' => 'widefat', 'selected' => $data['currency'], 'name' => 'gdrts[rich-snippets-mode]['.$_this->name.'][offers]['.$id.'][currency]', 'id' => 'gdrts-rich-snippets-mode-'.$_this->name.'-offers-'.$id.'-currency')); ?>
                </p>
            </div>
            <div class="__column-third __on-middle">
                <p>
                    <label for="gdrts-rich-snippets-mode-<?php echo $_this->name; ?>-offers-<?php echo $id; ?>-price"><?php _e("Price", "gd-rating-system"); ?></label>
                    <input id="gdrts-rich-snippets-mode-<?php echo $_this->name; ?>-offers-<?php echo $id; ?>-price" name="gdrts[rich-snippets-mode][<?php echo $_this->name; ?>][offers][<?php echo $id; ?>][price]" class="widefat" type="text" value="<?php echo esc_attr($data['price']); ?>"/>
                </p>
            </div>
            <div class="__column-third __on-right">
                <p>
                    <label for="gdrts-rich-snippets-mode-<?php echo $_this->name; ?>-offers-<?php echo $id; ?>-name"><?php _e("Name", "gd-rating-system"); ?>
                        <strong>(<?php _e("optional", "gd-rating-system"); ?>)</strong></label>
                    <input id="gdrts-rich-snippets-mode-<?php echo $_this->name; ?>-offers-<?php echo $id; ?>-name" name="gdrts[rich-snippets-mode][<?php echo $_this->name; ?>][offers][<?php echo $id; ?>][name]" class="widefat" type="text" value="<?php echo esc_attr($data['name']); ?>"/>
                </p>
            </div>
        </div>
        <div class="gdrts-metabox-row __p-zero-margin">
            <div class="__column-third __on-left">
                <p>
                    <label for="gdrts-rich-snippets-mode-<?php echo $_this->name; ?>-offers-<?php echo $id; ?>-seller"><?php _e("Seller", "gd-rating-system"); ?></label>
                    <input id="gdrts-rich-snippets-mode-<?php echo $_this->name; ?>-offers-<?php echo $id; ?>-seller" name="gdrts[rich-snippets-mode][<?php echo $_this->name; ?>][offers][<?php echo $id; ?>][seller]" class="widefat" type="text" value="<?php echo esc_attr($data['seller']); ?>"/>
                </p>
            </div>
            <div class="__column-two-thirds __on-right">
                <p>
                    <label for="gdrts-rich-snippets-mode-<?php echo $_this->name; ?>-offers-<?php echo $id; ?>-seller_url"><?php _e("Seller URL", "gd-rating-system"); ?></label>
                    <input id="gdrts-rich-snippets-mode-<?php echo $_this->name; ?>-offers-<?php echo $id; ?>-seller_url" name="gdrts[rich-snippets-mode][<?php echo $_this->name; ?>][offers][<?php echo $id; ?>][seller_url]" class="widefat" type="text" value="<?php echo esc_attr($data['seller_url']); ?>"/>
                </p>
            </div>
        </div>
        <div class="gdrts-metabox-row __p-zero-margin __with-margin-bottom">
            <div class="__column-third __on-left">
                <p>
                    <label for="gdrts-rich-snippets-mode-<?php echo $_this->name; ?>-offers-<?php echo $id; ?>-valid"><?php _e("Valid Until", "gd-rating-system"); ?>
                        <strong>(<?php _e("optional", "gd-rating-system"); ?>)</strong></label>
                    <input id="gdrts-rich-snippets-mode-<?php echo $_this->name; ?>-offers-<?php echo $id; ?>-valid" name="gdrts[rich-snippets-mode][<?php echo $_this->name; ?>][offers][<?php echo $id; ?>][valid]" class="widefat gdrts-field-is-date" type="text" value="<?php echo esc_attr($data['valid']); ?>"/>
                </p>
            </div>
            <div class="__column-sixth __on-middle">
                <p>
                    <label for="gdrts-rich-snippets-mode-<?php echo $_this->name; ?>-offers-<?php echo $id; ?>-condition"><?php _e("Condition", "gd-rating-system"); ?></label>
                    <?php d4p_render_select(gdrts_snippets_schema()->list_conditions(), array('class' => 'widefat', 'selected' => $data['condition'], 'name' => 'gdrts[rich-snippets-mode]['.$_this->name.'][offers]['.$id.'][condition]', 'id' => 'gdrts-rich-snippets-mode-'.$_this->name.'-offers-'.$id.'-condition')); ?>
                </p>
            </div>
            <div class="__column-sixth __on-middle">
                <p>
                    <label for="gdrts-rich-snippets-mode-<?php echo $_this->name; ?>-offers-<?php echo $id; ?>-availability"><?php _e("Availability", "gd-rating-system"); ?></label>
                    <?php d4p_render_select(gdrts_snippets_schema()->list_availabilities(), array('class' => 'widefat', 'selected' => $data['availability'], 'name' => 'gdrts[rich-snippets-mode]['.$_this->name.'][offers]['.$id.'][availability]', 'id' => 'gdrts-rich-snippets-mode-'.$_this->name.'-offers-'.$id.'-availability')); ?>
                </p>
            </div>
            <div class="__column-third __on-right">
                <p>
                    <label for="gdrts-rich-snippets-mode-<?php echo $_this->name; ?>-offers-<?php echo $id; ?>-remove"><?php _e("Not available anymore?", "gd-rating-system"); ?></label>
                    <input id="gdrts-rich-snippets-mode-<?php echo $_this->name; ?>-offers-<?php echo $id; ?>-remove" class="button-secondary widefat gdrts-rich-snippet-block-remove" type="button" value="<?php _e("Remove this offer", "gd-rating-system"); ?>"/>
                </p>
            </div>
        </div>
    </div>
<?php }
