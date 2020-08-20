<?php // GDRTS Template: With Likes // ?>

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

        <?php if (gdrts_loop()->render()->has_votes()) { ?>

        <div class="gdrts-rating-users-list">
            <?php

                _e("Liked by: ", "gd-rating-system");

                $_list_users = gdrts_single()->item()->users_who_voted('like-this', null, array('limit' => 10));

                gdrts_loop()->render()->list_users($_list_users);

            ?>
        </div>

        <?php } ?>

        <?php

        gdrts_loop()->please_wait(null, null, 'gdrts-text-inline');
        gdrts_loop()->json();
        
        do_action('gdrts-template-rating-block-after');

        ?>

    </div>
</div>