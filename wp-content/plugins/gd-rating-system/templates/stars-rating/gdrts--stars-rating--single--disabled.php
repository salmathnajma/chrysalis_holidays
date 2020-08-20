<?php // GDRTS Template: Voting Disabled // ?>

<div class="<?php gdrts_loop()->render()->classes(); ?>">
    <div class="gdrts-inner-wrapper">

        <?php do_action('gdrts-template-rating-block-before'); ?>

        <?php gdrts_loop()->render()->stars(array('allow_rating' => false)); ?>

        <div class="gdrts-rating-text">
            <?php

            if (gdrts_loop()->render()->has_votes()) {
                gdrts_loop()->render()->rating();
            } else {
                _e("No votes yet.", "gd-rating-system");
            }

            ?>
        </div>

        <?php

        gdrts_loop()->json();

        do_action('gdrts-template-rating-block-after');
        do_action('gdrts-template-rating-rich-snippet');

        ?>

    </div>
</div>