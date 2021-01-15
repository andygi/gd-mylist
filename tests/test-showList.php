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
            'posts_id' => "1",
            'authors_id' => "0",
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
        $this->class_show->method('current_user_id')->willReturn("999");

        $result = $this->class_show->list_item($post);
        $expected = [
            'postId' => "1",
            'posturl' => false,
            'postimage' => false,
            'posttitle' => 'post title',
            'postdate' => false,
            'postAuthorName' => 'John Smith',
            'showRemove' => [
                'itemid' => "1",
                'styletarget' => 'mylist',
                'userid' => "999",
                'label' => 'remove My List',
                'icon' => 'fas fa-heart',
            ],
        ];
        $this->assertSame($expected, $result);
    }

    public function test_showList_empty()
    {
        $expected = '<script type="text/javascript">var myListData = {"showEmpty":{"empty_label":"Sorry! Your don\'t have documents."}}</script><div id="myList_list"></div>';
        $this->expectOutputString($expected);

        $this->class_show = $this
            ->getMockBuilder(gd_show_gd_mylist_list::class)
            ->setMethods(['post_query'])
            ->getMock();
        $this->class_show->method('post_query')->willReturn([]);
        $atts = array(
            'share_list' => 'yes',
            'show_count' => 'yes',
        );
        $this->class_show->gd_show_gd_mylist_list($atts);
    }

    public function test_showList_withShareAndCount()
    {
        wp_set_current_user(0);
        $expected = '<script type="text/javascript">var myListData = {"showList":true,"share":{"showShare":true,"share_label":"Share your list","pageid":"?","userid":0},"count":{"showCount":true,"count_label":"Total items","count":0},"listitem":[{"postId":"1","posturl":false,"postimage":false,"posttitle":"post title","postdate":false,"postAuthorName":"John Smith","showRemove":{"itemid":"1","styletarget":"mylist","userid":"999","label":"remove My List","icon":"fas fa-heart"}}]}</script><div id="myList_list"></div>';
        $this->expectOutputString($expected);

        $post = array(
            "0" => (object) [
                "posts_id" => 1,
                "posts_title" => "Hello world!",
                "posts_date" => "2018-11-22 13:48:03",
                "authors_id" => 1,
                "authors_name" => "andygi"
            ]
        );
        $item = [
            'postId' => "1",
            'posturl' => false,
            'postimage' => false,
            'posttitle' => 'post title',
            'postdate' => false,
            'postAuthorName' => 'John Smith',
            'showRemove' => [
                'itemid' => "1",
                'styletarget' => 'mylist',
                'userid' => "999",
                'label' => 'remove My List',
                'icon' => 'fas fa-heart',
            ],
        ];
        $this->class_show = $this
            ->getMockBuilder(gd_show_gd_mylist_list::class)
            ->setMethods(['post_query','list_item'])
            ->getMock();
        $this->class_show->method('post_query')->willReturn($post);
        $this->class_show->method('list_item')->willReturn($item);
        $atts = array(
            'share_list' => 'yes',
            'show_count' => 'yes',
        );
        $this->class_show->gd_show_gd_mylist_list($atts);
    }
}