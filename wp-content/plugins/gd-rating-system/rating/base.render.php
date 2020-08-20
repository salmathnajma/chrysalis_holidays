<?php

if (!defined('ABSPATH')) { exit; }

if (!function_exists('gdrts_prepare_list_of_users')) {
    function gdrts_prepare_list_of_users($users, $avatar = true, $avatar_size = 24) {
        $items = array();

        foreach ($users as $user) {
            $item = array(
                'id' => $user->user_id,
                'name' => $user->display_name,
                'email' => $user->user_email,
                'url' => $user->user_url,
                'vote' => $user->vote
            );

            if (empty($item['url'])) {
                $item['url'] = get_author_posts_url($user->user_id);
            }

            if ($avatar) {
                $item['avatar'] = get_avatar($user->user_email, $avatar_size);
            }

            $items[$user->user_id] = $item;
        }

        return apply_filters('gdrts_prepare_list_of_users', $items, $users, $avatar, $avatar_size);
    }
}
