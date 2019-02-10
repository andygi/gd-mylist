<?php
/*
Plugin Name: GD Mylist
Plugin URI: https://wordpress.org/plugins/gd-mylist/
Description: Create mylist items of posts and pages
Version: 1.0
Author: Andy Greco
Author URI: http://www.gekode.co.uk
License: GPL
 */

class gd_mylist_plugin
{
    private $config = [
        'is_anonymous_allowed' => 'true',
        'is_add_btn' => 'true',
        'is_fontawesome' => 'true',
        'fontawesome_btn_add' => 'far fa-heart',
        'fontawesome_btn_remove' => 'fas fa-heart',
        'fontawesome_loading' => 'fa fa-spinner fa-pulse',
        'settings_label' => 'gd_mylist_settings',
    ];

    public function __construct()
    {
        global $wpdb;
        $this->var_setting = [
            'template_path' => plugins_url() . '/gd-mylist/template/',
            'table' => $wpdb->prefix . 'gd_mylist',
            'table_posts' => $wpdb->prefix . 'posts',
            'table_users' => $wpdb->prefix . 'users',
            'guest_user' => rand(100000000000, 999999999999) . '001',
        ];

        register_activation_hook(__FILE__, array($this, 'populate_db'));
        register_activation_hook(__FILE__, array(&$this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'depopulate_db'));
        register_uninstall_hook(__FILE__, array($this, 'uninstall'));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'settings_link'));

        //setup assets
        add_action('init', array($this, 'gd_mylist_asset'));

        add_action('init', array($this, 'gd_setcookie'));
        //add mylist function
        add_action('wp_ajax_gd_add_mylist', array($this, 'gd_add_mylist'));
        add_action('wp_ajax_nopriv_gd_add_mylist', array($this, 'gd_add_mylist')); //login check
        //remove from mylist function
        add_action('wp_ajax_gd_remove_mylist', array($this, 'gd_remove_mylist'));
        add_action('wp_ajax_nopriv_gd_remove_mylist', array($this, 'gd_remove_mylist')); //login check
        //show button add/remove
        add_action('gd_mylist_btn', array($this, 'gd_show_mylist_btn'), 11, 2);
        add_shortcode('show_gd_mylist_btn', array($this, 'gd_show_mylist_btn'), 11, 2);
        //show my list in page
        add_action('gd_mylist_list', array($this, 'gd_show_gd_mylist_list'), 11, 2);
        add_shortcode('show_gd_mylist_list', array($this, 'gd_show_gd_mylist_list'), 11, 2);
        // Hook button to the content
        add_filter('the_content', array($this, 'hook_button'), 20);
        // Hook into the admin menu
        add_action('admin_menu', array($this, 'gd_admin_panel'));
        add_action('admin_init', array($this, 'setup_sections'));
    }

    public function uninstall()
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        ob_start();
        require_once "lib/uninstall.php";
        $sql = ob_get_clean();
        dbDelta($sql);
    }

    public function populate_db()
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        ob_start();
        require_once "lib/install-data.php";
        $sql = ob_get_clean();
        dbDelta($sql);
    }

    public function activate()
    {
        $this->update_settings('setup');
    }

    public function depopulate_db()
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        ob_start();
        require_once "lib/drop-tables.php";
        $sql = ob_get_clean();
        dbDelta($sql);
    }

    public function settings_link($links)
    {
        $links = array_merge( array(
            '<a href="' . esc_url( admin_url( '/options-general.php?page=gdmylist_fields' ) ) . '">' . __( 'Settings', 'textdomain' ) . '</a>'
        ), $links );
        return $links;
    }

    public function gd_setcookie()
    {
        if ($this->stored_setting()['is_anonymous_allowed'] === 'true') {
            if (!isset($_COOKIE['gb_mylist_guest'])) {
                $id_guest = $this->var_setting['guest_user'];
                setcookie('gb_mylist_guest', $id_guest, time() + (86400 * 30), COOKIEPATH, COOKIE_DOMAIN);
            }
        } else {
            setcookie('gb_mylist_guest', '', time() - 3600);
        }
    }

    public function gd_mylist_asset()
    {
        $template_path = plugins_url() . '/gd-mylist/template/';

        wp_register_script('gd_mylist_handelbar', plugins_url() . '/gd-mylist/lib/handlebars.min.js', array('jquery'));
        wp_register_script('gd_mylist_script', plugins_url() . '/gd-mylist/js/gd-script.js', array('jquery'));
        wp_localize_script(
            'gd_mylist_script',
            'gdMyListAjax',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'boxList' => $template_path . 'box-list.html',
                'button' => $template_path . 'button.html',
                'nonce' => wp_create_nonce('gd_mylist'),
            )
        );
        wp_enqueue_script('jquery');
        wp_enqueue_script('gd_mylist_script');
        wp_enqueue_script('gd_mylist_handelbar');
        if ($this->stored_setting()['is_fontawesome'] === 'true') {
            // font awesone
            wp_enqueue_style('all.min', plugins_url() . '/gd-mylist/css/all.min.css');
        }
        wp_enqueue_style('gd_mylist_asset', plugins_url() . '/gd-mylist/css/app.css');
    }

    public function gd_add_mylist()
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'gd_mylist')) {
            !exit('No naughty business please');
        }
        global $wpdb;
        $item_id = $_POST['itemId'];
        $user_id = $_POST['userId'];
        $result = array();

        $wpdb->query(
            $wpdb->prepare('
                    INSERT INTO ' . $this->var_setting['table'] . "
                        (`item_id`, `user_id`)
                    VALUES
                        ('%d', '%s');
                    ",
                $item_id,
                $user_id
            )
        );

        $result['showRemove'] = [
            'itemid' => $item_id,
            'styletarget' => null,
            'userid' => $user_id,
            'label' => __('add My List'),
        ];

        print(json_encode($result));

        die();
    }

    public function gd_remove_mylist()
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'gd_mylist')) {
            !exit('No naughty business please');
        }
        global $wpdb;
        $item_id = $_POST['itemId'];
        $user_id = $_POST['userId'];
        $result = array();

        $wpdb->query(
            $wpdb->prepare(
                'DELETE FROM ' . $this->var_setting['table'] . '
                    WHERE item_id = %d AND user_id = %s',
                $item_id,
                $user_id
            )
        );

        $result['showAdd'] = [
            'itemid' => $item_id,
            'styletarget' => null,
            'userid' => $user_id,
            'label' => __('add My List'),
        ];

        print(json_encode($result));

        die();
    }

    public function gd_show_mylist_btn($atts)
    {
        global $wpdb, $templates_html;
        $locale = get_locale();
        $buttonData = [];

        extract(shortcode_atts(array(
            'styletarget' => null, //default
            'item_id' => null,
            'echo' => false,
        ), $atts));

        $gd_query = null;
        $user_id = get_current_user_id();
        if ($user_id === 0 && $this->stored_setting()['is_anonymous_allowed'] === 'true') {
            if (!isset($_COOKIE['gb_mylist_guest'])) {
                $user_id = $this->var_setting['guest_user'];
            } else {
                $user_id = $_COOKIE['gb_mylist_guest'];
            }
        }
        if ($item_id == null) {
            $item_id = get_the_id();
        }

        //check if item is in mylist
        $gd_sql = 'SELECT id FROM ' . $this->var_setting['table'] . '
                    WHERE item_id = ' . $item_id . ' AND user_id = ' . $user_id;

        $gd_query = $wpdb->get_results($gd_sql);

        if ($this->stored_setting()['is_anonymous_allowed'] === 'true') {
            if ($gd_query != null) {
                //in mylist
                // $type = 'btn_remove';
                $buttonData['showRemove'] = [
                    'itemid' => $item_id,
                    'styletarget' => $styletarget,
                    'userid' => $user_id,
                    'label' => __('remove My List'),
                ];
            } else {
                $buttonData['showAdd'] = [
                    'itemid' => $item_id,
                    'styletarget' => $styletarget,
                    'userid' => $user_id,
                    'label' => __('add My List'),
                ];
            }

        } else {
            //chek if allow use in no login case
            //must to be login
            $buttonData['showLogin'] = [
                'message' => __('Please login first'),
                'label' => __('add My List'),
            ];
        }

        echo ('<div class="js-item-mylist" data-id="' . $item_id . '">');
        echo ('<script type="text/javascript">');
        echo ('var myListButton' . $item_id . ' = ');
        echo (json_encode($buttonData));
        echo ('</script>');
        echo ('</div>');
        echo ('<div id="mylist_btn_' . $item_id . '"></div>');
    }

    public function gd_show_gd_mylist_list($atts)
    {
        global $wpdb;
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

        if ($user_id === 0 && $this->stored_setting()['is_anonymous_allowed'] == 'true') {
            $user_id = $_COOKIE['gb_mylist_guest'];
        }

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
                    FROM ' . $this->var_setting['table'] . ' a
                    INNER JOIN ' . $this->var_setting['table_posts'] . ' b
                    ON a.item_id = b.ID
                    INNER JOIN ' . $this->var_setting['table_users'] . " c
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

    public function hook_button($content)
    {
        if (is_page() != 1 && $this->stored_setting()['is_add_btn'] == 'true') {
            $atts = array(
                'styletarget' => null, //default
                'item_id' => null,
                'echo' => false,
            );
            $fullcontent = $this->gd_show_mylist_btn($atts) . $content;
        } else {
            $fullcontent = $content;
        }

        return $fullcontent;
    }

    public function stored_setting()
    {
        $stored_options = get_option($this->config['settings_label']);

        return $stored_options;
    }

    // admin area
    public function gd_admin_panel()
    {
        $page_title = 'GD Mylist Settings';
        $menu_title = 'GD Mylist';
        $capability = 'manage_options';
        $slug = 'gdmylist_fields';
        $callback = array($this, 'plugin_settings_page_content');
        $icon = 'dashicons-admin-plugins';
        $position = 100;

        add_submenu_page('options-general.php', $page_title, $menu_title, $capability, $slug, $callback);
    }

    public function update_settings($status)
    {
        switch ($status) {
            case 'check':
                if (empty($this->stored_setting())) {
                    $message = '<div class="notice error"><p><strong>GD Mylist Plugin:</strong> in order to use correctly the plugin Deactivate and Activate it again.</p></div>';
                }
                break;
            case 'setup':
                $setting = array(
                    'is_anonymous_allowed' => $this->config['is_anonymous_allowed'],
                    'is_fontawesome' => $this->config['is_fontawesome'],
                    'fontawesome_btn_add' => $this->config['fontawesome_btn_add'],
                    'fontawesome_btn_remove' => $this->config['fontawesome_btn_remove'],
                    'fontawesome_loading' => $this->config['fontawesome_loading'],
                    'is_add_btn' => $this->config['is_add_btn'],
                );
                $message = '<div class="updated"><p><strong>Plugin ready</strong></p></div>';
                update_option($this->config['settings_label'], $setting);
                break;
            case 'update':
                $setting = array(
                    'is_anonymous_allowed' => $_POST['is_anonymous_allowed'][0],
                    'is_fontawesome' => $_POST['is_fontawesome'][0],
                    'fontawesome_btn_add' => $_POST['fontawesome_btn_add'],
                    'fontawesome_btn_remove' => $_POST['fontawesome_btn_remove'],
                    'fontawesome_loading' => $_POST['fontawesome_loading'],
                    'is_add_btn' => $_POST['is_add_btn'][0],
                );
                $message = '<div class="updated"><p><strong>Data Updated</strong></p></div>';
                update_option($this->config['settings_label'], $setting);
                break;
            default:
                $message = '<div class="update-nag error">Something goes wrong</div>';
                break;
        }

        echo $message;
    }

    public function plugin_settings_page_content()
    {
        $this->update_settings('check');
        // Lock out non-admins:
        if (!current_user_can('manage_options')) {
            wp_die('You do not have sufficient permission to perform this operation');
        }

        if (isset($_POST['submit'])) {
            $this->update_settings('update');
        }

        // TODO: remove this
        print '<div class="update-nag"><p>';
        print_r($this->stored_setting());
        print '<br>';
        print $this->stored_setting()['is_add_btn'];
        print '</p></div>';

        ?>
        <div class="wrap">
            <h2>GD Mylist</h2>
            <form method="post">
                <?php
settings_fields('gdmylist_fields');
        do_settings_sections('gdmylist_fields');
        submit_button();
        ?>
            </form>
        </div>
        <?php
}

    public function setup_sections()
    {
        add_settings_section('login_request', '<hr>Anonymous user allowed', array($this, 'setup_fields'), 'gdmylist_fields');
        add_settings_section('use_fontawesome', '<hr>Use Fontawesome icon', array($this, 'setup_fields'), 'gdmylist_fields');
        add_settings_section('add_button', '<hr>Add Mylist button', array($this, 'setup_fields'), 'gdmylist_fields');
    }

    public function setup_fields()
    {
        $fields = array(
            array(
                'uid' => 'is_anonymous_allowed',
                'label' => 'Allow anonymous use',
                'section' => 'login_request',
                'type' => 'checkbox',
                'options' => array(
                    'true' => 'Yes',
                ),
                'default' => array($this->stored_setting()['is_anonymous_allowed']),
                'supplimental' => 'Availability to choose if no logger user can use it or not, Mylist cookie will be expired after 30 days',
            ),
            array(
                'uid' => 'is_fontawesome',
                'label' => 'Use Fontawesome icon',
                'section' => 'use_fontawesome',
                'type' => 'checkbox',
                'options' => array(
                    'true' => 'Yes',
                ),
                'default' => array($this->stored_setting()['is_fontawesome']),
                'supplimental' => 'Load Fontawesome CSS in order to use icon class name <a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank">(complete icon’s list)</a>',
            ),
            array(
                'uid' => 'fontawesome_btn_add',
                'label' => 'Add to mylist icon',
                'section' => 'use_fontawesome',
                'type' => 'text',
                'placeholder' => 'css class name',
                'default' => $this->stored_setting()['fontawesome_btn_add'],
                'supplimental' => 'default: <code>far fa-heart</code>',
            ),
            array(
                'uid' => 'fontawesome_btn_remove',
                'label' => 'Remove to mylist icon',
                'section' => 'use_fontawesome',
                'type' => 'text',
                'placeholder' => 'css class name',
                'default' => $this->stored_setting()['fontawesome_btn_remove'],
                'supplimental' => 'default: <code>fas fa-heart</code>',
            ),
            array(
                'uid' => 'fontawesome_loading',
                'label' => 'Loading icon',
                'section' => 'use_fontawesome',
                'type' => 'text',
                'placeholder' => 'css class name',
                'default' => $this->stored_setting()['fontawesome_loading'],
                'supplimental' => 'default: <code>fa fa-spinner fa-pulse</code>',
            ),
            array(
                'uid' => 'is_add_btn',
                'label' => 'Add GD Mylist button',
                'section' => 'add_button',
                'type' => 'checkbox',
                'options' => array(
                    'true' => 'Yes',
                ),
                'default' => array($this->stored_setting()['is_add_btn']),
                'supplimental' => 'Add GD Mylist button directly to the post/article list and detail page. <br><strong>Please note:</strong> the button position and your presence in the posts/articles abstract list it depends on themes you use. In that case you can considering to add it by short code: <a href="https://wordpress.org/plugins/gd-mylist/" target="_blank">more information in the FAQ section</a>',
            ),
        );
        foreach ($fields as $field) {
            add_settings_field($field['uid'], $field['label'], array($this, 'field_callback'), 'gdmylist_fields', $field['section'], $field);
            register_setting('gdmylist_fields', $field['uid']);
        }
    }

    public function field_callback($arguments)
    {
        $value = get_option($arguments['uid']);
        if (!$value) {
            $value = $arguments['default'];
        }
        switch ($arguments['type']) {
            case 'text':
            case 'password':
            case 'number':
                printf('<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value);
                break;
            case 'textarea':
                printf('<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value);
                break;
            case 'select':
            case 'multiselect':
                if (!empty($arguments['options']) && is_array($arguments['options'])) {
                    $attributes = '';
                    $options_markup = '';
                    foreach ($arguments['options'] as $key => $label) {
                        $options_markup .= sprintf('<option value="%s" %s>%s</option>', $key, selected($value[array_search($key, $value, true)], $key, false), $label);
                    }
                    if ($arguments['type'] === 'multiselect') {
                        $attributes = ' multiple="multiple" ';
                    }
                    printf('<select name="%1$s[]" id="%1$s" %2$s>%3$s</select>', $arguments['uid'], $attributes, $options_markup);
                }
                break;
            case 'radio':
            case 'checkbox':
                if (!empty($arguments['options']) && is_array($arguments['options'])) {
                    $options_markup = '';
                    $iterator = 0;
                    foreach ($arguments['options'] as $key => $label) {
                        $iterator++;
                        $options_markup .= sprintf('<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>', $arguments['uid'], $arguments['type'], $key, checked($value[array_search($key, $value, true)], $key, false), $label, $iterator);
                    }
                    printf('<fieldset>%s</fieldset>', $options_markup);
                }
                break;
        }
        if ($helper = $arguments['helper']) {
            printf('<span class="helper"> %s</span>', $helper);
        }
        if ($supplimental = $arguments['supplimental']) {
            printf('<p class="description">%s</p>', $supplimental);
        }
    }

}

new gd_mylist_plugin();
