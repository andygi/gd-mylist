jQuery(document).ready( function($) {
//mylist
//setup variable
var uriTemplate = (myAjax.uriPlugin);
var chunckLoading = "chunck-loading.html";
var chunckBtnLogin = "btn-login.html";
var chunckBtnAdd = "chunck-add.html";
var chunckBtnRemove = "chunck-remove.html";
    

    
    //btn add mylist
    $('body').on('click', '.btn-gd-add-mylist', function () {
        var postid = $(this).attr("data-postid");
        var userid = $(this).attr("data-userid");
        var nonce = $(this).attr("data-nonce");
        var styletarget = $(this).attr("data-styletarget");

        console.log(uriTemplate);
                        
        $("#mylist-"+postid).load(uriTemplate + chunckLoading);
        
        $.ajax({
            type: "POST",
            dataType:"json",
            url: myAjax.ajaxurl,
            data: { action: "gd_add_mylist", itemId: postid, userId: userid, nonce: nonce }
        }).done(function() {
            //alert('ok');
            $("#mylist-"+postid).addClass('btn-gd-remove-mylist').removeClass('btn-gd-add-mylist');
            $("#mylist-"+postid).load(uriTemplate + chunckBtnRemove);
        });
        
    });
    
    //btn remove mylist
    $('body').on('click', '.btn-gd-remove-mylist', function () {
        var postid = $(this).attr("data-postid");
        var userid = $(this).attr("data-userid");
        var nonce = $(this).attr("data-nonce");
        var styletarget = $(this).attr("data-styletarget");
        
       $("#mylist-"+postid).load(uriTemplate + chunckLoading);
        
        $.ajax({
            type: "POST",
            dataType:"json",
            url: myAjax.ajaxurl,
            data: { action: "gd_remove_mylist", itemId: postid, userId: userid, nonce: nonce }
        }).done(function() {
            //alert('ok');
            if (styletarget == 'mylist') {
                $("#mylist-"+postid).closest('.gd-mylist-box').fadeOut(500);
            } else {
                $("#mylist-"+postid).addClass('btn-gd-add-mylist').removeClass('btn-gd-remove-mylist');
                $("#mylist-"+postid).load(uriTemplate + chunckBtnAdd);
            }
        });
        
    });
    
});