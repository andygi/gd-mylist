<?php

class ShowListTest extends WP_UnitTestCase
{
    public function setUp()
    {
        $this->class_show = new gd_show_gd_mylist_list();
    }

    public function test_extract_title()
    {
        $title = "title without mqtranlate or qtranlate-x tag";
        $result = $this->class_show->extract_title($title);
        $expected = "";
        $this->assertEquals($expected, $result);
    }

    public function test_list_items()
    {
        $post = (object) [
            'posts_id' => 1,
            'authors_id' => 0,
            'authors_name' => 'John Smith',
            'posts_title' => 'post title',
        ];
        $this->class_show = $this
            ->getMockBuilder(gd_show_gd_mylist_list::class)
            ->setMethods(['stored_setting','is_anonymous_allowed','current_user_id'])
            ->getMock();
        $this->class_show->method('stored_setting')->willReturn([
            'fontawesome_btn_remove' => 'fas fa-heart',
            'is_anonymous_allowed' => 'true'
        ]);
        $this->class_show->method('current_user_id')->willReturn(999);

        $result = $this->class_show->list_item($post);
        $expected = [
            'postId' => 1,
            'posturl' => false,
            'postimage' => false,
            'posttitle' => 'post title',
            'postdate' => false,
            'postAuthorName' => 'John Smith',
            'showRemove' => [
                'itemid' => 1,
                'styletarget' => 'mylist',
                'userid' => 999,
                'label' => 'remove My List',
                'icon' => 'fas fa-heart',
            ],
        ];
        $this->assertSame($expected, $result);
    }
}