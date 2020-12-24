<?php

class gd_setcookie extends gd_mylist_plugin {

    public function __construct()
    {
        // print('__due.1');
        add_action('init', array($this, 'gd_setcookie'));
    }
    
    public function gd_setcookie()
    {
        // print('__due.1.1');
        if ($this->stored_setting()['is_anonymous_allowed'] === 'true' && !is_user_logged_in()) {
            if (!isset($_COOKIE['gb_mylist_guest'])) {
                $id_guest = $this->var_setting['guest_user'];
                setcookie('gb_mylist_guest', $id_guest, time() + (86400 * 30), COOKIEPATH, COOKIE_DOMAIN);
            }
        } else {
            setcookie('gb_mylist_guest', '', time() - 3600);
        }
    }
}

new gd_setcookie();