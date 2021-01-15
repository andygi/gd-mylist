<?php

class RemoveMylistTest extends WP_UnitTestCase
{
    public function setUp()
    {
        $this->class_remove = new gd_remove_mylist();
        $_POST = array();
    }

    public function test_remove_mylist()
    {
        $expected = '{"showAdd":{"itemid":"123","styletarget":null,"userid":"999","label":"add My List","icon":"far fa-heart"}}';
        $this->expectOutputString($expected);

        $_POST = array('itemId' => '123', 'userId' => '999');
        $this->class_remove = $this
            ->getMockBuilder(gd_remove_mylist::class)
            ->setMethods([
                'removeMylist',
                'stored_setting'
            ])
            ->getMock();
        $this->class_remove->method('removeMylist')->willReturn('1');
        $this->class_remove->method('stored_setting')->willReturn(['fontawesome_btn_add' => 'far fa-heart']);

        $this->class_remove->gd_remove_mylist();
    }

    public function test_remove_mylist_failed()
    {
        $expected = '{"showAdd":{"error":"something went wrong during the update"}}';
        $this->expectOutputString($expected);

        $_POST = array('itemId' => '123', 'userId' => '999');
        $this->class_remove = $this
            ->getMockBuilder(gd_remove_mylist::class)
            ->setMethods([
                'removeMylist',
            ])
            ->getMock();
        $this->class_remove->method('removeMylist')->willReturn('0');

        $this->class_remove->gd_remove_mylist();
    }

}
