<?php 

class gd_mylist_show_button extends WP_UnitTestCase
{
    public function setUp()
    {
        $this->class_show_btn = new gd_show_mylist_btn();
        $_COOKIE['gb_mylist_guest'] = null;
    }

    public function test_show_add_btn() {
        // Guest allowed to use it
        $atts = array(
            'styletarget' => null, //default
            'item_id' => 1,
            'echo' => false,
        );
        $this->class_show_btn = $this
            ->getMockBuilder(gd_show_mylist_btn::class)
            ->setMethods([
                'stored_setting',
                'current_user_id',
                'isInMylist'
            ])
            ->getMock();
        $this->class_show_btn->method('stored_setting')->willReturn([
            'is_anonymous_allowed' => 'true',
            'fontawesome_btn_add' => 'far fa-heart'
        ]);
        $this->class_show_btn->method('current_user_id')->willReturn('590743183073001');
        $this->class_show_btn->method('isInMylist')->willReturn('false');

        $result = $this->class_show_btn->gd_show_mylist_btn($atts);
        $expected = '<div class="js-item-mylist" data-id="1"><script type="text/javascript">var myListButton1 = {"showAdd":{"itemid":1,"styletarget":null,"userid":"590743183073001","label":"add My List","icon":"far fa-heart"}}</script></div><div id="mylist_btn_1"></div>';
        $this->assertEquals($expected, $result);
    }

    public function test_show_btn_with_login_message() {
        // Guest not allowed to use it
        $atts = array(
            'styletarget' => null, //default
            'item_id' => 1,
            'echo' => false,
        );
        $this->class_show_btn = $this
            ->getMockBuilder(gd_show_mylist_btn::class)
            ->setMethods([
                'stored_setting',
                'current_user_id',
                'isInMylist'
            ])
            ->getMock();
        $this->class_show_btn->method('stored_setting')->willReturn([
            'is_anonymous_allowed' => 'false',
            'fontawesome_btn_add' => 'far fa-heart'
        ]);
        $this->class_show_btn->method('current_user_id')->willReturn('590743183073001');
        $this->class_show_btn->method('isInMylist')->willReturn('false');

        $result = $this->class_show_btn->gd_show_mylist_btn($atts);
        $expected = '<div class="js-item-mylist" data-id="1"><script type="text/javascript">var myListButton1 = {"showLogin":{"message":"Please login first","label":"add My List","icon":"far fa-heart"}}</script></div><div id="mylist_btn_1"></div>';
        $this->assertEquals($expected, $result);
    }

    public function test_show_remove_btn() {
        $atts = array(
            'styletarget' => null, //default
            'item_id' => 1,
            'echo' => false,
        );
        $this->class_show_btn = $this
            ->getMockBuilder(gd_show_mylist_btn::class)
            ->setMethods([
                'stored_setting',
                'current_user_id',
                'isInMylist'
            ])
            ->getMock();
        $this->class_show_btn->method('stored_setting')->willReturn([
            'is_anonymous_allowed' => 'true',
            'fontawesome_btn_remove' => 'fas fa-heart'
        ]);
        $this->class_show_btn->method('current_user_id')->willReturn('590743183073001');
        $this->class_show_btn->method('isInMylist')->willReturn('true');

        $result = $this->class_show_btn->gd_show_mylist_btn($atts);
        $expected = '<div class="js-item-mylist" data-id="1"><script type="text/javascript">var myListButton1 = {"showRemove":{"itemid":1,"styletarget":null,"userid":"590743183073001","label":"remove My List","icon":"fas fa-heart"}}</script></div><div id="mylist_btn_1"></div>';
        $this->assertEquals($expected, $result);
    }

}