<?php

class gd_show_mylist_btn extends gd_mylist_plugin
{

    public function __construct()
    {
        add_action('gd_mylist_btn', array($this, 'gd_show_mylist_btn'), 11, 2);
        add_shortcode('show_gd_mylist_btn', array($this, 'gd_show_mylist_btn'), 11, 2);
        // Hook button to the content
        add_filter('the_content', array($this, 'hook_button'), 20);
    }

    public function gd_show_mylist_btn($atts)
    {
        global $templates_html;
        $locale = get_locale();
        $buttonData = [];

        extract(shortcode_atts(array(
            'styletarget' => null, //default
            'item_id' => null,
            'echo' => false,
        ), $atts));

        $user_id = $this->current_user_id();
        if ($item_id == null) {
            $item_id = get_the_id();
        }

        $gd_dbQuery = new gd_dbQuery();
        $obj = [
            'item_id' => $item_id,
            'user_id' => $user_id
        ];
        $isInMylist = $gd_dbQuery->isInMylist($obj);
        print('----'.$isInMylist);

        if (($this->stored_setting()['is_anonymous_allowed'] === 'true') || is_user_logged_in()) {
            if ($isInMylist === 'true') {
                //in mylist
                // $type = 'btn_remove';
                $buttonData['showRemove'] = [
                    'itemid' => $item_id,
                    'styletarget' => $styletarget,
                    'userid' => $user_id,
                    'label' => __('remove My List'),
                    'icon' => $this->stored_setting()['fontawesome_btn_remove'],
                ];
            } else {
                $buttonData['showAdd'] = [
                    'itemid' => $item_id,
                    'styletarget' => $styletarget,
                    'userid' => $user_id,
                    'label' => __('add My List'),
                    'icon' => $this->stored_setting()['fontawesome_btn_add'],
                ];
            }

        } else {
            //chek if allow use in no login case
            //must to be login
            $buttonData['showLogin'] = [
                'message' => __('Please login first'),
                'label' => __('add My List'),
                'icon' => $this->stored_setting()['fontawesome_btn_add'],
            ];
        }

        $button = ('<div class="js-item-mylist" data-id="' . $item_id . '">');
        $button .= ('<script type="text/javascript">');
        $button .= ('var myListButton' . $item_id . ' = ');
        $button .= (json_encode($buttonData));
        $button .= ('</script>');
        $button .= ('</div>');
        $button .= ('<div id="mylist_btn_' . $item_id . '"></div>');
        return $button;
    }

    public function hook_button($content)
    {
        if (is_page() != 1 && $this->stored_setting()['is_add_btn'] === 'true') {
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

}
