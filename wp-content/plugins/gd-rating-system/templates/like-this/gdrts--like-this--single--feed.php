<?php // GDRTS Template: Feed // ?>

<div class="<?php gdrts_loop()->render()->classes(); ?>">
    <div class="gdrts-inner-wrapper">

        <?php do_action('gdrts-template-rating-block-before'); ?>

        <div class="gdrts-rating-text gdrts-text-inline">
            <?php

            if (gdrts_loop()->render()->has_votes()) {
                gdrts_loop()->render()->text_feed();
            } else {
                _e("No likes yet.", "gd-rating-system");
            }

            ?>
        </div>

        <?php do_action('gdrts-template-rating-block-after'); ?>

    </div>
</div>