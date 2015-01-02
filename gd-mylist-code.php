<?php

##### mylist

/* NOTE
 * $styletarget: it used to make 'remove button' different behaviours into jquery it'll is valued by code into theme
 * values: 
 *      mylist //cancel div with class: gd-mylist-box
 */

//setup general variables
global $wpdb, $var_setting, $templates_html, $template_path;

    //db variables and settings
    $db_prefix = $wpdb->prefix;
    $var_setting = array (
        'table' => $db_prefix."gd_mylist",
        'table_posts' => $db_prefix."posts",
        'table_users' => $db_prefix."users",
        'login_request' => 'no',
        'guest_user' => rand(100000000000,999999999999).'001',
        'id_user' => get_current_user_id()
    );

    //template variable
    $template_path = plugins_url().'/gd-mylist/template/'; //change this path to use a different template remember to replay all files with all require varibales and syntax

    $templates_html = array (
        'btn_add'           => $template_path . 'btn-add.html',
        'btn_remove'        => $template_path . 'btn-remove.html',
        'btn_login'         => $template_path . 'btn-login.html',
        'box_list'          => $template_path . 'box-list.html',
        'box_list_empty'    => $template_path . 'box-list-empty.html'
    );

add_action( 'init', 'gd_setcookie' );
function gd_setcookie() {
    global $var_setting;
    if (!isset($_COOKIE['gb_mylist_guest'])) {
        $id_guest = $var_setting['guest_user'];
        setcookie("gb_mylist_guest", $id_guest, time()+3600, COOKIEPATH, COOKIE_DOMAIN);
    }
}

//setup assets
add_action( 'init', 'gd_mylist_asset' );
function gd_mylist_asset(){
    global $template_path;
    wp_register_script( 'gd_mylist_script', plugins_url() . '/gd-mylist/js/gd-script.js', array('jquery') );
    wp_localize_script( 'gd_mylist_script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'uriPlugin' => $template_path));
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'gd_mylist_script' );
    wp_enqueue_style( 'font-awesome.min', '//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css' );
}

//add mylist function

add_action("wp_ajax_gd_add_mylist", "gd_add_mylist");
add_action("wp_ajax_nopriv_gd_add_mylist", "gd_add_mylist"); //login check

function gd_mylist_login(){
   echo "Please login before";
   die();
}

function gd_add_mylist() {
    
    if( !wp_verify_nonce( $_REQUEST['nonce'], "gd_mylist")) {!
        exit("No naughty business please");
    }
    
    global $wpdb, $var_setting;
    $item_id = $_POST['itemId'];
    $user_id = $_POST['userId'];
    $result = array();

    $wpdb->query(
        $wpdb->prepare( "
                INSERT INTO ".$var_setting['table']."
                    (`item_id`, `user_id`) 
                VALUES 
                    ('%d', '%d');
                " , 
                $item_id,
                $user_id     
                    )
                );
    
    
    $result['type'] = "success";
    
    $result = json_encode($result);
    echo $result;
    
    die();
}

//remove from mylist function

add_action("wp_ajax_gd_remove_mylist", "gd_remove_mylist");
add_action("wp_ajax_nopriv_gd_remove_mylist", "gd_remove_mylist"); //login check

function gd_remove_mylist() {
    
    if( !wp_verify_nonce( $_REQUEST['nonce'], "gd_mylist")) {!
        exit("No naughty business please");
    }
    
    global $wpdb, $var_setting;
    $item_id = $_POST['itemId'];
    $user_id = $_POST['userId'];
    $result = array();
    
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM ".$var_setting['table']."
                WHERE item_id = %d AND user_id = %d",
                $item_id,
                $user_id
        )
    );
    
    $result['type'] = "success";
    
    $result = json_encode($result);
    echo $result;
    
    die();
}

//show button add/remove

add_action('gd_mylist_btn', 'gd_show_mylist_btn', 10, 2); /* eg code call into theme: <?php do_action('gd_mylist_btn', 'mylist'); ?> */
add_shortcode( 'show_gd_mylist_btn', 'gd_show_mylist_btn' ); /* eg shortcode call: [show_gd_mylist_btn] */
 
function gd_show_mylist_btn($styletarget = null, $item_id = null ) {
    
    global $wpdb, $var_setting, $templates_html;
    
    $gd_query = null;
    $user_id = $var_setting['id_user'];
    if ($user_id == 0 && $var_setting['login_request'] == 'no') {
        $user_id = $_COOKIE['gb_mylist_guest'];
    }
    if ($item_id == null) {
        $item_id = get_the_id();
    }
    
    //check if item is in mylist
    $gd_sql = "SELECT * FROM ".$var_setting['table']." 
                WHERE item_id = ".$item_id." AND user_id = ".$user_id;
    
    $gd_query = $wpdb->get_results($gd_sql);
    
    if ($user_id > 0) {
        if ($gd_query != null) {
            //in mylist
            $html = file_get_contents($templates_html['btn_remove']);
            $html = str_replace("##itemID##", $item_id, $html);
            $html = str_replace("##TARGET##", $styletarget, $html);
            $html = str_replace("##NONCE##", wp_create_nonce("gd_mylist"), $html);
            $html = str_replace("##userID##", $user_id, $html);
        } else {
            $html = file_get_contents($templates_html['btn_add']);
            $html = str_replace("##itemID##", $item_id, $html);
            $html = str_replace("##TARGET##", $styletarget, $html);
            $html = str_replace("##NONCE##", wp_create_nonce("gd_mylist"), $html);
            $html = str_replace("##userID##", $user_id, $html);
        }
    } else {
        //chek if allow use in no login case
        if ($var_setting['login_request'] == 'yes') {
            //must to be login
            $html = file_get_contents($templates_html['btn_login']);
        } else {            
            if ($gd_query != null) {
                //in mylist
                $html = file_get_contents($templates_html['btn_remove']);
                $html = str_replace("##itemID##", $item_id, $html);
                $html = str_replace("##TARGET##", $styletarget, $html);
                $html = str_replace("##NONCE##", wp_create_nonce("gd_mylist"), $html);
                $html = str_replace("##userID##", $user_id, $html);
            } else {
                $html = file_get_contents($templates_html['btn_add']);
                $html = str_replace("##itemID##", $item_id, $html);
                $html = str_replace("##TARGET##", $styletarget, $html);
                $html = str_replace("##NONCE##", wp_create_nonce("gd_mylist"), $html);
                $html = str_replace("##userID##", $user_id, $html);
            }
        }
    }
    
    return($html);
    
}

//show my list in page

add_action('gd_mylist_list', 'gd_show_gd_mylist_list');
add_shortcode( 'show_gd_mylist_list', 'gd_show_gd_mylist_list' ); //shortcode call [show_gd_mylist_list]

function gd_show_gd_mylist_list() {
    global $wpdb, $var_setting, $templates_html;
    $posts = null;
    $user_id = $var_setting['id_user'];
    
    if ($user_id == 0 && $var_setting['login_request'] == 'no') {
        $user_id = $_COOKIE['gb_mylist_guest'];
    }
    
    $posts = $wpdb->get_results( 
        $wpdb->prepare(
            "SELECT
                    b.ID AS posts_id,
                    b.post_title AS posts_title,
                    b.post_content AS posts_content, 
                    b.post_date AS posts_date, 
                    c.ID AS authors_id, 
                    c.display_name AS authors_name 
                FROM ".$var_setting['table']." a
                LEFT JOIN ".$var_setting['table_posts']." b
                ON a.item_id = b.ID
                LEFT JOIN ".$var_setting['table_users']." c 
                ON c.ID = b.post_author
                WHERE 
                    b.post_status = 'publish'  
                    AND a.user_id = %d
                ORDER BY b.post_title DESC",
                $user_id
        )
    );
    
    if ($posts != null) {
    
        foreach ($posts as $post) {
            $postId = $post->posts_id;
            $postDate = get_the_date('F j, Y', $postId);
            $postAuthorId = $post->authors_id;
            $postAuthorName = $post->authors_name;
            $postContent = $post->posts_content;
            $postImage = wp_get_attachment_url( get_post_thumbnail_id($postId));
            $postTitle = $post->posts_title;
            $postUrl = get_permalink($postId);
            $html = '';

                $html = file_get_contents($templates_html['box_list']);
                $html = str_replace("##postUrl##", $postUrl, $html);
                $html = str_replace("##postImage##", $postImage, $html);
                $html = str_replace("##postTitle##", $postTitle, $html);
                $html = str_replace("##postDate##", $postDate, $html);
                $html = str_replace("##postAuthorName##", $postAuthorName, $html);
                $html = str_replace("##postContent##", $postContent, $html);
                $html = str_replace("##postBtn##", gd_show_mylist_btn('mylist',$postId), $html);
        }
    } else {
        $html = file_get_contents($templates_html['box_list_empty']);
    }
    
    echo($html);
}
