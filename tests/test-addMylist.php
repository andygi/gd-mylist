<?php 

class AddMylistTest extends WP_UnitTestCase
{
    public function setUp()
    {
        $this->class_add = new gd_add_mylist();
        $_POST = array();
    }

    public function test_add_mylist() {
        $_POST = array('itemId' => '123', 'userId' => '999');
        $this->class_add = $this
            ->getMockBuilder(gd_add_mylist::class)
            ->setMethods([
                'addMylist'
            ])
            ->getMock();
        $this->class_add->method('addMylist')->willReturn('1');

        $result = $this->class_add->gd_add_mylist();
        $expected = '{"showRemove":{"itemid":"123","styletarget":null,"userid":"999","label":"remove My List","icon":null}}';
        $this->assertEquals($expected, $result);
    }

    public function test_add_mylist_failed() {
        $_POST = array('itemId' => '123', 'userId' => '999');
        $this->class_add = $this
            ->getMockBuilder(gd_add_mylist::class)
            ->setMethods([
                'addMylist'
            ])
            ->getMock();
        $this->class_add->method('addMylist')->willReturn('0');

        $result = $this->class_add->gd_add_mylist();
        $expected = '{"showRemove":{"error":"something went wrong during the update"}}';
        $this->assertEquals($expected, $result);
    }

}