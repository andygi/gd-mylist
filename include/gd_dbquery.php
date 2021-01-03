<?php

class gd_dbQuery extends gd_mylist_plugin {

    public function isInMylist($obj) {
        global $wpdb;
        $gd_sql = 'SELECT id FROM ' . $this->var_setting()['table'] . '
                    WHERE item_id = ' . $obj['item_id'] . ' AND user_id = ' . $obj['user_id'];
        $output = $wpdb->get_results($gd_sql) != null ? 'true' : 'false';
        return $output;
        die();
    }

}