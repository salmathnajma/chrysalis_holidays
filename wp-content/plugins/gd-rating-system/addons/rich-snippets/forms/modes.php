<input type="hidden" name="gdrts[rich-snippets-mode][nonce]" value="<?php echo wp_create_nonce('gdrts-rich-snippets-mode-'.$_gdrts_id); ?>"/>

<div class="gdrts-metabox-wrapper">
    <div class="gdrts-metabox-wrapper-left">
        <p>
            <label for="gdrts-rich-snippets-modes-switch"><?php _e("Snippet Type", "gd-rating-system"); ?></label>
            <?php d4p_render_select(gdrtsa_admin_rich_snippets()->get_list_snippet_modes(), array('class' => 'widefat', 'selected' => $_gdrts_mode, 'name' => 'gdrts[rich-snippets-mode][mode]', 'id' => 'gdrts-rich-snippets-modes-switch')); ?>
        </p>
    </div>
    <div class="gdrts-metabox-wrapper-right">
        <?php foreach (gdrtsa_rich_snippets()->modes as $mode => $obj) { ?>
            <div class="gdrts-snippet-model gdrts-snippet-model-<?php echo $mode; ?>" style="display: <?php echo $_gdrts_mode == $mode ? 'block' : 'none'; ?>">
                <h4>
                    <?php echo $obj['label']; ?> <span>(<?php echo $obj['label']; ?>)</span>

                    <a title="<?php _e("Information by Schema.org", "gd-rating-system"); ?>" href="https://schema.org/<?php echo $obj['type']; ?>" target="_blank"><i class="dashicons dashicons-editor-code"></i></a>

                    <?php if (isset($obj['google'])) { ?>
                        <a title="<?php _e("Information by Google.com", "gd-rating-system"); ?>" href="<?php echo $obj['google']; ?>" target="_blank"><i class="dashicons dashicons-search"></i></a>
                    <?php } ?>
                </h4>
                <?php do_action('gdrts_rich_snippet_admin_meta_content_load_'.$mode); ?>
            </div>
        <?php } ?>
    </div>
</div>
