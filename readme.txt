=== GDMylist ===
Contributors: AndyGi
Donate link: https://www.gekode.co.uk
Tags: wish list, wishlist, favorites, posts and pages bookmark, bookmark, woocommerce wish list
Requires at least: 3.9.0
Tested up to: 5.6
Stable tag: 1.1.1
Requires PHP: 5.6.32
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

You can create a favourite list of pages or posts or WooCommerce product page in an easy and faster way.

== Description ==

This plugin allows you to create wish lists or bookmark for your website pages, posts or product sheet like WooCommerce shop, and display them on any post or page with simple shortcode or code into your theme as well.

= Features =
**Easy to use**: you can add GDMylist button in many ways:
- activate it in the **setting page**, and you will be able to see the button in any posts/pages/WooCommerce product page immediately
- with **shortcode** in the editor, in that way you can choose which page can show it
- for **developer** with code directly in the template

**Sharing Function**: it is possible to share the list page on Twitter, Email and WhatsApp by activate the function in the setting page.

**Flexible**: by the Setting Page you can change the icons as your needs. The icons are provided by [FontAwesome](https://fontawesome.com/icons)

**For User registered and not**: you can decide how can use this function, if allowed the registered user only or for not registered also. You can control it easily in the Setting Page.

**Developer Friendly**: as developer you can change GDMylist button layout as you need. The template is made with HandlebarJs, and all is two files in */template* directory.

**Multi Language Ready**: GDMylist coming out with translation in English, Italian, Netherlands, French, Spanish, German and Japanize, but you can add more languages in case. All what you need is use the .po and .mo translation template that you can find in */lang* directory

= Development =
* [https://github.com/andygi/gd-mylist](https://github.com/andygi/gd-mylist "https://github.com/andygi/gd-mylist")

== Installation ==

1. Upload plugin .zip file to the `/wp-content/plugins/` directory and unzip
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the control panel or shortcode in your posts and pages to display your **button** or **MyList** (more info in FAQ)
4. You can chouse if the user have to login or not, **the plugin not request to be login by default**

== Frequently Asked Questions ==

= How call myList's button? =

There are three ways by your needs:

**1. By control panel**
By default the button will be add before the post/page content.
That means you will se the button in the post/page list and into the post/page itself.
You can anable/disable this function from the control panel. 
In the admin area go to "Settings/GD Mylist".

**2. By Shortcode**
if you needs a single button in a page or post or product sheet, just write

`[show_gd_mylist_btn]`

in the content

**3. By code into theme**
if you needs to put the buttom in themes code, just write

`<?php
$arg = array (
	'echo' => true
);
do_action('gd_mylist_btn',$arg);
?>`

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

Go to the setting panel (Settings/GD Mylist) and check Yes in case you whant allow the anonymous user.


*Note*

In case has anonymous user, the user's id data will be storage in a cookie by GD-Mylist.
Cookie name is: `gb_mylist_guest`, the expiration date is 30 days, and store only the Guest ID in order the create the list.

= Can I Template customization? =

Yes, I use Handlebars Js as template.
Here you can find the [official documentation](https://handlebarsjs.com/).

The files are in *template* folder:
- box-list.html
- button.html

= Can I change the icons? =

I use **Font Awesome** as icon framework [Font Awesome](https://fontawesome.com/icons).
You can change the class name from Control Panel (Settings/GD Mylist).

= Can I change the text on the button? =

Yes you can, you need to change the relative translation. 
As example, if you want to change **add My List** text for French you need to change it in the *gd-mylist-fr_FR.po* and .mo file as well. 
Please note: keep a copy of this file because it will be overwritten in case of new release.


== Screenshots ==

1. Frontend - MyList Log Bottom Add MyList
2. Frontend - MyList Log Bottom Remove MyList
3. Frontend - MyList show MyList
4. Control Panel

== Changelog ==

= 1.1.1 =
- extend theme compability
- add accessibility tag to button and list template

= 1.1 =
- perfoming improvement
- update **Fontawesome** to v5.15

= 1.0.1 =
* bugfix: allways appear login alert in case of anonymous user function is disable

= 1.0 =
* create setting control panel (find it in Settings/GD Mylist) with the cabality to control: user login, fontawesome icon, hook button to content
* refactoring template files with Handlebars Js
* add the GD MyList button directly to content
* update Fontawesome library with v5.0 free
* performing improvement

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


== Upgrade Notice ==

= 1.1 =
- perfoming improvement
- update **Fontawesome** to v5.15

= 1.0 = 
new functions and fix

= 0.4 =
new functions and fix

= 0.3 =
new functions

= 0.2 =
second release

= 0.1 =
first release