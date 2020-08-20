<?php

if (!defined('ABSPATH')) { exit; }

/** @global wpdb $wpdb
  * @return array */
function gdrts_list_database_tables() {
    global $wpdb;

    $tables = array(
        $wpdb->prefix.'gdrts_itemmeta' => 4,
        $wpdb->prefix.'gdrts_items' => 5,
        $wpdb->prefix.'gdrts_items_basic' => 9,
        $wpdb->prefix.'gdrts_logmeta' => 4,
        $wpdb->prefix.'gdrts_logs' => 12,
        $wpdb->prefix.'gdrts_cache' => 6
    );

    return array_merge($tables, apply_filters('gdrts_database_tables_list', array()));
}

/** @global wpdb $wpdb
  * @return array */
function gdrts_install_database() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $tables = array(
        'itemmeta' => $wpdb->prefix.'gdrts_itemmeta',
        'items' => $wpdb->prefix.'gdrts_items',
        'items_basic' => $wpdb->prefix.'gdrts_items_basic',
        'logmeta' => $wpdb->prefix.'gdrts_logmeta',
        'logs' => $wpdb->prefix.'gdrts_logs',
        'cache' => $wpdb->prefix.'gdrts_cache'
    );

    $query = "CREATE TABLE ".$tables['itemmeta']." (
meta_id bigint(20) unsigned NOT NULL auto_increment,
item_id bigint(20) unsigned NOT NULL default '0',
meta_key varchar(255) NULL default NULL,
meta_value longtext NULL,
PRIMARY KEY  (meta_id),
KEY item_id (item_id),
KEY meta_key (meta_key)
) ".$charset_collate.";

CREATE TABLE ".$tables['items']." (
item_id bigint(20) unsigned NOT NULL auto_increment,
entity varchar(32) NOT NULL default 'posts' COMMENT 'posts,comments,users,terms',
name varchar(64) NOT NULL default 'post',
id bigint(20) unsigned NOT NULL default '0',
latest datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'gmt',
PRIMARY KEY  (item_id),
UNIQUE KEY entity_name_id (entity,name,id),
KEY entity (entity),
KEY name (name),
KEY id (id)
) ".$charset_collate.";

CREATE TABLE ".$tables['items_basic']." (
id bigint(20) unsigned NOT NULL auto_increment,
item_id bigint(20) unsigned NOT NULL default '0',
method varchar(64) NOT NULL default '',
series varchar(64) NOT NULL default '',
latest datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'gmt',
rating int(11) NOT NULL default '0' COMMENT 'normalized value',
votes int(11) NOT NULL default '0',
sum int(10) unsigned NOT NULL default '0',
max tinyint(3) unsigned NOT NULL default '0',
PRIMARY KEY  (id),
UNIQUE KEY item_method_series (item_id,method,series),
KEY item_id (item_id),
KEY method (method),
KEY series (series),
KEY latest (latest),
KEY rating (rating)
) ".$charset_collate.";

CREATE TABLE ".$tables['logmeta']." (
meta_id bigint(20) unsigned NOT NULL auto_increment,
log_id bigint(20) unsigned NOT NULL default '0',
meta_key varchar(255) NULL default NULL,
meta_value longtext NULL,
PRIMARY KEY  (meta_id),
KEY log_id (log_id),
KEY meta_key (meta_key)
) ".$charset_collate.";

CREATE TABLE ".$tables['logs']." (
log_id bigint(20) NOT NULL auto_increment,
item_id bigint(20) NOT NULL default '0' COMMENT 'from gdrs_items table',
user_id bigint(20) NOT NULL default '0',
ref_id bigint(20) NOT NULL default '0' COMMENT 'reference id for revotes from this same table',
action varchar(32) NOT NULL default 'vote' COMMENT 'vote,revote,queue',
status varchar(32) NOT NULL default 'active' COMMENT 'active,replaced',
method varchar(64) NOT NULL default 'stars-rating',
logged datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'gmt',
ip varchar(64) NOT NULL default '',
series varchar(64) NOT NULL default '' COMMENT 'set belonging to method',
vote varchar(64) NOT NULL default '',
max int(11) NOT NULL default '0',
PRIMARY KEY  (log_id),
KEY item_id (item_id),
KEY user_id (user_id),
KEY action (action),
KEY ref_id (ref_id),
KEY status (status),
KEY method (method),
KEY ip (ip),
KEY series (series),
KEY vote (vote),
KEY max (max)
) ".$charset_collate.";

CREATE TABLE ".$tables['cache']." (
cache_id bigint(20) unsigned NOT NULL auto_increment,
module varchar(16) NOT NULL default '' COMMENT 'aggregate,period',
method varchar(64) NOT NULL default '',
access varchar(32) NOT NULL default '',
store longtext NOT NULL,
expire int(10) unsigned NOT NULL default '0',
PRIMARY KEY  (cache_id),
KEY module (module),
KEY method (method)
) ".$charset_collate.";";

    $query.= apply_filters('gdrts_database_tables_schemas', '', $charset_collate);

    require_once(ABSPATH.'wp-admin/includes/upgrade.php');

    return dbDelta($query);
}

function gdrts_update_database_tables_collations() {
    $tables = gdrts_list_database_tables();

    foreach (array_keys($tables) as $table) {
        gdrts_maybe_convert_table_to_utf8mb4($table);
    }
}

/** @global wpdb $wpdb
  * @return array */
function gdrts_check_database() {
    global $wpdb;

    $result = array();
    $tables = gdrts_list_database_tables();

    foreach ($tables as $table => $count) {
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
            $columns = $wpdb->get_results("SHOW COLUMNS FROM $table");

            if ($count != count($columns)) {
                $result[$table] = array("status" => "error", "msg" => __("Some columns are missing.", "gd-rating-system"));
            } else {
                $result[$table] = array("status" => "ok");
            }
        } else {
            $result[$table] = array("status" => "error", "msg" => __("Table missing.", "gd-rating-system"));
        }
    }

    return $result;
}

/** @global wpdb $wpdb
  * @return bool */
function gdrts_check_database_quick() {
    global $wpdb;

    $tables = gdrts_list_database_tables();

    foreach ($tables as $table => $count) {
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
            $columns = $wpdb->get_results("SHOW COLUMNS FROM $table");

            if ($count != count($columns)) {
                return false;
            }
        }
    }

    return true;
}

/** @global wpdb $wpdb */
function gdrts_truncate_database_tables() {
    global $wpdb;

    $tables = array_keys(gdrts_list_database_tables());

    foreach ($tables as $table) {
        $wpdb->query("TRUNCATE TABLE ".$table);
    }
}

/** @global wpdb $wpdb */
function gdrts_drop_database_tables() {
    global $wpdb;

    $tables = array_keys(gdrts_list_database_tables());

    foreach ($tables as $table) {
        $wpdb->query("DROP TABLE IF EXISTS ".$table);
    }
}

/** @param string $table
  * @global wpdb $wpdb
  * @return bool */
function gdrts_maybe_convert_table_to_utf8mb4($table) {
    global $wpdb;

    $results = $wpdb->get_results("SHOW FULL COLUMNS FROM `$table`");
    if (!$results) {
        return false;
    }

    foreach ($results as $column) {
        if ($column->Collation) {
            list($charset) = explode('_', $column->Collation);
            $charset = strtolower($charset);

            if ('utf8' !== $charset && 'utf8mb4' !== $charset) {
                return false;
            }
        }
    }

    $table_details = $wpdb->get_row("SHOW TABLE STATUS LIKE '$table'");
    if (!$table_details) {
        return false;
    }

    if ($table_details->Collation == 'utf8mb4_unicode_ci') {
        return false;
    }

    return $wpdb->query("ALTER TABLE $table CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
}
