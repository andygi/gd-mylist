jQuery(document).ready(function ($) {
    //mylist
    //setup variable
    var BUTTON = '#mylist_btn_',
        uriAjax = (gdMyListAjax.ajaxurl),
        boxList = (gdMyListAjax.boxList),
        loading_icon = (gdMyListAjax.loading_icon),
        button = (gdMyListAjax.button),
        nonce = (gdMyListAjax.nonce),
        buttonHtml = '';

    if (typeof myListData !== "undefined") {
        $.get(boxList, function(source){
            renderTemplate('#myList_list',source,myListData);
        });
    }

    createBtn();

    //btn add mylist
    $('body').on('click', '.js-gd-add-mylist', function () {
        var postid = $(this).data("postid");
        var userid = $(this).data("userid");
        var itemId = BUTTON + postid;

        showLoading(itemId);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: uriAjax,
            data: {
                action: "gd_add_mylist",
                itemId: postid,
                userId: userid,
                nonce: nonce
            }
        }).done(function (result) {
            renderTemplate(itemId,buttonHtml,result);
        });

    });

    //btn remove mylist
    $('body').on('click', '.js-gd-remove-mylist', function () {
        var postid = $(this).data("postid");
        var userid = $(this).data("userid");
        var styletarget = $(this).data("styletarget");
        var itemId = BUTTON + postid;

        showLoading(itemId);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: uriAjax,
            data: {
                action: "gd_remove_mylist",
                itemId: postid,
                userId: userid,
                nonce: nonce
            }
        }).done(function (result) {
            if (styletarget == 'mylist') {
                $("#mylist-" + postid).closest('.gd-mylist-box').fadeOut(500);
            } else {
                renderTemplate(itemId,buttonHtml,result);
            }
        });

    });

    function createBtn() {
        if ($('.js-item-mylist').length > 0) {
            //get template from file
            $.get(button, function(source){
                buttonHtml = source;
                $('.js-item-mylist').each(function () {
                    // get data
                    var itemId = BUTTON + $(this).data('id');
                    var nameVar = 'myListButton' + $(this).data('id');
                    var data = eval(nameVar);
                    renderTemplate(itemId,source,data);
                });
            });
        }
    }

    function showLoading(itemId) {
        data = $.parseJSON('{"showLoading": {"icon": "'+loading_icon+'"}}');
        renderTemplate(itemId,buttonHtml,data);
    }

    function renderTemplate(itemId,source,data) {
        // render Handlebars template
        var template = Handlebars.compile(source);
        var theCompiledHtml = template(data);
        $(itemId).html(theCompiledHtml);
    }

});
