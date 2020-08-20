<?php

function gdrts_pretty_print_microdata_snippet($snippet) {
    $level = 2;
    $indent = 0;
    $move = 0;

    $pretty = array();

    $snippet = str_replace('/><', '/>%%SPLIT%%<', $snippet);
    $snippet = str_replace('"><', '">%%SPLIT%%<', $snippet);
    $snippet = str_replace('n><', 'n>%%SPLIT%%<', $snippet);
    $snippet = explode("%%SPLIT%%", $snippet);

    foreach ($snippet as $el) {
        if (substr($el, 0, 5) == '<span') {
            $move = $level;
        } else if (substr($el, 0, 7) == '</span>') {
            $indent -= $level;
            $move = 0;
        } else {
            $move = 0;
        }

        $pretty[] = str_repeat(' ', $indent).$el;

        $indent += $move;
    }

    return implode("\n", $pretty);
}

global $page, $pages;

$page = 1;
$pages = array($post->post_content);

$snippet = gdrtsa_rich_snippets()->snippet_to_string($post);
$display = gdrtsa_rich_snippets()->snippet_display_method($post);

if (empty($snippet)) {
    echo '<pre>'.__("Based on the current settings for this post, snippet can't be generated.", "gd-rating-system").'</pre>';
} else {
    if ($display == 'jsonld') {
        echo '<pre>'.esc_html($snippet).'</pre>';
    } else {
        echo '<pre>'.esc_html(gdrts_pretty_print_microdata_snippet($snippet)).'</pre>';
    }
}
