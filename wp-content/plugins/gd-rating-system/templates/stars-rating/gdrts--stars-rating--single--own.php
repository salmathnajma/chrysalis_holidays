<?php // GDRTS Template: Own Rating Visible // ?>

<div class="<?php gdrts_loop()->render()->classes(); ?>">
    <div class="gdrts-inner-wrapper">

        <?php do_action('gdrts-template-rating-block-before'); ?>

        <?php gdrts_loop()->render()->stars(array('show_rating' => 'own')); ?>

        <div class="gdrts-rating-user">
            <?php

            if (gdrts_loop()->user()->has_voted()) {
                gdrts_loop()->render()->vote_from_user();
            } else {
                _e("You have not voted yet.", "gd-rating-system");
            }

            ?>
        </div>

        <?php

        if (gdrts_loop()->is_save()) {

        ?>

            <div class="gdrts-rating-thanks">
                <?php _e("Thanks for your vote!", "gd-rating-system"); ?>
            </div>

        <?php

        }

        gdrts_loop()->please_wait();
        gdrts_loop()->json();

        do_action('gdrts-template-rating-block-after');
        do_action('gdrts-template-rating-rich-snippet');

        ?>

    </div>
</div>
