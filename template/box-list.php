<?php include '../lib/translate_tool.php';?>
<div class="col-sm-12">
    <div class="thumbnail gd-mylist-box">
        <a href="<?php echo $_GET['posturl']; ?>"><img src="<?php echo $_GET['postimage']; ?>" class="img-book img-responsive"></a>
        <div class="caption">
            <h3><a href="<?php echo $_GET['posturl']; ?>"><?php echo $_GET['posttitle']; ?></a></h3>
            <p><small><?php echo $_GET['postdate']; ?> | <?php echo $_GET['postauthor']; ?></small></p>
            <p><?php echo $_GET['postbtn']; ?></p>
        </div>
    </div>
</div>