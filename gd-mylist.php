<?php
/*
Plugin Name: GD Mylist
Plugin URI: https://wordpress.org/plugins/gd-mylist/
Description: Create mylist items of posts and pages
Version: 0.4
Author: Andy Greco
Author URI: http://www.gekode.co.uk
License: GPL
*/


class gd_mylist_plugin {

    public function __construct() {

        include_once 'gd-mylist-code.php';

        register_activation_hook( __FILE__, array($this, 'populate_db') );
        register_deactivation_hook( __FILE__, array($this, 'depopulate_db') );
    }

    public function register_fields() {
        include_once 'gd-mylist-code.php';
    }

    public function populate_db() {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        ob_start();
        require_once "lib/install-data.php";
        $sql = ob_get_clean();
        dbDelta( $sql );
    }

    public function depopulate_db() {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        ob_start();
        require_once "lib/drop-tables.php";
        $sql = ob_get_clean();
        dbDelta( $sql );
    }

}

new gd_mylist_plugin();
