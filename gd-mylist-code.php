<?php

##### mylist 0.3

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
        'table' => $db_prefix.'gd_mylist',
        'table_posts' => $db_prefix.'posts',
        'table_users' => $db_prefix.'users',
        'login_request' => 'no', //change 'yes' if you want registration is required
        'guest_user' => rand(100000000000, 999999999999).'001',
    );

    //template variable
    $template_path = plugins_url().'/gd-mylist/template/'; //change this path to use a different template remember to replay all files with all require varibales and syntax
    $locale_chunck = '?locale=';

    $templates_html = array(
        'btn_add' => $template_path.'btn-add.php'.$locale_chunck,
        'btn_remove' => $template_path.'btn-remove.php'.$locale_chunck,
        'btn_login' => $template_path.'btn-login.php'.$locale_chunck,
        'box_list' => $template_path.'box-list.php'.$locale_chunck,
        'box_list_empty' => $template_path.'box-list-empty.php'.$locale_chunck,
        'box_list_share' => $template_path.'box-list-share.php'.$locale_chunck,
        'box_list_count' => $template_path.'box-list-count.php'.$locale_chunck,
        'chunck_loading' => $template_path.'chunck-loading.php'.$locale_chunck,
        'chunck_add' => $template_path.'chunck-add.php'.$locale_chunck,
        'chunck_remove' => $template_path.'chunck-remove.php'.$locale_chunck,
        'btn_view_wishlist' => $template_path.'btn-view-wishlist.php'.$locale_chunck,
        'chunck_view_wishlist' => $template_path.'chunck-view-wishlist.php'.$locale_chunck
    );

if ($var_setting['login_request'] == 'no') {
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
function gd_mylist_asset()
{
    global $template_path, $templates_html;
    $locale = get_locale();

    wp_register_script('gd_mylist_script', plugins_url().'/gd-mylist/js/gd-script.js', array('jquery'));
    wp_localize_script(
        'gd_mylist_script',
        'gdMyListAjax',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'chunckLoading' => $templates_html['chunck_loading'].$locale,
            'chunckBtnLogin' => $templates_html['btn_login'].$locale,
            'chunckBtnAdd' => $templates_html['chunck_add'].$locale,
            'chunckBtnRemove' => $templates_html['chunck_remove'].$locale,
        )
    );
    wp_enqueue_script('jquery');
    wp_enqueue_script('gd_mylist_script');
    wp_enqueue_style('font-awesome.min', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
		wp_enqueue_style( 'gd_mylist_asset', plugins_url().'/gd-mylist/css/app.css' );
}

//add mylist function

add_action('wp_ajax_gd_add_mylist', 'gd_add_mylist');
add_action('wp_ajax_nopriv_gd_add_mylist', 'gd_add_mylist'); //login check

function gd_mylist_login()
{
    echo 'Please login before';
    die();
}

function gd_add_mylist()
{
    if (!wp_verify_nonce($_REQUEST['nonce'], 'gd_mylist')) {
        !exit('No naughty business please');
    }

    global $wpdb, $var_setting;
    $item_id = $_POST['itemId'];
    $user_id = $_POST['userId'];
    $result = array();

    $wpdb->query(
        $wpdb->prepare('
                INSERT INTO '.$var_setting['table']."
                    (`item_id`, `user_id`)
                VALUES
                    ('%d', '%s');
                ",
                $item_id,
                $user_id
                    )
                );

    $result['type'] = 'success';

    //$result = json_encode($result);
    $result = 'ok';
    echo $result;

    die();
}

//remove from mylist function

add_action('wp_ajax_gd_remove_mylist', 'gd_remove_mylist');
add_action('wp_ajax_nopriv_gd_remove_mylist', 'gd_remove_mylist'); //login check

function gd_remove_mylist()
{
    if (!wp_verify_nonce($_REQUEST['nonce'], 'gd_mylist')) {
        !exit('No naughty business please');
    }

    global $wpdb, $var_setting;
    $item_id = $_POST['itemId'];
    $user_id = $_POST['userId'];
    $result = array();

    $wpdb->query(
        $wpdb->prepare(
            'DELETE FROM '.$var_setting['table'].'
                WHERE item_id = %d AND user_id = %s',
                $item_id,
                $user_id
        )
    );

    $result['type'] = 'success';

    //$result = json_encode($result);
    $result = 'ok';

    die();
}

//show button add/remove

add_action('gd_mylist_btn', 'gd_show_mylist_btn', 10, 2); /* eg code call into theme: <?php do_action('gd_mylist_btn', 'mylist'); ?> */
add_shortcode('show_gd_mylist_btn', 'gd_show_mylist_btn'); /* eg shortcode call: [show_gd_mylist_btn] */

//function gd_show_mylist_btn($styletarget = null, $item_id = null ) {
function gd_show_mylist_btn($atts)
{
    global $wpdb, $var_setting, $templates_html;
    $locale = get_locale();

    extract(shortcode_atts(array(
        'styletarget' => null, //default
        'item_id' => null,
        'echo' => false,
    ), $atts));

    $gd_query = null;
    $user_id = get_current_user_id();
    if ($user_id == 0 && $var_setting['login_request'] == 'no') {
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
    $gd_sql = 'SELECT * FROM '.$var_setting['table'].'
                WHERE item_id = '.$item_id.' AND user_id = '.$user_id;

    $gd_query = $wpdb->get_results($gd_sql);

    if ($user_id > 0) {
        if ($gd_query != null) {
            //in mylist
            $html = file_get_contents($templates_html['btn_remove'].$locale);
            $html = str_replace('##itemID##', $item_id, $html);
            $html = str_replace('##TARGET##', $styletarget, $html);
            $html = str_replace('##NONCE##', wp_create_nonce('gd_mylist'), $html);
            $html = str_replace('##userID##', $user_id, $html);
        } else {
            $html = file_get_contents($templates_html['btn_add'].$locale);
            $html = str_replace('##itemID##', $item_id, $html);
            $html = str_replace('##TARGET##', $styletarget, $html);
            $html = str_replace('##NONCE##', wp_create_nonce('gd_mylist'), $html);
            $html = str_replace('##userID##', $user_id, $html);
        }
    } else {
        //chek if allow use in no login case
        //must to be login
        $html = file_get_contents($templates_html['btn_login'].$locale);
    }

    if ($echo == true) {
        echo $html;
    } else {
        return $html;
    }
}

//show my list in page

add_action('gd_mylist_list', 'gd_show_gd_mylist_list');
add_shortcode('show_gd_mylist_list', 'gd_show_gd_mylist_list', 10, 2); //shortcode call [show_gd_mylist_list]

function gd_show_gd_mylist_list($atts)
{
    global $wpdb, $var_setting, $templates_html;
    $posts = null;
    $user_id = get_current_user_id();
    $locale = get_locale();
    $lang = substr($locale, 0, 2);
    $user_id_share = @$_GET['wish'];

		//whatsapp get id
		$url = $_SERVER['REQUEST_URI'];
		$arUrl = explode('wish_', $url);
		if ($arUrl[1]) {
			$user_id_share = $arUrl[1];
		}

    extract(shortcode_atts(array(
        'share_list' => 'yes',
        'show_count' => 'yes'
    ), $atts));

    if ($user_id == 0 && $var_setting['login_request'] == 'no') {
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
                    b.post_content AS posts_content,
                    b.post_date AS posts_date,
                    c.ID AS authors_id,
                    c.display_name AS authors_name
                FROM '.$var_setting['table'].' a
                LEFT JOIN '.$var_setting['table_posts'].' b
                ON a.item_id = b.ID
                LEFT JOIN '.$var_setting['table_users']." c
                ON c.ID = b.post_author
                WHERE
                    b.post_status = 'publish'
                    AND a.user_id = %s
                ORDER BY b.post_title DESC",
                $user_id
        )
    );

    if ($posts != null) {
        if ($share_list == 'yes') {
            $html = '';
            $html = file_get_contents($templates_html['box_list_share'].$locale);
            $permalink = get_permalink();
            if (strpos($permalink, '?') !== false) {
                $html = str_replace('##pageID##', $permalink.'&', $html);
            } else {
                $html = str_replace('##pageID##', $permalink.'?', $html);
            }
            $html = str_replace('##userID##', $user_id, $html);

            echo($html);
        }

        if ($show_count == 'yes') {
            $html = '';
            $html = file_get_contents($templates_html['box_list_count'].$locale);
            $count = $wpdb->num_rows;
            $html = str_replace('##count##', $count, $html);

            echo($html);
        }

        foreach ($posts as $post) {
            $postId = $post->posts_id;
            $postDate = get_the_date('F j, Y', $postId);
            $postAuthorId = $post->authors_id;
            $postAuthorName = $post->authors_name;
            $postContent = $post->posts_content;
            $postImage = wp_get_attachment_url(get_post_thumbnail_id($postId));
            $postTitle = $post->posts_title;
            $portTitleLang = extract_title($postTitle);
            $postUrl = get_permalink($postId);
            $args = array(
                'styletarget' => 'mylist',
                'item_id' => $postId,
            );
            $html = '';
            $html = file_get_contents($templates_html['box_list'].$locale);
            $html = str_replace('##postUrl##', $postUrl, $html);
            $html = str_replace('##postImage##', $postImage, $html);
            if (strpos($postTitle, '<!--:') !== false || strpos($postTitle, '[:') !== false) { //means use mqtranlate or qtranlate-x
                    $html = str_replace('##postTitle##', $portTitleLang[$lang], $html);
            } else {
                $html = str_replace('##postTitle##', $postTitle, $html);
            }
            $html = str_replace('##postDate##', $postDate, $html);
            $html = str_replace('##postAuthorName##', $postAuthorName, $html);
            $html = str_replace('##postContent##', $postContent, $html);
            $html = str_replace('##postBtn##', gd_show_mylist_btn($args), $html);

            echo($html);
        }
    } else {
        $html = file_get_contents($templates_html['box_list_empty'].$locale);
        echo($html);
    }
}

function extract_title($postTitle)
{
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
