<?php

class gd_show_gd_mylist_list extends gd_mylist_plugin
{

    public function __construct()
    {
        add_action('gd_mylist_list', array($this, 'gd_show_gd_mylist_list'), 11, 2);
        add_shortcode('show_gd_mylist_list', array($this, 'gd_show_gd_mylist_list'), 11, 2);
    }

    public function gd_show_gd_mylist_list($atts)
    {
        global $wpdb;
        $posts = null;
        $user_id = $this->current_user_id();
        $locale = get_locale();
        $lang = substr($locale, 0, 2);
        $isShowListPage = true;
        $listAr = [];
        if (isset($_GET['wish'])) {
            $user_id_share = $_GET['wish'];
        } else {
            $user_id_share = null;
        }

        //whatsapp get id
        $url = $_SERVER['REQUEST_URI'];
        $arUrl = explode('wish_', $url);
        if (isset($arUrl[1])) {
            $user_id_share = $arUrl[1];
        }

        extract(shortcode_atts(array(
            'share_list' => 'yes',
            'show_count' => 'yes',
        ), $atts));

        if ($user_id_share) {
            $user_id = $user_id_share;
        }

        $posts = $this->post_query($user_id);

        if ($posts != null) {
            $listAr['showList'] = true;
            if ($share_list === 'yes') {
                $type = 'share_list';
                $html = '';
                $permalink = get_permalink();
                if (strpos($permalink, '?') !== false) {
                    $pageid = $permalink . '&';
                } else {
                    $pageid = $permalink . '?';
                }
                $listAr['share'] = [
                    'showShare' => true,
                    'share_label' => __('Share your list'),
                    'pageid' => $pageid,
                    'userid' => $user_id,
                ];
            }

            if ($show_count === 'yes') {
                $type = 'item_count';
                $html = '';
                $count = $wpdb->num_rows;
                $listAr['count'] = [
                    'showCount' => true,
                    'count_label' => __('Total items'),
                    'count' => $count,
                ];
            }

            foreach ($posts as $post) {
                $listAr['listitem'][] = $this->list_item($post);
            }

            echo ('<script type="text/javascript">');
            echo ('var myListData = ');
            echo (json_encode($listAr));
            echo ('</script>');
        } else {
            $listAr['showEmpty'] = [
                'empty_label' => __("Sorry! Your don't have documents."),
            ];
            echo ('<script type="text/javascript">');
            echo ('var myListData = ');
            echo (json_encode($listAr));
            echo ('</script>');
        }
        echo ('<div id="myList_list"></div>');
    }

    private function list_item($post)
    {
        $output = [];
        $type = 'post_list';
        $postId = $post->posts_id;
        $postAuthorId = $post->authors_id;
        $postAuthorName = $post->authors_name;
        $postTitle = $post->posts_title;
        $portTitleLang = $this->extract_title($postTitle);
        $postUrl = get_permalink($postId);
        $user_id = $this->current_user_id();
        $args = array(
            'styletarget' => 'mylist',
            'item_id' => $postId,
        );

        if (strpos($postTitle, '<!--:') !== false || strpos($postTitle, '[:') !== false) { //means use mqtranlate or qtranlate-x
            $posttitle = $portTitleLang[$lang];
        } else {
            $posttitle = $postTitle;
        }

        $output = [
            'postId' => $postId,
            'posturl' => $postUrl,
            'postimage' => wp_get_attachment_url(get_post_thumbnail_id($postId)),
            'posttitle' => $postTitle,
            'postdate' => get_the_date('F j, Y', $postId),
            'postAuthorName' => $postAuthorName,
            'showRemove' => [
                'itemid' => $postId,
                'styletarget' => 'mylist',
                'userid' => $user_id,
                'label' => __('remove My List'),
                'icon' => $this->stored_setting()['fontawesome_btn_remove'],
            ],
        ];

        return $output;
    }

    private function post_query($user_id)
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
                    FROM ' . $this->var_setting()['table'] . ' a
                    INNER JOIN ' . $this->var_setting()['table_posts'] . ' b
                    ON a.item_id = b.ID
                    INNER JOIN ' . $this->var_setting()['table_users'] . " c
                    ON c.ID = b.post_author
                    WHERE
                        b.post_status = 'publish'
                        AND a.user_id = %s
                    ORDER BY b.post_title DESC",
                $user_id
            )
        );

        return $posts;
    }

    private function extract_title($postTitle)
    {
        $titles = null;

        if (strpos($postTitle, '<!--:') !== false) {
            $regexp = '/<\!--:(\w+?)-->([^<]+?)<\!--:-->/i';
        } else {
            $regexp = '/\:(\w{2})\]([^\[]+?)\[/';
        }

        if (preg_match_all($regexp, $postTitle, $matches)) {
            $titles = array();
            $count = count($matches[0]);
            for ($i = 0; $i < $count; ++$i) {
                $titles[$matches[1][$i]] = $matches[2][$i];
            }
        }

        return $titles;
    }
}
