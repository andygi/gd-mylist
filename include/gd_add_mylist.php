<?php

class gd_add_mylist extends gd_mylist_plugin
{

    public function __construct()
    {
        add_action('wp_ajax_gd_add_mylist', array($this, 'gd_add_mylist'));
        add_action('wp_ajax_nopriv_gd_add_mylist', array($this, 'gd_add_mylist')); //login check
    }

    public function gd_add_mylist()
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'gd_mylist')) {
            !exit('No naughty business please');
        }
        global $wpdb;
        $item_id = $_POST['itemId'];
        $user_id = $_POST['userId'];
        $result = array();

        $wpdb->query(
            $wpdb->prepare('
                    INSERT INTO ' . $this->var_setting()['table'] . "
                        (`item_id`, `user_id`)
                    VALUES
                        ('%d', '%s');
                    ",
                $item_id,
                $user_id
            )
        );

        $result['showRemove'] = [
            'itemid' => $item_id,
            'styletarget' => null,
            'userid' => $user_id,
            'label' => __('remove My List'),
            'icon' => $this->stored_setting()['fontawesome_btn_remove'],
        ];

        print(json_encode($result));

        die();
    }
}
