jQuery(document).ready(function ($) {
    //mylist
    //setup variable
    var uriAjax         = (gdMyListAjax.ajaxurl);
    var chunckLoading   = (gdMyListAjax.chunckLoading);
    var chunckBtnLogin  = (gdMyListAjax.chunckBtnLogin);
    var chunckBtnAdd    = (gdMyListAjax.chunckBtnAdd);
    var chunckBtnRemove = (gdMyListAjax.chunckBtnRemove);

    console.log(chunckBtnRemove + " " + chunckBtnAdd);
    //btn add mylist
    $('body').on('click', '.btn-gd-add-mylist', function () {
        var postid      = $(this).data("postid");
        var userid      = $(this).data("userid");
        var nonce       = $(this).data("nonce");
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
            console.log('result: ' + result);
            $("#mylist-" + postid).addClass('btn-gd-remove-mylist').removeClass('btn-gd-add-mylist');
            $("#mylist-" + postid).load(chunckBtnRemove);
        });

    });

    //btn remove mylist
    $('body').on('click', '.btn-gd-remove-mylist', function () {
        var postid      = $(this).data("postid");
        var userid      = $(this).data("userid");
        var nonce       = $(this).data("nonce");
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
            console.log('result: ' + result);
            if (styletarget == 'mylist') {
                $("#mylist-" + postid).closest('.gd-mylist-box').fadeOut(500);
            } else {
                $("#mylist-" + postid).addClass('btn-gd-add-mylist').removeClass('btn-gd-remove-mylist');
                $("#mylist-" + postid).load(chunckBtnAdd);
            }
        });

    });

});
