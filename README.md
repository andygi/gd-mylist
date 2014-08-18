#GD MyList v 0.1

GD MyList is a plugin to create a whistlist or bookmark pages and posts.
You can create a favorite list of pages or posts in easy and faster way.
GD MyList use bootstrap 3 and fortawesome as layout.
It's work only with login users.

##HOW TO USE

After as installed it you can call the button 

###Call myList's button:

There are two ways by your needs:

1. by Shortcode
if you needs a single button in a page or post or product sheet, just write 

    [show_gd_mylist_btn] 
    
    in the content

2. by code into theme
if you needs to put the buttom in themes code, just write 

    <?php do_action('gd_mylist_btn'); ?>

###Call myList's list:

yuo can show MyList list by shortcode, just create a page (es: myList) and put into the content the code
    [show_gd_mylist_list]

###Template customization

There are different template in html, you can find it "template" folder.
There are different type:
1. Add MyList button:
    btn-add.html
    chunck-add.html (it appare only after first click because there is a ajax call)
2. Remove MyList button:
    btn-remove.html
    chunck-remove.html (it appare only after first click because there is a ajax call)
3. Loading status (it'll appare after firt click)
    chunck-loading.html
4. Add MyList button if you not login
    btn-login.html (there is a javascript alert)
5. MyList list
    box-list.html (where there are some items to show)
    box-list-empty.html (when there list is empty)