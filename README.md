[![Build Status](https://travis-ci.com/andygi/gd-mylist.svg?branch=master)](https://travis-ci.com/andygi/gd-mylist)

# My Wish List #

You can create a favorite list of pages or posts in easy and faster way.

## Developing ##

For developing please see [related file](developer.md)

## Description ##
This plugin allows you to create wish lists or bookmark for your website pages, posts or product sheet, and display them on any post or page with simple shortcode or code into your theme as well.
It add items by AJAX system and it's check if user is login or not, you can add or remove list only as login user.
GD MyList use Handlebars Js as template framework, and Fontawesome (v5.0 free) as icons.

### Features ###

* [v1.0] create setting control panel (find it in Settings/GD Mylist) with the cabality to control: user login, use font awesome icon, hook button to content directly
* [v1.0] refactoring template files with Handlebars Js. Now are only two files
* [v1.0] add the GD MyList button directly
* [v1.0] update Fontawesome library with v5.0 free
* [v1.0] performing improvement
* [v0.4] **items counter**, active or not (active by default)
* [v0.4] improve share component with: **Twitter**, **Email** and **Whatsapp** (please read note)
* [v0.3] Multilingual support (English, Italian, Nederland [thank you Nick]) with template .pot file
* [v0.3] Support **mqtranslate** and **qtranslate-x**
* [v0.3] You can activate (active by default) **Wishlist share button** on Facebook and as Link with separate template
* Availability to choose if no logger user can use it or not (it is available by default), the wishlist will be expired after 30 days
* You can customize every single buttons/lists by templates
* You can call button and list by shortcode or by php code directly into the template
* It's tested on posts, pages and woocommerce products's pages


### Development ###
* [https://github.com/andygi/gd-mylist](https://github.com/andygi/gd-mylist "https://github.com/andygi/gd-mylist")

## Installation ##

1. Upload plugin .zip file to the `/wp-content/plugins/` directory and unzip
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the shortcode in your posts and pages to display your **button** or **MyList** (more info in FAQ)
4. You can chouse if the user have to login or not, **the plugin not request to be login by default**

## Frequently Asked Questions ##

### How call myList's button? ###

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

```
<?php
$arg = array (
	'echo' => true
);
do_action('gd_mylist_btn',$arg);
?>
```
where do you want that button will show it

**4. In case you need add it in post list or carousel**
In case you need add it in post list page or carousel, it means with multiple thumbnails with add-lo-list button.
You can add it by add the following code in theme code:

```
<?php echo do_shortcode('[show_gd_mylist_btn item_id= '. $customPost->ID . ']'); ?>
```

### How call myList's list? ###

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

### How change login permission? ###

Go to the setting panel (Settings/GD Mylist) and check Yes in case you whant allow the anonymous user.


*Note*

In case has anonymous user, the user's id data will be storage in a cookie by GD-Mylist.
Cookie name is: `gb_mylist_guest`, the expiration date is 30 days, and store only the Guest ID in order the create the list.

### Can I Template customization? ###

Yes, I use Handlebars Js as template.
Here you can find the [official documentation](https://handlebarsjs.com/).

The files are in *template* folder:
- box-list.html
- button.html

### Icon customization ###

I use **Font Awesome** as icon framework [Font Awesome](https://fontawesome.com/icons).
You can change the class name from Control Panel (Settings/GD Mylist).

## Screenshots ##

1. Frontend - MyList Log Bottom Add MyList
2. Frontend - MyList Log Bottom Remove MyList
3. Frontend - MyList show MyList
3. Control Panel

## Changelog ##

### 1.0.2 ###
* refactoring: completly php refactoring with adding unit testing

### 1.0.1 ###
* bugfix: allways appear login alert in case of anonymous user function is disable

### 1.0 ###
* create setting control panel (find it in Settings/GD Mylist) with the cabality to control: user login, fontawesome icon, hook button to content
* refactoring template files with Handlebars Js
* add the GD MyList button directly to content
* update Fontawesome library with v5.0 free
* performing improvement

### 0.4 ###
* add wish items counter
* add Twitter, Whatsapp and Email as share method
* fix share link

### 0.3.2 ###
* fix post title not appare on the list on not Multilingual sites (thank’s ‘svenol’)

### 0.3.2 beta ###
* Multilingual support (English, Italian) with template .pot file
* Support **mqtranslate** and **qtranslate-x**
* You can activate (active by default) **Wishlist share button** on Facebook and as Link with separate template
* Fix call code into template (thank’s ‘nabjoern’)

### 0.2.1 ###
* Fix view problems on wishlist’s list page

### 0.2 ###
* Add login/no login case
* Now you can put the button into the content

### 0.1 ###
* Initial release

## Upgrade Notice ##

### 1.0 ### 
new functions and fix

### 0.4 ###
new functions and fix

### 0.3 ###
new functions

### 0.2 ###
second release

### 0.1 ###
first release
