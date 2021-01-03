<?php 

class gd_mylist_user extends WP_UnitTestCase
{
    public function setUp()
    {
        $this->class_instance = new gd_mylist_plugin();
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

}