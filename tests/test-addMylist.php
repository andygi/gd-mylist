<?php

class AddMylistTest extends WP_UnitTestCase
{
    public function setUp()
    {
        $this->class_add = new gd_add_mylist();
        $_POST = array();
    }

    public function test_add_mylist()
    {
        $expected = '{"showRemove":{"itemid":"123","styletarget":null,"userid":"999","label":"remove My List","icon":"fas fa-heart"}}';
        $this->expectOutputString($expected);

        $_POST = array('itemId' => '123', 'userId' => '999');
        $this->class_add = $this
            ->getMockBuilder(gd_add_mylist::class)
            ->setMethods([
                'addMylist',
                'stored_setting'
            ])
            ->getMock();
        $this->class_add->method('addMylist')->willReturn('1');
        $this->class_add->method('stored_setting')->willReturn(['fontawesome_btn_remove' => 'fas fa-heart']);

        $this->class_add->gd_add_mylist();
    }

    public function test_add_mylist_failed()
    {
        $expected = '{"showRemove":{"error":"something went wrong during the update"}}';
        $this->expectOutputString($expected);

        $_POST = array('itemId' => '123', 'userId' => '999');
        $this->class_add = $this
            ->getMockBuilder(gd_add_mylist::class)
            ->setMethods([
                'addMylist',
            ])
            ->getMock();
        $this->class_add->method('addMylist')->willReturn('0');

        $this->class_add->gd_add_mylist();
    }

}
