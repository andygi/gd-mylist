=== My Wish List ===
Contributors: AndyGi
Donate link: http://www.gekode.co.uk
Tags: item lists, wish list, wishlist, posts and pages bookmark
Requires at least: 3.9.0
Tested up to: 4.1
Stable tag: 0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

You can create a favorite list of pages or posts in easy and faster way.

== Description ==

This plugin allows you to create wish lists or bookmark for your website pages, posts or product sheet, and display them on any post or page with simple shortcode or code into your theme as well.
It add items by AJAX system and it's check if user is login or not, you can add or remove list only as login user.
GD MyList use bootstrap 3 as grid and css class, and fontawesome as icons.

= Features =

* [new] **items counter**, active or not (active by default)
* [new] improve share component with: **Twitter**, **Email** and **Whatsapp** (please read note)
* [v0.3] Multilingual support (English, Italian, Nederland [thank you Nick]) with template .pot file
* [v0.3] Support **mqtranslate** and **qtranslate-x**
* [v0.3] You can activate (active by default) **Wishlist share button** on Facebook and as Link with separate template
* Availability to choose if no logger user can use it or not (it is available by default), the wishlist will be expired after 30 days
* You can customize every single buttons/lists by templates
* You can call button and list by shortcode or by php code directly into the template
* It's tested on posts, pages and woocommerce products's pages


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

`
<?php
$arg = array (
	'echo' => true
);
do_action('gd_mylist_btn',$arg);
?>
`

where do you want that button will show it

= How call myList's list? =

yuo can show MyList list by shortcode, just create a page (eg: myList) and put into the content the shortcode

`[show_gd_mylist_list]`

to disable **share button**

`
[show_gd_mylist_list share_list='no']
`

to disable **count items**

`
[show_gd_mylist_list show_count='no']
`

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

**For whatsapp share**: it is visible only for mobile resolution and it not works with permalink set as plain.

= Can I Template customization? =

Yes, there are different templates in html format, you can find it in "template" folder `wp-content/plugins/gd-mylist/template/...`.
If you want, you can create a new one in different directory just copy **all files** and **change the path** into `gd-mylist-code.php` file variable `$template_path`.
Templates files are:

1. Add MyList button:
	* btn-add.php
	* chunck-add.php (it'll appare just after first click)

2. Remove MyList button:
	* btn-remove.php
	* chunck-remove.php (it'll appare just after first click)

3. Loading status (it'll appare just after first click)
	* chunck-loading.php

4. Add MyList button if you not login
	* btn-login.php (there is a javascript alert)

5. MyList list
	* box-list.php (where there are some items to show)
	* box-list-empty.php (when there list is empty)
	* box-list-share.php (for share button)

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
	* ##pageID##
	* ##userID##
	* class="gd-mylist-box" (required)

Minial **list** html syntax:

`<p class="gd-mylist-box"><a href="##postUrl##">##postTitle##</a> ##postBtn##</p>`

== Screenshots ==

1. Frontend - MyList Log Bottom Add MyList
2. Frontend - MyList Log Bottom Remove MyList
3. Frontend - MyList show MyList

== Changelog ==

= 0.4 =
* add wish items counter
* add Twitter, Whatsapp and Email as share method
* fix share link

= 0.3.2 =
* fix post title not appare on the list on not Multilingual sites (thank’s ‘svenol’)

= 0.3.2 beta =
* Multilingual support (English, Italian) with template .pot file
* Support **mqtranslate** and **qtranslate-x**
* You can activate (active by default) **Wishlist share button** on Facebook and as Link with separate template
* Fix call code into template (thank’s ‘nabjoern’)

= 0.2.1 =
* Fix view problems on wishlist’s list page

= 0.2 =
* Add login/no login case
* Now you can put the button into the content

= 0.1 =
* Initial release

== Upgrade Notice ==

= 0.4 =
new functions and fix

= 0.3 =
new functions

= 0.2 =
second release

= 0.1 =
first release
