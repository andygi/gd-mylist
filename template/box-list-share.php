<?php include('../lib/translate_tool.php'); ?>
<div class="col-sm-12 ml-share-bar">
    <?php echo( __( 'Share your list')); ?>
    <a href="https://twitter.com/share?url=<?php echo $_GET['pageid']; ?>wish=<?php echo $_GET['userid']; ?>" target="_blank">
        <i class="fab fa-twitter-square"></i>
    </a>
    <a href="whatsapp://send?text=<?php echo $_GET['pageid']; ?>wish_<?php echo $_GET['userid']; ?>" data-action="share/whatsapp/share" class="ml-mobile">
        <i class="fab fa-whatsapp-square"></i>
    </a>
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $_GET['pageid']; ?>wish=<?php echo $_GET['userid']; ?>" target="_blank">
        <i class="fab fa-facebook-square"></i>
    </a>
    <a href="mailto:?body=<?php echo $_GET['pageid']; ?>wish=<?php echo $_GET['userid']; ?>" target="_blank">
        <i class="fas fa-envelope-square"></i>
    </a>
    <a href="<?php echo $_GET['pageid']; ?>wish=<?php echo $_GET['userid']; ?>" target="_blank">
        <i class="fas fa-share-square"></i>
    </a>
</div>
