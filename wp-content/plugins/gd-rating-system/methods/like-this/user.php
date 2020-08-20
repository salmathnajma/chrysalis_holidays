<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_user_like_this extends gdrts_method_user {
    public $method = 'like-this';

    public function has_voted($from = '', $to = '') {
        $stats = $this->stats($from, $to);

        return $stats['like']['items'] - $stats['clear']['items'] > 0;
    }

    public function count_votes($from = '', $to = '') {
        $stats = $this->stats($from, $to);

        return $stats['like']['items'];
    }

    public function count_clears($from = '', $to = '') {
        $stats = $this->stats($from, $to);

        return $stats['clear']['items'];
    }

    public function previous_vote() {
        $stats = $this->stats_anytime();

        if ($stats['like']['log_id'] > 0) {
            return gdrts_cache()->get_log_entry($stats['like']['log_id']);
        }

        return null;
    }

    public function active_vote() {
        return $this->previous_vote();
    }
}
