<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title><?php echo tpl_title(); ?></title>
    <link href="<?php echo css_path(); ?>styles.css" media="all" type="text/css" rel="stylesheet">
    <script src="<?php echo js_path(); ?>jquery.min.js"></script>
    <script src="<?php echo js_path(); ?>jquery-ui.min.js"></script>
    <script src="<?php echo js_path(); ?>bootstrap.js"></script>
    <script src="<?php echo js_path(); ?>jquery.jgrowl.js"></script>
    <script src="<?php echo js_path(); ?>jquery.uniform.js"></script>

    <?php if(isset($_SESSION['s_role'])): ?>
    <script src="<?php echo js_path(); ?>common.js"></script>
    <?php endif; ?>
    <!--[if lte IE 6]>
    <style>
        body {background:#ffffe1;}
        #ie6-warning {width: 960px; position: absolute; left: 50%; top: 50%; margin: -11px 0 0 -480px; text-align: center; z-index: 9999;}
        #ie6-warning p{width:100%; text-align: center; color: #A64949; }
    </style>
    <![endif]-->

</head>
<body>
    <!--[if lte IE 6]>
    <div id="ie6-warning">
        <p>本页面采用CSS3，您正在使用 IE 6 浏览器，在本页面的显示效果可能有差异。请您升级到 <a href="http://www.microsoft.com/china/windows/internet-explorer/" target="_blank">IE 8</a> 或安装以下浏览器
        <a href="http://www.mozillaonline.com/">Firefox</a> / <a href="http://www.google.com/chrome/?hl=zh-CN">Chrome</a> 即可获得最佳浏览效果。</p>
    </div>
    <![endif]-->

    <!-- Top line begins -->
    <div id="top">
        <div class="wrapper">
            <a href="<?php echo home_url(); ?>" title="<?php echo tpl_title(); ?>" class="logo"><img src="<?php echo image_path(); ?>logo.png" alt=""></a>
            <!-- Right top nav -->
            <div class="topNav">
                <?php 
                    if(isset($_SESSION['s_role'])) :
                        $role = Role_Model::getName($_SESSION['s_role']);
                ?>
                <span style="float: left; margin: 10px 10px 0 0; line-height: 28px; color: #ccc">欢迎您！<?php echo $_SESSION['s_username']; ?>，您现在身份是<?php echo $role; ?></span>
                <a style="float: left; margin: 10px 10px 0 0; line-height: 28px; color: #ccc" href="<?php echo home_url(); ?>?m=user&a=logout" title="登出" class="logout">[ 登出 ]</a>
                <?php endif; ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <!-- Top line ends -->