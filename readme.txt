=== My Wish List ===
Contributors: AndyGi
Donate link: http://www.gekode.co.uk
Tags: item lists, wish list, wishlist, posts and pages bookmark
Requires at least: 3.9.0
Tested up to: 4.1
Stable tag: 0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

You can create a favorite list of pages or posts in easy and faster way.

== Description ==

This plugin allows you to create wish lists or bookmark for your website pages, posts or product sheet, and display them on any post or page with simple shortcode or code into your theme as well.
It add items by AJAX system and it's check if user is login or not, you can add or remove list only as login user.
GD MyList use bootstrap 3 as grid and css class, and fontawesome as icons.

= Development =
* [https://github.com/andygi/gd-mylist](https://github.com/andygi/gd-mylist "https://github.com/andygi/gd-mylist")

== Installation ==

1. Upload plugin .zip file to the `/wp-content/plugins/` directory and unzip
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the shortcode in your posts and pages to display your **button** or **MyList** (more info in FAQ) 
4. You can chouse if the user have to login or not, **the plugin not request to be login by default**

== Frequently Asked Questions ==

= How call myList's button? =

There are two ways by your needs:

1. by Shortcode
if you needs a single button in a page or post or product sheet, just write 

`[show_gd_mylist_btn]`

in the content

2. by code into theme
if you needs to put the buttom in themes code, just write 

`<?php do_action('gd_mylist_btn'); ?>`

where do you want that button will show it

= How call myList's list? =

yuo can show MyList list by shortcode, just create a page (eg: myList) and put into the content the shortcode

`[show_gd_mylist_list]`

= How change login permission? =

Change the value on row #20 of file  `wp-content/plugins/gd-mylist/gd-mylist.php` the value is `no` **by default**

`from:
	'login_request' => 'no',
to:
	'login_request' => 'yes',
`

Note

In case the user has not logged, the user's id data will be storage in a cookie by GD-Mylist. 

So if the same user made two different wish lists, one before and one after he has logged, the MyList List will be like as new user, because for the plugin the user appears as two different users.

= Can I Template customization? =

Yes, there are different templates in html format, you can find it in "template" folder `wp-content/plugins/gd-mylist/template/...`.
If you want, you can create a new one in different directory just copy **all files** and **change the path** into `gd-mylist-code.php` file `line #21`.
Templates files are:

1. Add MyList button:
	* btn-add.html
	* chunck-add.html (it'll appare just after first click)

2. Remove MyList button:
	* btn-remove.html
	* chunck-remove.html (it'll appare just after first click)

3. Loading status (it'll appare just after first click)
	* chunck-loading.html

4. Add MyList button if you not login
	* btn-login.html (there is a javascript alert)

5. MyList list
	* box-list.html (where there are some items to show)
	* box-list-empty.html (when there list is empty)

= Icon customization =

I use **Font Awesome** as icon framework [Font Awesome](http://fortawesome.github.io/Font-Awesome/ "Font Awesome"), so can change with one of that, just cange call name into templets

= CSS Class =

I use **Bootstrap 3** html and css syntax to create html templates [Bootstrap](http://getbootstrap.com/ "Bootstrap"), but you can change with your framework

= Values =

Every templates has simple syntax to target variables, variables list in deep:

* Button Template (all are required)
	* ##itemID##
	* ##TARGET##
	* ##NONCE##
	* ##userID##
	* class="btn-gd-remove-mylist" (to remove button)
	* class="btn-gd-add-mylist" (to add button)

Minimal **button** html syntax (eg: remove button):

`<a href="javascript:void();" class="btn-gd-remove-mylist" id="mylist-##itemID##" data-postid="##itemID##" data-styletarget="##TARGET##" data-userid="##userID##" data-nonce="##NONCE##">remove My List</a>`

* List Template
	* ##postUrl##
	* ##postImage##
	* ##postTitle##
	* ##postDate##
	* ##postAuthorName##
	* ##postContent##
	* ##postBtn##
	* class="gd-mylist-box" (required)

Minial **list** html syntax:

`<p class="gd-mylist-box"><a href="##postUrl##">##postTitle##</a> ##postBtn##</p>`

== Screenshots ==

1. Frontend - MyList Log Bottom Add MyList
2. Frontend - MyList Log Bottom Remove MyList
3. Frontend - MyList show MyList

== Changelog ==

= 0.2 =
* Add login/no login case
* Now you can put the button into the content

= 0.1 =
* Initial release

== Upgrade Notice ==

= 0.2 =
first release

= 0.1 =
first release