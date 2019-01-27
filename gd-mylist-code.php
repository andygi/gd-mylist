<?php

##### mylist 1.0

/* NOTE
 * $styletarget: it used to make 'remove button' different behaviours into jquery it'll is valued by code into theme
 * values:
 *      mylist //cancel div with class: gd-mylist-box
 */

//setup general variables
global $wpdb, $var_setting, $templates_html, $template_path;

//db variables and settings
$db_prefix = $wpdb->prefix;
$var_setting = array(
    'table' => $db_prefix . 'gd_mylist',
    'table_posts' => $db_prefix . 'posts',
    'table_users' => $db_prefix . 'users',
    'guest_user' => rand(100000000000, 999999999999) . '001',
    'login_request' => false, //change 'true' if you want registration is required
    'add_to_content' => true,
);

//template variable
$template_path = plugins_url() . '/gd-mylist/template/'; //change this path to use a different template remember to replay all files with all require varibales and syntax
$locale_chunck = '?locale=';
$isShowListPage = false;

$templates_html = array(
    'box_list' => $template_path . 'box-list.html',
    'button' => $template_path . 'button.html',
);

if ($var_setting['login_request'] === false) {
    add_action('init', 'gd_setcookie');
    function gd_setcookie()
    {
        global $var_setting;
        if (!isset($_COOKIE['gb_mylist_guest'])) {
            $id_guest = $var_setting['guest_user'];
            setcookie('gb_mylist_guest', $id_guest, time() + (86400 * 30), COOKIEPATH, COOKIE_DOMAIN);
        }
    }
}

//setup assets
add_action('init', 'gd_mylist_asset');
function gd_mylist_asset() {
    global $template_path, $templates_html;
    $locale = get_locale();

    wp_register_script('gd_mylist_handelbar', plugins_url() . '/gd-mylist/lib/handlebars.min.js', array('jquery'));
    wp_register_script('gd_mylist_script', plugins_url() . '/gd-mylist/js/gd-script.js', array('jquery'));
    wp_localize_script(
        'gd_mylist_script',
        'gdMyListAjax',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'boxList' => $templates_html['box_list'],
            'button' => $templates_html['button'],
            'nonce' => wp_create_nonce('gd_mylist'),
        )
    );
    wp_enqueue_script('jquery');
    wp_enqueue_script('gd_mylist_script');
    wp_enqueue_script('gd_mylist_handelbar');
    wp_enqueue_style('all.min', plugins_url() . '/gd-mylist/css/all.min.css');
    wp_enqueue_style('gd_mylist_asset', plugins_url() . '/gd-mylist/css/app.css');
}

//add mylist function
add_action('wp_ajax_gd_add_mylist', 'gd_add_mylist');
add_action('wp_ajax_nopriv_gd_add_mylist', 'gd_add_mylist'); //login check

function gd_mylist_login() {
    echo 'Please login before';
    die();
}

function gd_add_mylist() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'gd_mylist')) {
        !exit('No naughty business please');
    }

    global $wpdb, $var_setting;
    $item_id = $_POST['itemId'];
    $user_id = $_POST['userId'];
    $result = array();

    $wpdb->query(
        $wpdb->prepare('
                INSERT INTO ' . $var_setting['table'] . "
                    (`item_id`, `user_id`)
                VALUES
                    ('%d', '%s');
                ",
            $item_id,
            $user_id
        )
    );

    $result ['showRemove'] = [
        'itemid' => $item_id,
        'styletarget' => null,
        'userid' => $user_id,
        'label' => __( 'add My List')
    ];

    print(json_encode($result));

    die();
}

//remove from mylist function
add_action('wp_ajax_gd_remove_mylist', 'gd_remove_mylist');
add_action('wp_ajax_nopriv_gd_remove_mylist', 'gd_remove_mylist'); //login check

function gd_remove_mylist() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'gd_mylist')) {
        !exit('No naughty business please');
    }

    global $wpdb, $var_setting;
    $item_id = $_POST['itemId'];
    $user_id = $_POST['userId'];
    $result = array();

    $wpdb->query(
        $wpdb->prepare(
            'DELETE FROM ' . $var_setting['table'] . '
                WHERE item_id = %d AND user_id = %s',
            $item_id,
            $user_id
        )
    );

    $result ['showAdd'] = [
        'itemid' => $item_id,
        'styletarget' => null,
        'userid' => $user_id,
        'label' => __( 'add My List')
    ];

    print(json_encode($result));

    die();
}

//show button add/remove
add_action('gd_mylist_btn', 'gd_show_mylist_btn', 10, 2); /* eg code call into theme: <?php do_action('gd_mylist_btn', 'mylist'); ?> */
add_shortcode('show_gd_mylist_btn', 'gd_show_mylist_btn'); /* eg shortcode call: [show_gd_mylist_btn] */

function gd_show_mylist_btn($atts) {
    global $wpdb, $var_setting, $templates_html;
    $locale = get_locale();
    $buttonData = [];

    extract(shortcode_atts(array(
        'styletarget' => null, //default
        'item_id' => null,
        'echo' => false,
    ), $atts));

    $gd_query = null;
    $user_id = get_current_user_id();
    if ($user_id === 0 && $var_setting['login_request'] === false) {
        if (!isset($_COOKIE['gb_mylist_guest'])) {
            $user_id = $var_setting['guest_user'];
        } else {
            $user_id = $_COOKIE['gb_mylist_guest'];
        }
    }
    if ($item_id == null) {
        $item_id = get_the_id();
    }

    //check if item is in mylist
    $gd_sql = 'SELECT id FROM ' . $var_setting['table'] . '
                WHERE item_id = ' . $item_id . ' AND user_id = ' . $user_id;

    $gd_query = $wpdb->get_results($gd_sql);

    if ($user_id > 0) {
        if ($gd_query != null) {
            //in mylist
            // $type = 'btn_remove';
            $buttonData ['showRemove'] = [
                'itemid' => $item_id,
                'styletarget' => $styletarget,
                'userid' => $user_id,
                'label' => __('remove My List')
            ];
        } else {
            $buttonData ['showAdd'] = [
                'itemid' => $item_id,
                'styletarget' => $styletarget,
                'userid' => $user_id,
                'label' => __( 'add My List')
            ];
        }

    } else {
        //chek if allow use in no login case
        //must to be login
        $buttonData ['showLogin'] = [
            'message' => __( 'Please login first'),
            'label' => __( 'add My List')
        ];
    }

    echo('<div class="js-item-mylist" data-id="'.$item_id.'">');
    echo('<script type="text/javascript">');
    echo('var myListButton'.$item_id.' = ');
    echo(json_encode($buttonData));
    echo('</script>');
    echo('</div>');
    echo('<div id="mylist_btn_'.$item_id.'"></div>');
}

//show my list in page
add_action('gd_mylist_list', 'gd_show_gd_mylist_list');
add_shortcode('show_gd_mylist_list', 'gd_show_gd_mylist_list', 10, 2); //shortcode call [show_gd_mylist_list]

function gd_show_gd_mylist_list($atts) {
    global $wpdb, $var_setting, $templates_html;
    $posts = null;
    $user_id = get_current_user_id();
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

    if ($user_id === 0 && $var_setting['login_request'] === false) {
        $user_id = $_COOKIE['gb_mylist_guest'];
    }

    if ($user_id_share) {
        $user_id = $user_id_share;
    }

    $posts = $wpdb->get_results(
        $wpdb->prepare(
            'SELECT
                    b.ID AS posts_id,
                    b.post_title AS posts_title,
                    b.post_date AS posts_date,
                    c.ID AS authors_id,
                    c.display_name AS authors_name
                FROM ' . $var_setting['table'] . ' a
                INNER JOIN ' . $var_setting['table_posts'] . ' b
                ON a.item_id = b.ID
                INNER JOIN ' . $var_setting['table_users'] . " c
                ON c.ID = b.post_author
                WHERE
                    b.post_status = 'publish'
                    AND a.user_id = %s
                ORDER BY b.post_title DESC",
            $user_id
        )
    );

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
                'share_label' => __( 'Share your list'),
                'pageid' => $pageid,
                'userid' => $user_id
            ];
        }

        if ($show_count === 'yes') {
            $type = 'item_count';
            $html = '';
            $count = $wpdb->num_rows;
            $listAr['count'] = [
                'showCount' => true,
                'count_label' => __( 'Total items'),
                'count' => $count
            ];
        }

        foreach ($posts as $post) {
            $type = 'post_list';
            $postId = $post->posts_id;
            $postAuthorId = $post->authors_id;
            $postAuthorName = $post->authors_name;
            $postTitle = $post->posts_title;
            $portTitleLang = extract_title($postTitle);
            $postUrl = get_permalink($postId);
            $args = array(
                'styletarget' => 'mylist',
                'item_id' => $postId,
            );

            if (strpos($postTitle, '<!--:') !== false || strpos($postTitle, '[:') !== false) { //means use mqtranlate or qtranlate-x
                $posttitle = $portTitleLang[$lang];
            } else {
                $posttitle = $postTitle;
            }

            $listAr['listitem'][] = [
                'postId' => $postId,
                'posturl' => $postUrl,
                'postimage' => wp_get_attachment_url(get_post_thumbnail_id($postId)),
                'posttitle' => $posttitle,
                'postdate' => get_the_date('F j, Y', $postId),
                'postAuthorName' => $postAuthorName,
                'showRemove' => [
                    'itemid' => $postId,
                    'styletarget' => 'mylist',
                    'userid' => $user_id,
                    'label' => __('remove My List'),
                ]
            ];
        }

        echo('<script type="text/javascript">');
        echo('var myListData = ');
        echo(json_encode($listAr));
        echo('</script>');
    } else {
        $listAr['showEmpty'] = [
            'empty_label' => __( "Sorry! Your don't have documents.")
        ];
        echo('<script type="text/javascript">');
        echo('var myListData = ');
        echo(json_encode($listAr));
        echo('</script>');
    }
    echo('<div id="myList_list"></div>');
}

function extract_title($postTitle) {
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

// add HOOK function to title and/or content
if ($var_setting['add_to_content'] === true) {
    function wpdev_before_after($content) {
        if (is_page() != 1) {
            $atts = array(
                'styletarget' => null, //default
                'item_id' => null,
                'echo' => false,
            );
            $fullcontent = gd_show_mylist_btn($atts) . $content;
        } else {
            $fullcontent = $content;
        }
    
        return $fullcontent;
    }
    add_filter('the_content', 'wpdev_before_after');
}

// add_filter('the_title', 'new_title', 10, 2);
// function new_title($title, $id) {
//     $title = $title.gd_show_mylist_btn();
//     return $title;
// }
