<?php include '../lib/translate_tool.php';?>
<a href="javascript:void();" 
        class="btn btn-default js-gd-remove-mylist" 
        id="mylist-<?php echo $_GET['itemid']; ?>" 
        data-postid="<?php echo $_GET['itemid']; ?>" 
        data-styletarget="<?php echo $_GET['styletarget']; ?>" 
        data-userid="<?php echo $_GET['userid']; ?>" 
        data-nonce="<?php echo $_GET['nonce']; ?>">
        <i class="fas fa-heart"></i> <?php echo (__('remove My List')); ?>
</a>
