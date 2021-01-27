<?php

class gd_add_mylist extends gd_mylist_plugin
{

    public function __construct()
    {
        add_action('wp_ajax_gd_add_mylist', array($this, 'print_add_data'));
        add_action('wp_ajax_nopriv_gd_add_mylist', array($this, 'print_add_data')); //login check
    }

    public function addMylist($obj)
    {
        $gd_dbQuery = new gd_dbQuery();
        return $gd_dbQuery->addMylist($obj);
    }

    public function gd_add_mylist()
    {
        global $wpdb;
        $item_id = $_POST['itemId'];
        $user_id = $_POST['userId'];
        $result = array();

        $obj = [
            'item_id' => $item_id,
            'user_id' => $user_id,
            'table' => $this->var_setting()['table'],
        ];
        $addMylist = $this->addMylist($obj);

        if ($addMylist > 0) {
            $result['showRemove'] = [
                'itemid' => $item_id,
                'styletarget' => null,
                'userid' => $user_id,
                'label' => __('remove My List', 'gd-mylist'),
                'icon' => $this->stored_setting()['fontawesome_btn_remove'],
            ];
        } else {
            $result['showRemove'] = [
                'error' => 'something went wrong during the update',
            ];
        }
        print(json_encode($result));
    }

    public function print_add_data()
    {
        $this->gd_add_mylist();
        die();
    }
}
