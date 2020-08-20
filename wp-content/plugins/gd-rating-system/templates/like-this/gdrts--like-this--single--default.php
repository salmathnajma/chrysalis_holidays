<?php // GDRTS Template: Default // ?>

<div class="<?php gdrts_loop()->render()->classes(); ?>">
    <div class="gdrts-inner-wrapper">

        <?php do_action('gdrts-template-rating-block-before'); ?>

        <?php

        do_action('gdrts-template-like-this-default-before-rating-stars');

        gdrts_loop()->render()->likes();

        do_action('gdrts-template-like-this-default-after-rating-stars');

        ?>

        <?php if (apply_filters('gdrts-template-like-this-default-show-rating-text', true)) { ?>

            <div class="gdrts-rating-text gdrts-text-inline">
                <?php

                do_action('gdrts-template-like-this-default-before-rating-text');

                if (gdrts_loop()->render()->has_votes()) {
                    gdrts_loop()->render()->rating();
                } else {
                    $no_votes = __("Be the first one to like this.", "gd-rating-system");

                    echo apply_filters('gdrts-template-like-this-default-no-votes-message', $no_votes);
                }

                do_action('gdrts-template-like-this-default-after-rating-text');

                ?>
            </div>

        <?php } ?>

        <?php

        if (apply_filters('gdrts-template-like-this-default-show-please-wait', true)) {
            do_action('gdrts-template-like-this-default-before-please-wait');

            gdrts_loop()->please_wait(null, null, 'gdrts-text-inline');

            do_action('gdrts-template-like-this-default-before-please-wait');
        }

        gdrts_loop()->json();
        
        do_action('gdrts-template-rating-block-after');

        ?>

    </div>
</div>