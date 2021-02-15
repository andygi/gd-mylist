<?php

class gd_mylist_admin extends gd_mylist_plugin
{

    public function __construct()
    {
        if (is_admin()) {
            add_filter('plugin_action_links_' . GDMYLIST_PLUGIN_BASENAME, array($this, 'settings_link'));
            add_action('admin_menu', array($this, 'gd_admin_panel'));
            add_action('admin_init', array($this, 'setup_sections'));
        }
    }

    public function settings_link($links)
    {
        $links = array_merge(array(
            '<a href="' . esc_url(admin_url('/options-general.php?page=gdmylist_fields')) . '">' . __('Settings', 'textdomain') . '</a>',
        ), $links);
        return $links;
    }

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
        $message = '';

        switch ($status) {
            case 'check':
                if (empty($this->stored_setting())) {
                    $message = '<div class="notice error"><p><strong>GD Mylist Plugin:</strong> in order to use correctly the plugin Deactivate and Activate it again.</p></div>';
                }
                break;
            case 'setup':
                $setting = array(
                    'is_anonymous_allowed' => $this->config()['is_anonymous_allowed'],
                    'is_fontawesome' => $this->config()['is_fontawesome'],
                    'fontawesome_btn_add' => $this->config()['fontawesome_btn_add'],
                    'fontawesome_btn_remove' => $this->config()['fontawesome_btn_remove'],
                    'fontawesome_loading' => $this->config()['fontawesome_loading'],
                    'is_add_btn' => $this->config()['is_add_btn'],
                );
                $message = '';
                update_option($this->config()['settings_label'], $setting);
                break;
            case 'update':
                $setting = array(
                    'is_anonymous_allowed' => isset($_POST['is_anonymous_allowed'][0]) ? $_POST['is_anonymous_allowed'][0] : '',
                    'is_fontawesome' => isset($_POST['is_fontawesome'][0]) ? $_POST['is_fontawesome'][0] : '',
                    'fontawesome_btn_add' => isset($_POST['fontawesome_btn_add']) ? $_POST['fontawesome_btn_add'] : '',
                    'fontawesome_btn_remove' => isset($_POST['fontawesome_btn_remove']) ? $_POST['fontawesome_btn_remove'] : '',
                    'fontawesome_loading' => isset($_POST['fontawesome_loading']) ? $_POST['fontawesome_loading'] : '',
                    'is_add_btn' => isset($_POST['is_add_btn'][0]) ? $_POST['is_add_btn'][0] : '',
                );
                $message = '<div class="updated"><p><strong>Data Updated</strong></p></div>';
                update_option($this->config()['settings_label'], $setting);
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

        // Setting page structure
        echo('<div class="wrap">');
        echo('  <h2>GD Mylist</h2>');
        echo('  <form method="post">');
        settings_fields('gdmylist_fields');
        do_settings_sections('gdmylist_fields');
        submit_button();
        echo('  </form>');
        echo('</div>');
    }

    public function setup_sections()
    {
        add_settings_section('login_request', '<hr>' . __('Anonymous user allowed', 'gd-mylist'), array($this, 'setup_fields'), 'gdmylist_fields');
        add_settings_section('use_fontawesome', '<hr>' . __('Use Fontawesome icon', 'gd-mylist'), array($this, 'setup_fields'), 'gdmylist_fields');
        add_settings_section('add_button', '<hr>' . __('Add Mylist button', 'gd-mylist'), array($this, 'setup_fields'), 'gdmylist_fields');
    }

    public function setup_fields()
    {
        $fields = array(
            array(
                'uid' => 'is_anonymous_allowed',
                'label' => __('Use anonymous user', 'gd-mylist'),
                'section' => 'login_request',
                'type' => 'checkbox',
                'options' => array(
                    'true' => __('Yes', 'gd-mylist'),
                ),
                'default' => array($this->stored_setting()['is_anonymous_allowed']),
                'helper' => __('Availability to choose if not logged user can use it or not', 'gd-mylist'),
                'supplimental' => __('Mylist cookie will be expired after 30 days', 'gd-mylist'),
            ),
            array(
                'uid' => 'is_fontawesome',
                'label' => __('Use Fontawesome icon', 'gd-mylist'),
                'section' => 'use_fontawesome',
                'type' => 'checkbox',
                'options' => array(
                    'true' => __('Yes', 'gd-mylist'),
                ),
                'default' => array($this->stored_setting()['is_fontawesome']),
                'helper' => __('Load Fontawesome CSS in order to use icon class name', 'gd-mylist'),
                'supplimental' => '<a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank">(' . __('complete icon\'s list', 'gd-mylist') . ')</a>',
            ),
            array(
                'uid' => 'fontawesome_btn_add',
                'label' => __('Add to Mylist icon', 'gd-mylist'),
                'section' => 'use_fontawesome',
                'type' => 'text',
                'placeholder' => __('css class name', 'gd-mylist'),
                'default' => $this->stored_setting()['fontawesome_btn_add'],
                'helper' => __('Preview', 'gd-mylist') . ' <i class="' . $this->stored_setting()['fontawesome_btn_add'] . '"></i>',
                'supplimental' => __('default', 'gd-mylist') . ': <code>' . $this->config()['fontawesome_btn_add'] . '</code>',
            ),
            array(
                'uid' => 'fontawesome_btn_remove',
                'label' => __('Remove to Mylist icon', 'gd-mylist'),
                'section' => 'use_fontawesome',
                'type' => 'text',
                'placeholder' => __('css class name', 'gd-mylist'),
                'default' => $this->stored_setting()['fontawesome_btn_remove'],
                'helper' => __('Preview', 'gd-mylist') . ' <i class="' . $this->stored_setting()['fontawesome_btn_remove'] . '"></i>',
                'supplimental' => __('default', 'gd-mylist') . ': <code>' . $this->config()['fontawesome_btn_remove'] . '</code>',
            ),
            array(
                'uid' => 'fontawesome_loading',
                'label' => __('Loading icon', 'gd-mylist'),
                'section' => 'use_fontawesome',
                'type' => 'text',
                'placeholder' => __('css class name', 'gd-mylist'),
                'default' => $this->stored_setting()['fontawesome_loading'],
                'helper' => __('Preview', 'gd-mylist') . ' <i class="' . $this->stored_setting()['fontawesome_loading'] . '"></i>',
                'supplimental' => __('default', 'gd-mylist') . ': <code>' . $this->config()['fontawesome_loading'] . '</code> <a href="https://origin.fontawesome.com/how-to-use/on-the-web/styling/animating-icons" target="_blank">more icons</a>',
            ),
            array(
                'uid' => 'is_add_btn',
                'label' => __('Add GD Mylist button', 'gd-mylist'),
                'section' => 'add_button',
                'type' => 'checkbox',
                'options' => array(
                    'true' => __('Yes', 'gd-mylist'),
                ),
                'default' => array($this->stored_setting()['is_add_btn']),
                'helper' => __('Add GD Mylist button directly to the post/article list and detail page.', 'gd-mylist'),
                'supplimental' => __('<strong>Please note:</strong> this feature is depends on of which template you use, in case of issue you can disable it and use Short Code instead. <a href="https://wordpress.org/plugins/gd-mylist/" target="_blank">More information in the FAQ section</a>', 'gd-mylist'),
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
