<?php block('header');?>

<!-- Main content wrapper begins -->
<div class="errorWrapper">
    <span class="errorNum"><?php echo $tdata['title']; ?></span>
    <div class="errorContent">
        <span class="errorDesc"><span class="icon-warning"></span><?php echo $tdata['desc']; ?></span>
        <div class="fluid">
            <a href="<?php echo home_url(); ?>" title="" class="buttonM bLightBlue grid6">返回首页</a>
            <a href="javascript:history.go(-1);" title="" class="buttonM bRed grid6">返回上一页</a>
        </div>
    </div>
</div>    
<!-- Main content wrapper ends -->

</body>
</html>
