<?php

class gd_mylist_asset extends gd_mylist_plugin
{

    public function __construct()
    {
        //setup assets
        add_action('init', array($this, 'gd_mylist_asset'));
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
                'loading_icon' => $this->stored_setting()['fontawesome_loading'],
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

}
