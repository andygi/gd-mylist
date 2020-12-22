<?php 

class gd_mylist_pluginTest extends WP_UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->class_instance = new gd_mylist_plugin();
        // global $wpdb;
    }

    public function test_current_user_id()
    {
        $result = $this->class_instance->current_user_id();
        $expected = 0;
        $this->assertEquals($expected, $result);
    }

    // public function test_gd_show_mylist_btn() {
    //     $atts = array(
    //         'styletarget' => null, //default
    //         'item_id' => null,
    //         'echo' => false,
    //     );
    //     $result = $this->class_instance->gd_show_mylist_btn($atts);
    // }

}