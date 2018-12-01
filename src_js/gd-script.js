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
    var boxList = (gdMyListAjax.boxList);
    var nonce = (gdMyListAjax.nonce);
    // var myListData;

    if (typeof myListData !== "undefined") {
        $.get(boxList, function(source){
            var template = Handlebars.compile(source);
            var theCompiledHtml = template(myListData);
            $('#myList_list').html(theCompiledHtml);
            setTimeout(function(){
                createBtn();
            },0);
        });
    }

    createBtn();

    //btn add mylist
    $('body').on('click', '.js-gd-add-mylist', function () {
        var postid = $(this).data("postid");
        var userid = $(this).data("userid");
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


    function createBtn() {
        if ($('.js-item-mylist').length > 0) {
            $('.js-item-mylist').each(function () {
                console.log($(this).data('id'));
                var nameVar = 'myListButton' + $(this).data('id');
                console.log(eval(nameVar));

                var btnId = '#' + $(this).attr('id');
                var typebtn = $(this).data('typebtn');
                var itemurl = $(this).data('itemurl');
                // console.log(typebtn);
                // console.log(itemurl);
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
    }

});
