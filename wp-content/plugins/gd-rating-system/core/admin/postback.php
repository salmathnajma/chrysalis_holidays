<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_admin_postback {
    public function __construct() {
        if (isset($_POST['option_page']) && $_POST['option_page'] == 'gd-rating-system-newrule') {
            $this->new_rule();
        }

        if (isset($_POST['option_page']) && $_POST['option_page'] == 'gd-rating-system-ruledit') {
            $this->edit_rule();
        }

        if (isset($_POST['option_page']) && $_POST['option_page'] == 'gd-rating-system-entityedit') {
            $this->edit_entity();
        }

        if (isset($_POST['option_page']) && $_POST['option_page'] == 'gd-rating-system-tools') {
            $this->tools();
        }

        if (isset($_POST['option_page']) && $_POST['option_page'] == 'gd-rating-system-settings') {
            $this->settings();
        }

        do_action('gdrts_admin_postback_handler');
    }

    private function save_settings($panel) {
        require_once(GDRTS_D4PLIB.'admin/d4p.functions.php');
        require_once(GDRTS_D4PLIB.'admin/d4p.settings.php');
        include(GDRTS_PATH.'core/admin/internal.php');

        $options = new gdrts_admin_settings();
        $settings = $options->settings($panel);

        $processor = new d4pSettingsProcess($settings);
        $processor->base = 'gdrtsvalue';

        $data = $processor->process();

        foreach ($data as $group => $values) {
            if (!empty($group)) {
                foreach ($values as $name => $value) {
                    gdrts_settings()->set($name, $value, $group);
                }

                if ($panel == 'extensions') {
                    $ok = false;

                    foreach (array_keys(gdrts()->methods) as $method) {
                        if (gdrts_settings()->get('method_'.$method, 'load')) {
                            $ok = true;
                        }
                    }

                    if (!$ok) {
                        gdrts_settings()->set('method_stars-rating', true, 'load');
                    }
                }

                gdrts_settings()->save($group);
            }
        }
    }

    private function edit_entity() {
        check_admin_referer('gd-rating-system-entityedit-options');

        $url = 'admin.php?page=gd-rating-system-types';

        if (isset($_POST['gdrtsvalue']['entity'])) {
            $defaults = array('name' => '', 'label' => '', 'icon' => '', 'types' => array());
            $data = wp_parse_args($_POST['gdrtsvalue']['entity'], $defaults);

            $data['name'] = d4p_sanitize_key_expanded($data['name']);
            $data['label'] = d4p_sanitize_basic($data['label']);
            $data['icon'] = d4p_sanitize_basic($data['icon']);

            $value = array();

            foreach ($data['types']  as $id => $item) {
                if ($id > 0) {
                    $_key = d4p_sanitize_basic($item['key']);
                    $_val = d4p_sanitize_basic($item['value']);

                    if ($_key != '' && $_val != '') {
                        $value[$_key] = $_val;
                    }
                }
            }

            $data['types'] = $value;

            gdrts_settings()->save_custom_entity($data);

            $url.= '&message=saved';
        }

        wp_redirect($url);
        exit;
    }

    private function tools() {
        check_admin_referer('gd-rating-system-tools-options');

        $message = 'invalid';
        $post = $_POST['gdrtstools'];
        $action = $post['panel'];

        $url = 'admin.php?page=gd-rating-system-tools&panel='.$action;

        if ($action == 'import') {
            if (is_uploaded_file($_FILES['import_file']['tmp_name'])) {
                $raw = file_get_contents($_FILES['import_file']['tmp_name']);
                $data = maybe_unserialize($raw);

                if (is_array($data)) {
                    gdrts_settings()->import_from_object($data);

                    $message = 'imported';
                } else {
                    $message = 'import-failed';
                }
            }
        } else if ($action == 'snippets') {
            $settings = isset($post['snippets']) ? (array)$post['snippets'] : array();

            if (isset($settings['legacy_remove']) && $settings['legacy_remove'] == 'on') {
                gdrts_db()->snippets_remove_legacy_data();
            }

            if (isset($settings['ratings_disable']) && $settings['ratings_disable'] == 'on') {
                gdrts_db()->snippets_disable_ratings();
            }

            if (isset($settings['bulk'])) {
                $this->snippets_bulk_update($settings['bulk']);
            }

            $message = 'updated';
        } else if ($action == 'ipmd5') {
            $hash = isset($post['ipmd5']) ? (array)$post['ipmd5'] : array();

            if (isset($hash['hash']) && $hash['hash'] == 'on') {
                $ips = gdrts_db()->md5_hash_votes_log_ips();

                $message = 'hashed&ips='.$ips;
            }
        } else if ($action == 'remove') {
            $remove = isset($post['remove']) ? (array)$post['remove'] : array();

            if (empty($remove)) {
                $message = 'nothing-removed';
            } else {
                if (isset($remove['settings']) && $remove['settings'] == 'on') {
                    gdrts_settings()->remove_plugin_settings();
                }

                if (isset($remove['drop']) && $remove['drop'] == 'on') {
                    require_once(GDRTS_PATH.'core/admin/install.php');

                    gdrts_drop_database_tables();

                    if (!isset($remove['disable'])) {
                        gdrts_settings()->mark_for_update();
                    }
                } else if (isset($remove['truncate']) && $remove['truncate'] == 'on') {
                    require_once(GDRTS_PATH.'core/admin/install.php');

                    gdrts_truncate_database_tables();
                }

                if (isset($remove['disable']) && $remove['disable'] == 'on') {
                    deactivate_plugins('gd-rating-system/gd-rating-system.php', false, false);

                    wp_redirect(admin_url('plugins.php'));
                    exit;
                }

                $message = 'removed';
            }
        }

        wp_redirect($url.'&message='.$message);
        exit;
    }

    private function settings() {
        check_admin_referer('gd-rating-system-settings-options');

        $this->save_settings(gdrts_admin()->panel);

        $url = 'admin.php?page=gd-rating-system-settings&panel='.gdrts_admin()->panel;
        wp_redirect($url.'&message=saved');
        exit;
    }

	private function new_rule() {
		check_admin_referer('gd-rating-system-newrule-options');

		$item = d4p_sanitize_key_expanded($_POST['item']);
		$object = d4p_sanitize_key_expanded($_POST['object']);
		$method = isset($_POST[$object]) ? d4p_sanitize_basic(urldecode($_POST[$object]['method'])) : '';
		$method = $method != '-1' ? $method : '';

		$rule_id = gdrts_settings()->new_rule($item, $object, $method);

		$url = 'admin.php?page=gd-rating-system-rules';
		$url.= '&action=rule&rule='.$rule_id;

		wp_redirect($url);
		exit;
	}

	private function edit_rule() {
		check_admin_referer('gd-rating-system-ruledit-options');

		$_rule_id = isset($_GET['rule']) ? absint($_GET['rule']) : 0;

		if ($_rule_id == 0) {
			$url = 'admin.php?page=gd-rating-system-rules';

			wp_redirect($url.'&message=invalid');
			exit;
		}

		$rule = gdrts_settings()->get_rule($_rule_id);

		if ($rule === false) {
			$url = 'admin.php?page=gd-rating-system-rules';

			wp_redirect($url.'&message=invalid');
			exit;
		}

		$this->save_rule($rule);

		$url = 'admin.php?page=gd-rating-system-rules&action=rule&rule='.$_rule_id;

		wp_redirect($url.'&message=saved');
		exit;
	}

	/** @param gdrts_settings_rule $rule */
	private function save_rule($rule) {
		require_once(GDRTS_D4PLIB.'admin/d4p.functions.php');
		require_once(GDRTS_D4PLIB.'admin/d4p.settings.php');

		$groups = apply_filters('gdrts_admin_get_rule_on_save_'.$rule->object, array(), $rule);

		$settings = array();

		foreach ($groups as $group) {
			$settings = array_merge($settings, $group['settings']);
		}

		if (isset($_POST['gdrtsvalue']['rules'][$rule->id])) {
			$raw = $_POST['gdrtsvalue']['rules'][$rule->id];

			$request = array(
				'gdrtsvalue' => array(
					'rules' => isset($raw['settings']) ? $raw['settings'] : array()
				)
			);

			$processor = new d4pSettingsProcess($settings);
			$processor->base = 'gdrtsvalue';

			$data = $processor->process($request);

			$rule->active = isset($raw['active']) && $raw['active'] == 'on';
			$rule->settings = isset($data['rules']) ? $data['rules'] : array();
            $rule->filters = array();

			gdrts_settings()->save_rule($rule, true);
		}
	}

    public function snippets_bulk_update($data) {
        if (!isset($data['cpt']) && !isset($data['remove'])) {
            return;
        }

        $cpts = array_map('d4p_sanitize_key_expanded', array_keys($data['cpt']));

        foreach ($data['remove'] as $remove => $on) {
            if ($on != 'on') {
                continue;
            }

            $meta_key = '';
            switch ($remove) {
                case 'display':
                    $meta_key = 'rich-snippets_display';
                    break;
                case 'rating-method':
                    $meta_key = 'rich-snippets_rating_method';
                    break;
                case 'review-method':
                    $meta_key = 'rich-snippets_review_method';
                    break;
                case 'type':
                    $meta_key = 'rich-snippets_mode';
                    break;
                case 'custom':
                    $meta_key = 'rich-snippets_mode_custom_name';
                    break;
                case 'rating':
                    $meta_key = array();
                    $modes = gdrtsa_rich_snippets()->get_registered_modes();
                    foreach ($modes as $mode) {
                        $meta_key[] = 'rich-snippets_mode_'.$mode.'_rating';
                    }
                    break;
            }

            if (!empty($meta_key)) {
                gdrts_db()->snippets_remove_metakey($cpts, $meta_key);
            }
        }
    }
}
