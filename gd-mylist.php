<?php
/*
Plugin Name: GD Mylist
Plugin URI: https://wordpress.org/plugins/gd-mylist/
Description: Create mylist items of posts and pages
Version: 1.0
Author: Andy Greco
Author URI: http://www.gekode.co.uk
License: GPL
*/

class gd_mylist_plugin {
    //setup general variables
    private $wpdb, $var_setting, $templates_html, $template_path;

    public function __construct() {
        global $wpdb, $var_setting, $templates_html, $template_path;
        //db variables and settings
        $db_prefix = $wpdb->prefix;
        $var_setting = array(
            'table' => $db_prefix . 'gd_mylist',
            'table_posts' => $db_prefix . 'posts',
            'table_users' => $db_prefix . 'users',
            'guest_user' => rand(100000000000, 999999999999) . '001',
            'login_request' => false, //change 'true' if you want registration is required
            'add_to_content' => true,
        );
    
        //template variable
        $template_path = plugins_url() . '/gd-mylist/template/'; //change this path to use a different template remember to replay all files with all require varibales and syntax
        $locale_chunck = '?locale=';
        $isShowListPage = false;
    
        $templates_html = array(
            'box_list' => $template_path . 'box-list.html',
            'button' => $template_path . 'button.html',
        );

        include 'gd-mylist-code.php';

        register_activation_hook( __FILE__, array($this, 'populate_db') );
        register_deactivation_hook( __FILE__, array($this, 'depopulate_db') );

        add_filter('the_content', array($this, 'wpdev_before_after'));
    }

    // public function register_fields() {
    //     include_once 'gd-mylist-code.php';
    // }

    function wpdev_before_after($content) {
        if ($var_setting['add_to_content'] === true) {
            if (is_page() != 1) {
                $atts = array(
                    'styletarget' => null, //default
                    'item_id' => null,
                    'echo' => false,
                );
                // $fullcontent = gd_show_mylist_btn($atts) . $content;
                $fullcontent = 'test---' . $content;
            } else {
                $fullcontent = $content;
            }
        
            return $fullcontent;
        }
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
