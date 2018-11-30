<?php include('../lib/translate_tool.php'); ?>
<a href="javascript:void();" 
    class="btn btn-default js-gd-add-mylist" 
    id="mylist-<?php echo $_GET['itemid']; ?>" 
    data-postid="<?php echo $_GET['itemid']; ?>" 
    data-styletarget="<?php echo $_GET['styletarget']; ?>" 
    data-userid="<?php echo $_GET['userid']; ?>" >
    <i class="far fa-heart"></i> <?php echo( __( 'add My List')); ?>
</a>
