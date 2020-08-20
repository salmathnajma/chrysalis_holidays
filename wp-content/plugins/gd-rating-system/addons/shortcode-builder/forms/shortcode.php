<div id="gdrts-shortcode-block">

</div>

<div class="gdrts-shortcode-copy">
    <button type="button" class="button-primary"><?php _e("Copy to Clipboard", "gd-rating-system"); ?></button>
</div>

<div class="d4p-group d4p-group-arguments" data-shortcode="<?php echo $shortcode['shortcode']; ?>" data-inner="<?php echo isset($shortcode['inner_content']) && $shortcode['inner_content'] ? 'yes' : 'no'; ?>">
    <h3><?php _e("Shortcode Arguments", "gd-rating-system"); ?></h3>
    <div class="d4p-group-inner">
        <div class="gdrts-grid">
        <?php

            $boolean = array(
                'true' => __("Yes", "gd-rating-system"),
                'false' => __("No", "gd-rating-system")
            );

            $i = 0;
            foreach ($shortcode['attrs'] as $id => $field) {
                $field['value'] = isset($field['value']) ? $field['value'] : '';
                $_rule = isset($field['rule']) ? json_encode($field['rule']) : '{}';

                echo '<div class="gdrts-unit half" data-rule=\''.$_rule.'\' data-id="'.$id.'" data-type="'.$field['type'].'" data-name="'.$field['attr'].'" data-default="'.$field['value'].'">';
                echo '<label class="gdrts-attr-ctrl"><input class="gdrts-attribute" type="checkbox" /><span>'.$field['label'].'</span></label>';
                echo '<div class="gdrts-attr-value"><span class="gdrts-sr-only">'.sprintf(__("Value for %s", "gd-rating-system"), $field['label']).'</span>';

                switch ($field['type']) {
                    case 'select':
                        d4p_render_select($field['options'], array('selected' => $field['value'], 'class' => 'widefat'));
                        break;
                    case 'multi':
                        d4p_render_checkradios($field['options'], array('selected' => $field['value'], 'class' => 'widefat'));
                        break;
                    case 'checkbox':
                        d4p_render_select($boolean, array('selected' => $field['value'], 'class' => 'widefat'));
                        break;
                    case 'number':
                        echo '<input type="number" class="widefat" min="0" step="1" value="'.$field['value'].'" />';
                        break;
                    case 'text':
                        echo '<input type="text" class="widefat" value="'.$field['value'].'" />';
                        break;
                    case 'datetime':
                        echo '<input type="text" class="widefat gdrts-datetime-picker" value="'.$field['value'].'" />';
                        break;
                    case 'color':
                        echo '<input type="text" class="widefat gdrts-color-picker" value="#fff'.$field['value'].'" />';
                        break;
                }

                if (isset($field['description'])) {
                    echo '<p class="description">'.$field['description'].'</p>';
                }

                echo '</div></div>';

                $i++;

                if ($i == 2) {
                    echo '</div><div class="gdrts-grid">';
                    $i = 0;
                }
            }

        ?>
        </div>
    </div>
</div>
