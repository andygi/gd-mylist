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

    public function postsList($obj)
    {
        global $wpdb;
        $posts = [];

        $posts = $wpdb->get_results(
            $wpdb->prepare(
                'SELECT
                        b.ID AS posts_id,
                        b.post_title AS posts_title,
                        b.post_date AS posts_date,
                        c.ID AS authors_id,
                        c.display_name AS authors_name
                    FROM ' . $obj['table'] . ' a
                    INNER JOIN ' . $obj['table_posts'] . ' b
                    ON a.item_id = b.ID
                    INNER JOIN ' . $obj['table_users'] . " c
                    ON c.ID = b.post_author
                    WHERE
                        b.post_status = 'publish'
                        AND a.user_id = %s
                    ORDER BY b.post_title DESC",
                $obj['user_id']
            )
        );

        return $posts;
    }

}
