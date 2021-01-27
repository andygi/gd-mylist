<?php

class gd_remove_mylist extends gd_mylist_plugin
{

    public function __construct()
    {
        add_action('wp_ajax_gd_remove_mylist', array($this, 'print_remove_data'));
        add_action('wp_ajax_nopriv_gd_remove_mylist', array($this, 'print_remove_data')); //login check
    }

    public function removeMylist($obj)
    {
        $gd_dbQuery = new gd_dbQuery();
        return $gd_dbQuery->removeMylist($obj);
    }

    public function gd_remove_mylist()
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
        $removeMylist = $this->removeMylist($obj);

        if ($removeMylist > 0) {
            $result['showAdd'] = [
                'itemid' => $item_id,
                'styletarget' => null,
                'userid' => $user_id,
                'label' => __('add My List', 'gd-mylist'),
                'icon' => $this->stored_setting()['fontawesome_btn_add'],
            ];
        } else {
            $result['showAdd'] = [
                'error' => 'something went wrong during the update',
            ];
        }

        print(json_encode($result));
    }

    public function print_remove_data()
    {
        $this->gd_remove_mylist();
        die();
    }
}
