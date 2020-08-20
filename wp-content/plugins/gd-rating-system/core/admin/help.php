<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_admin_help {
	function __construct() {
		add_filter('gdrts_load_admin_page_log', array($this, 'tab_log'));
		add_filter('gdrts_load_admin_page_types', array($this, 'tab_types'));
		add_filter('gdrts_load_admin_page_rules', array($this, 'tab_rules'));
	}

	public function tab_sidebar() {
		$screen = get_current_screen();

		$screen->set_help_sidebar(
			'<p><strong>'.gdrts_admin()->title().'</strong></p>'.
			'<p><a target="_blank" href="https://plugins.dev4press.com/'.gdrts_admin()->plugin.'/">'.__("Home Page", "gd-rating-system").'</a><br/>'.
			'<a target="_blank" href="https://support.dev4press.com/kb/product/'.gdrts_admin()->plugin.'/">'.__("Knowledge Base", "gd-rating-system").'</a><br/>'.
			'<a target="_blank" href="https://support.dev4press.com/forums/forum/plugins/'.gdrts_admin()->plugin.'/">'.__("Support Forum", "gd-rating-system").'</a></p>'
		);
	}

	public function tab_getting_help() {
		$screen = get_current_screen();

		$screen->add_help_tab(
			array(
				'id' => 'gdbbx-help-info',
				'title' => __("Help & Support", "gd-rating-system"),
				'content' => '<h2>'.__("Help & Support", "gd-rating-system").'</h2><p>'.__("To get help with this plugin, you can start with Knowledge Base list of frequently asked questions, user guides, articles (tutorials) and reference guide (for developers).", "gd-rating-system").
				             '</p><p><a href="https://support.dev4press.com/kb/product/'.gdrts_admin()->plugin.'/" class="button-primary" target="_blank">'.__("Knowledge Base", "gd-rating-system").'</a> <a href="https://support.dev4press.com/forums/forum/plugins/'.gdrts_admin()->plugin.'/" class="button-secondary" target="_blank">'.__("Support Forum", "gd-rating-system").'</a></p>'
			)
		);

		$screen->add_help_tab(
			array(
				'id' => 'gdbbx-help-bugs',
				'title' => __("Found a bug?", "gd-rating-system"),
				'content' => '<h2>'.__("Found a bug?", "gd-rating-system").'</h2><p>'.__("If you find a bug in GD Rating System, you can report it in the support forum.", "gd-rating-system").
				             '</p><p>'.__("Before reporting a bug, make sure you use latest plugin version, your website and server meet system requirements. And, please be as descriptive as possible, include server side logged errors, or errors from browser debugger.", "gd-rating-system").
				             '</p><p><a href="https://support.dev4press.com/forums/forum/plugins/'.gdrts_admin()->plugin.'/" class="button-primary" target="_blank">'.__("Open new topic", "gd-rating-system").'</a></p>'
			)
		);
	}

	public function tab_types() {
		$screen = get_current_screen();

		$render = '<p>'.__("Here are few important pointers about this panel.", "gd-rating-system").'</p>';

		$render.= '<ul>';
		$render.= '<li>'.__("All registered rating entities and types are listed here for overview purposes.", "gd-rating-system").'</li>';
		$render.= '<li>'.__("You can't modify rating entities and types registered with code.", "gd-rating-system").'</li>';
		$render.= '</ul>';

		$screen->add_help_tab(
			array(
				'id' => 'gdrts-help-types',
				'title' => __("Rating Types", "gd-rating-system"),
				'content' => $render
			)
		);
	}

	public function tab_log() {
		$screen = get_current_screen();

		$render = '<p>'.__("Here are few important pointers about this panel. Make sure you understand the limitations and basic rules for using this panel.", "gd-rating-system").'</p>';

		$render.= '<ul>';
		$render.= '<li>'.__("Deleting votes from the log will recalculate object ratings. If you delete one vote, plugin will take previous vote by the user for the object, if available. This way it is undoing the revoting.", "gd-rating-system").'</li>';
		$render.= '<li>'.__("Each vote that is replacing previous vote (revote) hold reference to vote it replaces. If you break this chain, plugin will not be able to correctly calculate the correct rating. In general, it is not recommend to mess with the votes at all if you want to maintain the correct votes and revotes log.", "gd-rating-system").'</li>';
		$render.= '<li>'.__("It is not recommended to use 'Remove from Log' option because it will just remove log entry, it will not recaulcaulte object rating. If you don't understand this option, do not use it. This option is disabled by default, and it can be enabled from plugin settings.", "gd-rating-system").'</li>';
		$render.= '<li>'.__("If log takes too long to load, disable GEO Location flags for votes IP's from plugin settings.", "gd-rating-system").'</li>';
		$render.= '<li>'.__("Do not mess with votes log in database directly, or you might delete something that will cause problems to the way plugin works.", "gd-rating-system").'</li>';
		$render.= '</ul>';

		$screen->add_help_tab(
			array(
				'id' => 'gdrts-help-log',
				'title' => __("Votes Log", "gd-rating-system"),
				'content' => $render
			)
		);
	}

	public function tab_rules() {
		$screen = get_current_screen();

		$render = '<h2>'.__("Rules Filters", "gd-rating-system").'</h2><p>'.__("Here are few important pointers about use the filters for settings rules.", "gd-rating-system").'</p>';

		$render.= '<ul>';
		$render.= '<li>'.__("Filters can be used only for posts rating items (post, page, custom post types) and rating method objects, and right now, there is one filter available: terms. You can specify one or more terms, by ID, comma separated.", "gd-rating-system").'</li>';
		$render.= '<li>'.__("Make sure not to make rules using filters ambiguous - don't make two or more filters targeting same rating items (for same rating method or addon), or there will be problem resolving the filter, ending up with plugin using first matched filter.", "gd-rating-system").'</li>';
		$render.= '<li>'.__("When displaying rating lists, option is added to allow for auto resolve of the rules filters. This option will take into account list filter terms and rating method (method only, no series if available) to find the appropriate rule to use.", "gd-rating-system").'</li>';
		$render.= '</ul>';

		$screen->add_help_tab(
			array(
				'id' => 'gdrts-help-rules',
				'title' => __("Rules", "gd-rating-system"),
				'content' => $render
			)
		);
	}
}
