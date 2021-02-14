<?php
/*
Plugin Name: GD Mylist
Plugin URI: https://wordpress.org/plugins/gd-mylist/
Description: Create mylist items of posts and pages
Version: 1.1.1
Author: Andy Greco
Author URI: http://www.gekode.co.uk
License: GPL
Text Domain: gd-mylist
Domain Path: /lang
 */

define('GDMYLIST_PLUGIN', __FILE__);
define('GDMYLIST_PLUGIN_BASENAME', plugin_basename(GDMYLIST_PLUGIN));
define('GDMYLIST_PLUGIN_NAME', trim(dirname(GDMYLIST_PLUGIN_BASENAME), '/'));
define('GDMYLIST_PLUGIN_DIR', untrailingslashit(dirname(GDMYLIST_PLUGIN)));

include_once GDMYLIST_PLUGIN_DIR . '/include/gd_mylist_asset.php';
include_once GDMYLIST_PLUGIN_DIR . '/include/gd_dbquery.php';
include_once GDMYLIST_PLUGIN_DIR . '/include/gd_setcookie.php';
include_once GDMYLIST_PLUGIN_DIR . '/include/gd_add_mylist.php';
include_once GDMYLIST_PLUGIN_DIR . '/include/gd_remove_mylist.php';
include_once GDMYLIST_PLUGIN_DIR . '/include/gd_show_mylist_btn.php';
include_once GDMYLIST_PLUGIN_DIR . '/include/gd_show_gd_mylist_list.php';
include_once GDMYLIST_PLUGIN_DIR . '/include/gd_admin_panel.php';

class gd_mylist_plugin
{
    public function config()
    {
        $config = [
            'is_anonymous_allowed' => 'true',
            'is_add_btn' => 'true',
            'is_fontawesome' => 'true',
            'fontawesome_btn_add' => 'far fa-heart',
            'fontawesome_btn_remove' => 'fas fa-heart',
            'fontawesome_loading' => 'fas fa-spinner fa-pulse',
            'settings_label' => 'gd_mylist_settings',
        ];
        return $config;
    }

    public function var_setting()
    {
        global $wpdb;
        $var_setting = [
            'template_path' => plugins_url() . '/gd-mylist/template/',
            'table' => $wpdb->prefix . 'gd_mylist',
            'table_posts' => $wpdb->prefix . 'posts',
            'table_users' => $wpdb->prefix . 'users',
            'guest_user' => rand(100000000000, 999999999999) . '001',
        ];
        return $var_setting;
    }

    public function __construct()
    {
        register_activation_hook(__FILE__, array($this, 'populate_db'));
        register_deactivation_hook(__FILE__, array($this, 'depopulate_db'));
        add_action( 'plugins_loaded', array($this, 'gd_mylist_load_plugin_textdomain') );

        new gd_mylist_asset();
        new gd_setcookie();
        new gd_add_mylist();
        new gd_remove_mylist();
        new gd_show_gd_mylist_list();
        new gd_show_mylist_btn();

        new gd_mylist_admin();
    }

    function gd_mylist_load_plugin_textdomain() {
        load_plugin_textdomain( 'gd-mylist', FALSE, GDMYLIST_PLUGIN_NAME . '/lang/' );
    }

    public function populate_db()
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        ob_start();
        require_once "lib/install-data.php";
        $sql = ob_get_clean();
        dbDelta($sql);
    }

    public function depopulate_db()
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        ob_start();
        require_once "lib/drop-tables.php";
        $sql = ob_get_clean();
        dbDelta($sql);
    }

    public function current_user_id()
    {
        $user_id = get_current_user_id();
        if ($user_id === 0 && $this->stored_setting()['is_anonymous_allowed'] === 'true') {
            $user_id = (!isset($_COOKIE['gb_mylist_guest'])) ? $this->var_setting()['guest_user'] : $user_id = $_COOKIE['gb_mylist_guest'];
        }
        return $user_id;
    }

    public function stored_setting()
    {
        $stored_options = get_option($this->config()['settings_label']);

        return $stored_options;
    }

}

new gd_mylist_plugin();
