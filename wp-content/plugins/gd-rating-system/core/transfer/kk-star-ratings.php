<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_transfer_kk_star_ratings {
    public $page = 500;

    public function __construct() {
        $this->page = gdrts_settings()->get('step_transfer');
    }

    public function round_to_page($number) {
        return ceil(intval($number) / $this->page) * $this->page;
    }

    public function count() {
        $count = 0;

        $sql = "SELECT COUNT(*) FROM ".gdrts_db()->wpdb()->postmeta." WHERE meta_key IN ('_kksr_casts', '_kksr_ratings')";
        $count+= $this->round_to_page(gdrts_db()->get_var($sql));

        return $count;
    }

    public function transfer($max = 5, $offset = 0) {
        $sql = "SELECT post_id as `id`, SUBSTR(meta_key, 7) as `key`, meta_value as `value` 
               FROM ".gdrts_db()->wpdb()->postmeta." WHERE meta_key IN ('_kksr_casts', '_kksr_ratings') 
               ORDER BY post_id ASC LIMIT ".$offset.', '.$this->page;
        $raw = gdrts_db()->run($sql);

        if (!empty($raw)) {
            $data = array();

            foreach ($raw as $r) {
                $id = intval($r->id);
                $data[$id][$r->key] = $r->value;
            }

            foreach ($data as $post => $rating) {
                $post_type = get_post_type($post);

                if ($post_type) {
                    gdrtsm_stars_rating()->init_rule_settings('posts', $post_type);

                    $args = array(
                        'entity' => 'posts', 
                        'name' => $post_type, 
                        'id' => $post
                    );

                    $item = gdrts_get_rating_item($args);
                    $item->prepare_save();
                    $item->prepare('stars-rating');

                    if ($item->get_meta('kksr-import', false) === false) {
                        $factor = gdrtsm_stars_rating()->get_rule('stars') / $max;

                        $votes = intval($item->get('stars-rating_votes', 0));
                        $sum = floatval($item->get('stars-rating_sum', 0));

                        $votes+= intval($rating['casts']);
                        $sum+= intval($rating['ratings']) * $factor;

                        $_rating = round($sum / $votes, gdrts()->decimals());

                        $item->set_rating('sum', $sum);
                        $item->set_rating('max', gdrtsm_stars_rating()->get_rule('stars'));
                        $item->set_rating('votes', $votes);
                        $item->set_rating('rating', $_rating);

                        $item->set('kksr-import', true);

                        $item->save(false, false);
                    }
                }
            }
        }
    }
}
