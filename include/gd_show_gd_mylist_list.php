<?php

class gd_show_gd_mylist_list extends gd_mylist_plugin
{

    public function __construct()
    {
        add_action('gd_mylist_list', array($this, 'gd_show_gd_mylist_list'), 11, 2);
        add_shortcode('show_gd_mylist_list', array($this, 'gd_show_gd_mylist_list'), 11, 2);
    }

    public function post_query($user_id)
    {
        $obj = [
            'user_id' => $user_id,
            'table' => $this->var_setting()['table'],
            'table_posts' => $this->var_setting()['table_posts'],
            'table_users' => $this->var_setting()['table_users'],
        ];
        $postsList = new gd_dbQuery();
        return $postsList->postsList($obj);
    }

    public function list_item($post)
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
                'label' => __('remove My List', 'gd-mylist'),
                'icon' => $this->stored_setting()['fontawesome_btn_remove'],
            ],
        ];

        return $output;
    }

    public function extract_title($postTitle)
    {
        // support for mqtranlate and qtranlate-x
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

    public function gd_show_gd_mylist_list($atts)
    {
        global $wpdb;
        $posts = null;
        $user_id = $this->current_user_id();
        $locale = get_locale();
        $lang = substr($locale, 0, 2);
        $isShowListPage = true;
        $output = '';
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
                    'share_label' => __('Share your list', 'gd-mylist'),
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
                    'count_label' => __('Total items', 'gd-mylist'),
                    'count' => $count,
                ];
            }

            foreach ($posts as $post) {
                $listAr['listitem'][] = $this->list_item($post);
            }

            $output .= '<script type="text/javascript">';
            $output .= 'var myListData = ';
            $output .= json_encode($listAr);
            $output .= '</script>';
        } else {
            $listAr['showEmpty'] = [
                'empty_label' => __("Sorry! Your don't have documents.", 'gd-mylist'),
            ];
            $output .= '<script type="text/javascript">';
            $output .= 'var myListData = ';
            $output .= json_encode($listAr);
            $output .= '</script>';
        }
        $output .= '<div id="myList_list"></div>';
        print($output);
    }

}
