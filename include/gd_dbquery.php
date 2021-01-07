<?php

class gd_dbQuery
{

    public function isInMylist($obj)
    {
        global $wpdb;
        $gd_sql = 'SELECT id FROM ' . $obj['table'] . '
                    WHERE item_id = ' . $obj['item_id'] . ' AND user_id = ' . $obj['user_id'];
        $output = $wpdb->get_results($gd_sql) != null ? 'true' : 'false';
        return $output;
    }

    public function addMylist($obj)
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'gd_mylist')) {
            !exit('Error');
        }
        global $wpdb;
        $query = $wpdb->query(
            $wpdb->prepare('
                    INSERT INTO ' . $obj['table'] . "
                        (`item_id`, `user_id`)
                    VALUES
                        ('%d', '%s');
                    ",
                $obj['item_id'],
                $obj['user_id']
            )
        );
        return $query;
    }

    public function removeMylist($obj)
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'gd_mylist')) {
            !exit('Error');
        }
        global $wpdb;
        $query = $wpdb->query(
            $wpdb->prepare(
                'DELETE FROM ' . $obj['table'] . '
                    WHERE item_id = %d AND user_id = %s',
                $obj['item_id'],
                $obj['user_id']
            )
        );
        return $query;
    }

}
