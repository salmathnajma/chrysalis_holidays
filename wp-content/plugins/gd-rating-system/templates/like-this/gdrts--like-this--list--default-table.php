<?php // GDRTS Template: Default, Table // ?>

<div class="<?php gdrts_loop()->render()->classes(); ?>">
    <?php

    $thumbnail_show = apply_filters('gdrts-template-like-this-default-table-show-thumbnail', true);
    $thumbnail_size = apply_filters('gdrts-template-like-this-default-table-thumbnail-size', array(32, 32));

    ?>

    <div class="gdrts-inner-wrapper gdrts-grid-wrapper">
        <table class="gdrts-grid-minimal">
            <thead>
                <tr>
                    <th class="gdrts-grid-order"></th>
                    <?php if ($thumbnail_show) { ?><th class="gdrts-grid-thumbnail"></th><?php } ?>
                    <th class="gdrts-grid-item"><?php _e("Item", "gd-rating-system"); ?></th>
                    <th class="gdrts-grid-likes"><?php _e("Likes", "gd-rating-system"); ?></th>
                </tr>
            </thead>
            <tbody>

<?php

if (gdrts_list()->have_items()) :
    while (gdrts_list()->have_items()) :
        gdrts_list()->the_item();

        $thumbnail = '';
        if ($thumbnail_show) {
            $thumbnail = gdrts_list()->item()->thumbnail($thumbnail_size);
        }

?>

<tr>
    <td class="gdrts-grid-order"><?php echo gdrts_list()->item()->ordinal; ?></td>
    <?php if ($thumbnail_show) { ?>
        <td class="gdrts-grid-thumbnail"><?php echo $thumbnail; ?></td>
    <?php } ?>
    
    <td class="gdrts-grid-item"><a href="<?php echo gdrts_list()->item()->url(); ?>"><?php echo gdrts_list()->item()->title(); ?></a></td>
    <td class="gdrts-grid-likes"><?php gdrts_loop()->render()->likes(); ?></td>
</tr>

<?php

    endwhile;

else :

?>

<tr>
    <td colspan="5"><?php _e("No items found.", "gd-rating-system"); ?></td>
</tr>

<?php

endif;

?>

            </tbody>
        </table>

        <?php gdrts_list()->json(); ?>

    </div>
</div>
