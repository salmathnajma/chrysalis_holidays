<?php

if (!defined('ABSPATH')) { exit; }

function gdrts_render_grouped_select($values, $args = array(), $attr = array()) {
    $defaults = array(
        'selected' => '', 'name' => '', 'id' => '', 'class' => '', 
        'style' => '', 'multi' => false, 'echo' => true, 'readonly' => false);
    $args = wp_parse_args($args, $defaults);
    extract($args);

    $render = '';
    $attributes = array();
    $selected = (array)$selected;
    $id = d4p_html_id_from_name($name, $id);

    if ($class != '') {
        $attributes[] = 'class="'.$class.'"';
    }

    if ($style != '') {
        $attributes[] = 'style="'.$style.'"';
    }

    if ($multi) {
        $attributes[] = 'multiple';
    }

    if ($readonly) {
        $attributes[] = 'readonly';
    }

    foreach ($attr as $key => $value) {
        $attributes[] = $key.'="'.esc_attr($value).'"';
    }

    $name = $multi ? $name.'[]' : $name;

    if ($id != '') {
        $attributes[] = 'id="'.$id.'"';
    }

    if ($name != '') {
        $attributes[] = 'name="'.$name.'"';
    }

    $render.= '<select '.join(' ', $attributes).'>';
    foreach ($values as $group) {
        $render.= '<optgroup label="'.$group['title'].'">';
        $scope = isset($group['scope']) ? $group['scope'] : '';

        foreach ($group['values'] as $value => $display) {
            $sel = in_array($value, $selected) ? ' selected="selected"' : '';
            $render.= '<option value="'.esc_attr($value).'"'.$sel.' data-scope="'.$scope.'">'.$display.'</option>';
        }
        $render.= '</optgroup>';
    }
    $render.= '</select>';

    if ($echo) {
        echo $render;
    } else {
        return $render;
    }
}
