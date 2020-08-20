<?php // GDRTS Template: Classic // ?>

<div class="<?php gdrts_loop()->render()->classes(); ?>">
    <div class="gdrts-inner-wrapper">

        <?php do_action('gdrts-template-rating-block-before'); ?>

        <?php gdrts_loop()->render()->likes(); ?>

        <div class="gdrts-rating-text gdrts-text-inline">
            <?php

            if (gdrts_loop()->render()->has_votes()) {
                gdrts_loop()->render()->rating();
            } else {
                _e("Be the first one to like this.", "gd-rating-system");
            }

            ?>
        </div>

        <?php

        gdrts_loop()->please_wait(null, null, 'gdrts-text-inline');
        gdrts_loop()->json();
        
        do_action('gdrts-template-rating-block-after');

        ?>

    </div>
</div>