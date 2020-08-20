<?php // GDRTS Template: Shortcode Lite // ?>

<div class="<?php gdrts_loop()->render()->classes(); ?>">
    <div class="gdrts-inner-wrapper gdrts-grid-wrapper">
        <table class="gdrts-grid-minimal">
            <thead>
                <tr>
                    <th class="gdrts-grid-order"></th>
                    <th class="gdrts-grid-item"><?php _e("Item", "gd-rating-system"); ?></th>
                    <th class="gdrts-grid-likes"><?php _e("Likes", "gd-rating-system"); ?></th>
                </tr>
            </thead>
            <tbody>

<?php

if (gdrts_list()->have_items()) :
    while (gdrts_list()->have_items()) :
        gdrts_list()->the_item();

?>

<tr>
    <td class="gdrts-grid-order"><?php echo gdrts_list()->item()->ordinal; ?></td>
    <td class="gdrts-grid-item"><a href="<?php echo gdrts_list()->item()->url(); ?>"><?php echo gdrts_list()->item()->title(); ?></a></td>
    <td class="gdrts-grid-likes"><?php gdrts_loop()->value('rating'); ?></td>
</tr>

<?php

    endwhile;

else :

?>

<tr><td colspan="4"><?php _e("No items found.", "gd-rating-system"); ?></td></tr>

<?php

endif;

?>

            </tbody>
        </table>

        <?php gdrts_list()->json(); ?>

    </div>
</div>
