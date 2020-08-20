<?php

$_list_of_categories = array(
    'GameApplication' => _x("Game", "Rich snippet Software category", "gd-rating-system"),
    'SocialNetworkingApplication' => _x("Social Networking", "Rich snippet Software category", "gd-rating-system"),
    'TravelApplication' => _x("Travel", "Rich snippet Software category", "gd-rating-system"),
    'ShoppingApplication' => _x("Shopping", "Rich snippet Software category", "gd-rating-system"),
    'SportsApplication' => _x("Sports", "Rich snippet Software category", "gd-rating-system"),
    'LifestyleApplication' => _x("Lifestyle", "Rich snippet Software category", "gd-rating-system"),
    'BusinessApplication' => _x("Business", "Rich snippet Software category", "gd-rating-system"),
    'DesignApplication' => _x("Design", "Rich snippet Software category", "gd-rating-system"),
    'DeveloperApplication' => _x("Developer", "Rich snippet Software category", "gd-rating-system"),
    'DriverApplication' => _x("Driver", "Rich snippet Software category", "gd-rating-system"),
    'EducationalApplication' => _x("Educational", "Rich snippet Software category", "gd-rating-system"),
    'HealthApplication' => _x("Health", "Rich snippet Software category", "gd-rating-system"),
    'FinanceApplication' => _x("Finance", "Rich snippet Software category", "gd-rating-system"),
    'SecurityApplication' => _x("Security", "Rich snippet Software category", "gd-rating-system"),
    'BrowserApplication' => _x("Browser", "Rich snippet Software category", "gd-rating-system"),
    'CommunicationApplication' => _x("Communication", "Rich snippet Software category", "gd-rating-system"),
    'DesktopEnhancementApplication' => _x("Desktop Enhancement", "Rich snippet Software category", "gd-rating-system"),
    'EntertainmentApplication' => _x("Entertainment", "Rich snippet Software category", "gd-rating-system"),
    'MultimediaApplication' => _x("Multimedia", "Rich snippet Software category", "gd-rating-system"),
    'HomeApplication' => _x("Home", "Rich snippet Software category", "gd-rating-system"),
    'UtilitiesApplication' => _x("Utilities", "Rich snippet Software category", "gd-rating-system"),
    'ReferenceApplication' => _x("Reference", "Rich snippet Software category", "gd-rating-system")
);

$_aggregate_offer = $this->data['aggregate_offer'];

if (empty($_aggregate_offer)) {
    $_aggregate_offer = array(
        'currency' => '',
        'low' => '',
        'high' => '',
        'offers' => ''
    );
}

?>
<div class="gdrts-metabox-row __p-zero-margin __with-margin">
    <div class="__column-half __on-left">
        <p>
            <label for="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-rating"><?php _e("Rating", "gd-rating-system"); ?></label>
            <?php d4p_render_select(gdrtsa_admin_rich_snippets()->get_list_include_ratings(), array('class' => 'widefat', 'selected' => $this->data['rating'], 'name' => 'gdrts[rich-snippets-mode]['.$this->name.'][rating]', 'id' => 'gdrts-rich-snippets-mode-'.$this->name.'-rating')); ?>
        </p>
    </div>
    <div class="__column-half __on-right">
        <p>
            <label for="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-offer"><?php _e("Offers", "gd-rating-system"); ?></label>
            <?php d4p_render_select(array('none' => __("None", "gd-rating-system"), 'offers' => __("Offers", "gd-rating-system"), 'aggregate_offer' => __("Aggregate Offer", "gd-rating-system")), array('class' => 'widefat gdrts-rich-snippets-mode-block-switch', 'selected' => $this->data['offer'], 'name' => 'gdrts[rich-snippets-mode]['.$this->name.'][offer]', 'id' => 'gdrts-rich-snippets-mode-'.$this->name.'-offer')); ?>
        </p>
    </div>
</div>
<h5><?php _e("Basic Information", "gd-rating-system"); ?></h5>
<div class="gdrts-metabox-row __p-zero-margin">
    <div class="__column-full">
        <p>
            <label for="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-description"><?php _e("Description", "gd-rating-system"); ?>
                <strong>(<?php _e("required", "gd-rating-system"); ?>)</strong></label>
            <input id="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-description" name="gdrts[rich-snippets-mode][<?php echo $this->name; ?>][description]" class="widefat" type="text" value="<?php echo esc_attr($this->data['description']); ?>"/>
        </p>
    </div>
</div>
<div class="gdrts-metabox-row __p-zero-margin __with-margin-bottom">
    <div class="__column-full">
        <p>
            <label for="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-name"><?php _e("Name", "gd-rating-system"); ?> (<?php _e("optional", "gd-rating-system"); ?>)</label>
            <input id="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-name" name="gdrts[rich-snippets-mode][<?php echo $this->name; ?>][name]" class="widefat" type="text" value="<?php echo esc_attr($this->data['name']); ?>"/>
        </p>
    </div>
</div>
<div class="gdrts-metabox-row __p-zero-margin __with-margin-bottom">
    <div class="__column-half __on-left">
        <p>
            <label for="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-os"><?php _e("Operating System", "gd-rating-system"); ?> (<?php _e("required", "gd-rating-system"); ?>)</label>
            <input id="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-os" name="gdrts[rich-snippets-mode][<?php echo $this->name; ?>][os]" class="widefat" type="text" value="<?php echo esc_attr($this->data['os']); ?>"/>
        </p>
    </div>
    <div class="__column-half __on-right">
        <p>
            <label for="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-category"><?php _e("Category", "gd-rating-system"); ?></label>
            <?php d4p_render_select($_list_of_categories, array('class' => 'widefat', 'selected' => $this->data['category'], 'name' => 'gdrts[rich-snippets-mode]['.$this->name.'][category]', 'id' => 'gdrts-rich-snippets-mode-'.$this->name.'-category')); ?>
        </p>
    </div>
</div>

<div class="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-offer" id="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-offer-aggregate_offer" style="display: <?php echo $this->data['offer'] == 'aggregate_offer' ? 'block' : 'none'; ?>;">
    <h5><?php _e("Aggregate offer", "gd-rating-system"); ?></h5>
    <div class="gdrts-metabox-row __p-zero-margin __with-margin-bottom">
        <div class="__column-third __on-left">
            <p>
                <label for="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-aggregate_offer-currency"><?php _e("Currency", "gd-rating-system"); ?></label>
                <?php d4p_render_select(gdrts_snippets_schema()->list_currencies(), array('class' => 'widefat', 'selected' => $_aggregate_offer['currency'], 'name' => 'gdrts[rich-snippets-mode]['.$this->name.'][aggregate_offer][currency]', 'id' => 'gdrts-rich-snippets-mode-'.$this->name.'-aggregate_offer-currency')); ?>
            </p>
        </div>
        <div class="__column-two-ninths __on-middle">
            <p>
                <label for="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-aggregate_offer-low"><?php _e("Price From", "gd-rating-system"); ?></label>
                <input id="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-aggregate_offer-low" name="gdrts[rich-snippets-mode][<?php echo $this->name; ?>][aggregate_offer][low]" class="widefat" type="text" value="<?php echo esc_attr($_aggregate_offer['low']); ?>"/>
            </p>
        </div>
        <div class="__column-two-ninths __on-middle">
            <p>
                <label for="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-aggregate_offer-high"><?php _e("Price To", "gd-rating-system"); ?></label>
                <input id="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-aggregate_offer-high" name="gdrts[rich-snippets-mode][<?php echo $this->name; ?>][aggregate_offer][high]" class="widefat" type="text" value="<?php echo esc_attr($_aggregate_offer['high']); ?>"/>
            </p>
        </div>
        <div class="__column-two-ninths __on-right">
            <p>
                <label for="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-aggregate_offer-offers"><?php _e("Number of Offers", "gd-rating-system"); ?></label>
                <input id="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-aggregate_offer-offers" name="gdrts[rich-snippets-mode][<?php echo $this->name; ?>][aggregate_offer][offers]" class="widefat" type="text" value="<?php echo esc_attr($_aggregate_offer['offers']); ?>"/>
            </p>
        </div>
    </div>
</div>
<div class="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-offer" id="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-offer-offers" style="display: <?php echo $this->data['offer'] == 'offers' ? 'block' : 'none'; ?>;">
    <h5><?php _e("One or more offers", "gd-rating-system"); ?></h5>
    <?php

    gdrts_rich_snippets_render_single_offer_block($this, 'gdrts-offer-wrapper-hidden');

    $i = 1;
    foreach ($this->data['offers'] as $offer) {
        gdrts_rich_snippets_render_single_offer_block($this, '', $i++, $offer);
    }

    ?>

    <p>
        <input data-repkey="%GDRTSOFFER%" data-nextid="<?php echo $i; ?>" id="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-offers-new-offer" class="gdrts-rich-snippet-block-add-new button-primary widefat" type="button" value="<?php _e("Add new Offer", "gd-rating-system"); ?>" style="width: auto;"/>
    </p>
</div>
