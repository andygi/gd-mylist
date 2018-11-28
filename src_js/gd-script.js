jQuery(document).ready(function ($) {
    //mylist
    //setup variable
    var uriAjax = (gdMyListAjax.ajaxurl);
    var chunckLoading = (gdMyListAjax.chunckLoading);
    var chunckBtnLogin = (gdMyListAjax.chunckBtnLogin);
    var chunckBtnAdd = (gdMyListAjax.chunckBtnAdd);
    var chunckBtnRemove = (gdMyListAjax.chunckBtnRemove);
    var BtnAdd = (gdMyListAjax.BtnAdd);
    var BtnRemove = (gdMyListAjax.BtnRemove);
    var BtnLogin = (gdMyListAjax.BtnLogin);

    if ($('.js-btn-mylist').length > 0) {
        $('.js-btn-mylist').each(function () {
            var btnId = '#' + $(this).attr('id');
            var typebtn = $(this).data('typebtn');
            var itemurl = $(this).data('itemurl');
            switch (typebtn) {
                case 'btn_remove':
                    $(btnId).load(BtnRemove + '&' + itemurl);
                    break;
                case 'btn_add':
                    $(btnId).load(BtnAdd + '&' + itemurl);
                    break;
                case 'btn_login':
                    $(btnId).load(BtnLogin);
                    break;
            }
        });
    }
    //btn add mylist
    $('body').on('click', '.js-gd-add-mylist', function () {
        var postid = $(this).data("postid");
        var userid = $(this).data("userid");
        var nonce = $(this).data("nonce");
        var styletarget = $(this).data("styletarget");

        $("#mylist-" + postid).load(chunckLoading);

        $.ajax({
            type: "POST",
            dataType: "html",
            url: uriAjax,
            data: {
                action: "gd_add_mylist",
                itemId: postid,
                userId: userid,
                nonce: nonce
            }
        }).done(function (result) {
            $("#mylist-" + postid).addClass('js-gd-remove-mylist').removeClass('js-gd-add-mylist');
            $("#mylist-" + postid).load(chunckBtnRemove);
        });

    });

    //btn remove mylist
    $('body').on('click', '.js-gd-remove-mylist', function () {
        var postid = $(this).data("postid");
        var userid = $(this).data("userid");
        var nonce = $(this).data("nonce");
        var styletarget = $(this).data("styletarget");

        $("#mylist-" + postid).load(chunckLoading);

        $.ajax({
            type: "POST",
            dataType: "html",
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
                $("#mylist-" + postid).addClass('js-gd-add-mylist').removeClass('js-gd-remove-mylist');
                $("#mylist-" + postid).load(chunckBtnAdd);
            }
        });

    });

});