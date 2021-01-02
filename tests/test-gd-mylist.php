<?php 

class gd_mylist extends WP_UnitTestCase
{
    public function setUp()
    {
        $this->class_instance = new gd_mylist_plugin();
        $this->class_show_btn = new gd_show_mylist_btn();
        $_COOKIE['gb_mylist_guest'] = null;
    }

    public function test_isRegistered_user_id()
    {
        wp_set_current_user(1);
        $result = $this->class_instance->current_user_id();
        $expected = 1;
        $this->assertEquals($expected, $result);
    }

    public function test_isGuest_user_id()
    {
        wp_set_current_user(0);
        // $user_id = $this->factory->user->create();
        // wp_set_current_user( $user_id );

        $this->class_instance = $this
            ->getMockBuilder(gd_mylist_plugin::class)
            ->setMethods(['stored_setting','var_setting'])
            ->getMock();
        $this->class_instance->method('stored_setting')->willReturn(['is_anonymous_allowed' => 'true']);
        $this->class_instance->method('var_setting')->willReturn(['guest_user' => '227352855171001']);

        $result = $this->class_instance->current_user_id();
        $expected = '227352855171001';
        $this->assertEquals($expected, $result);
    }

    public function test_isGuest_by_cookie_user_id()
    {
        $_COOKIE['gb_mylist_guest'] = '322236219084001';
        wp_set_current_user(0);
        // $user_id = $this->factory->user->create();
        // wp_set_current_user( $user_id );

        $this->class_instance = $this
            ->getMockBuilder(gd_mylist_plugin::class)
            ->setMethods(['stored_setting','var_setting'])
            ->getMock();
        $this->class_instance->method('stored_setting')->willReturn(['is_anonymous_allowed' => 'true']);
        $this->class_instance->method('var_setting')->willReturn(['guest_user' => '227352855171001']);

        $result = $this->class_instance->current_user_id();
        $expected = '322236219084001';
        $this->assertEquals($expected, $result);
    }

    public function test_show_mylist_btn_GuestOn() {
        $atts = array(
            'styletarget' => null, //default
            'item_id' => 1,
            'echo' => false,
        );
        $this->class_show_btn = $this
            ->getMockBuilder(gd_show_mylist_btn::class)
            ->setMethods([
                'stored_setting',
                'is_user_logged_in',
                'current_user_id'
            ])
            ->getMock();
        $this->class_show_btn->method('stored_setting')->willReturn([
            'is_anonymous_allowed' => 'true',
            'fontawesome_btn_add' => 'far fa-heart'
        ]);
        $this->class_show_btn->method('is_user_logged_in')->willReturn('true');
        $this->class_show_btn->method('current_user_id')->willReturn('590743183073001');

        $result = $this->class_show_btn->gd_show_mylist_btn($atts);
        $expected = '<div class="js-item-mylist" data-id="1"><script type="text/javascript">var myListButton1 = {"showAdd":{"itemid":1,"styletarget":null,"userid":"590743183073001","label":"add My List","icon":"far fa-heart"}}</script></div><div id="mylist_btn_1"></div>';
        $this->assertEquals($expected, $result);
    }

    public function test_show_mylist_btn_GuestOff() {
        $atts = array(
            'styletarget' => null, //default
            'item_id' => 1,
            'echo' => false,
        );
        $this->class_show_btn = $this
            ->getMockBuilder(gd_show_mylist_btn::class)
            ->setMethods([
                'stored_setting',
                'is_user_logged_in',
                'current_user_id'
            ])
            ->getMock();
        $this->class_show_btn->method('stored_setting')->willReturn([
            'is_anonymous_allowed' => 'false',
            'fontawesome_btn_add' => 'far fa-heart'
        ]);
        $this->class_show_btn->method('is_user_logged_in')->willReturn('true');
        $this->class_show_btn->method('current_user_id')->willReturn('590743183073001');

        $result = $this->class_show_btn->gd_show_mylist_btn($atts);
        $expected = '<div class="js-item-mylist" data-id="1"><script type="text/javascript">var myListButton1 = {"showLogin":{"message":"Please login first","label":"add My List","icon":"far fa-heart"}}</script></div><div id="mylist_btn_1"></div>';
        $this->assertEquals($expected, $result);
    }

}