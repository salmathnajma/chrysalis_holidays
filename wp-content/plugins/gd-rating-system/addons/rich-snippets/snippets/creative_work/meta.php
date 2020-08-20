<div class="gdrts-metabox-row __p-zero-margin __with-margin">
    <div class="__column-half">
        <p>
            <label for="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-rating"><?php _e("Rating", "gd-rating-system"); ?></label>
            <?php d4p_render_select(gdrtsa_admin_rich_snippets()->get_list_include_ratings(), array('class' => 'widefat', 'selected' => $this->data['rating'], 'name' => 'gdrts[rich-snippets-mode]['.$this->name.'][rating]', 'id' => 'gdrts-rich-snippets-mode-'.$this->name.'-rating')); ?>
        </p>
    </div>
</div>
<h5><?php _e("Extra Information", "gd-rating-system"); ?></h5>
<div class="gdrts-metabox-row __p-zero-margin __with-margin">
    <div class="__column-full">
        <p>
            <label for="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-headline"><?php _e("Headline", "gd-rating-system"); ?> (<?php _e("optional, maximum 110 characters", "gd-rating-system"); ?>)</label>
            <input id="gdrts-rich-snippets-mode-<?php echo $this->name; ?>-headline" name="gdrts[rich-snippets-mode][<?php echo $this->name; ?>][headline]" class="widefat" type="text" value="<?php echo esc_attr($this->data['headline']); ?>"/>
        </p>
    </div>
</div>
