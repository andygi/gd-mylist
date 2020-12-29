<?php

class gd_remove_mylist extends gd_mylist_plugin
{

    public function __construct()
    {
        add_action('wp_ajax_gd_remove_mylist', array($this, 'gd_remove_mylist'));
        add_action('wp_ajax_nopriv_gd_remove_mylist', array($this, 'gd_remove_mylist')); //login check
    }

    public function gd_remove_mylist()
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'gd_mylist')) {
            !exit('No naughty business please');
        }
        global $wpdb;
        $item_id = $_POST['itemId'];
        $user_id = $_POST['userId'];
        $result = array();

        $wpdb->query(
            $wpdb->prepare(
                'DELETE FROM ' . $this->var_setting()['table'] . '
                    WHERE item_id = %d AND user_id = %s',
                $item_id,
                $user_id
            )
        );

        $result['showAdd'] = [
            'itemid' => $item_id,
            'styletarget' => null,
            'userid' => $user_id,
            'label' => __('add My List'),
            'icon' => $this->stored_setting()['fontawesome_btn_add'],
        ];

        print(json_encode($result));

        die();
    }
}
