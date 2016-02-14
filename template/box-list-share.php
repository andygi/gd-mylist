<?php include('../lib/translate_tool.php'); ?>
<div class="col-sm-12 ml-share-bar">
    <?php echo( __( 'Share your list')); ?>
    <a href="https://twitter.com/share?url=##pageID##wish=##userID##" target="_blank">
        <i class="fa fa-twitter-square"></i>
    </a>
    <a href="whatsapp://send?text=##pageID##wish_##userID##" data-action="share/whatsapp/share" class="ml-mobile">
        <i class="fa fa-whatsapp"></i>
    </a>
    <a href="https://www.facebook.com/sharer/sharer.php?u=##pageID##wish=##userID##" target="_blank">
        <i class="fa fa-facebook-square"></i>
    </a>
    <a href="mailto:?body=##pageID##wish=##userID##" target="_blank">
        <i class="fa fa-envelope"></i>
    </a>
    <a href="##pageID##wish=##userID##" target="_blank">
        <i class="fa fa-external-link"></i>
    </a>
</div>
