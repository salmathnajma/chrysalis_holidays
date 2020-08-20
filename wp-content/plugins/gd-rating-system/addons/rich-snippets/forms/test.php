<p><?php

    $_title = 'Google Structured Data Testing Tool';
    $_url = 'https://search.google.com/structured-data/testing-tool/u/0/';

    if ($post->post_status != 'publish') {
        _e("Post needs to be published before you can test it using Google Structured Data Testing Tool. If the post is not published, you can use the Testing Tool, but you need to paste the source of your page to test it.", "gd-rating-system");
    } else {
        _e("You can use the Google Structured Data Testing Tool, to test if the embedded snippets are correct. This tool will test all other strucutres that Google supports.", "gd-rating-system");

        $_title = __("Test this page using the Testing Tool", "gd-rating-system");
        $_url .= '#url='.urlencode(get_permalink($post));
    }

    ?></p>
<p>
    <a target="_blank" href="<?php echo $_url; ?>" class="button-secondary"><?php echo $_title; ?></a>
</p>
