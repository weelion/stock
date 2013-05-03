<?php block('header'); ?>
<?php block('sidebar'); ?>
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-home"></span>出单管理</span>
        
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="<?php echo home_url(); ?>" title="管理后台">管理后台</a></li>
                <li class="current"><a href="<?php echo home_url(); ?>" title="出单管理">出单管理</a></li>
            </ul>
        </div>

        <div class="breadLinks">
             <div class="clear"></div>
        </div>
        
    </div>
    <!-- Breadcrumbs line ends -->

    <div class="wrapper">
        
        <div class="divider"><span></span></div>
    	
        <!-- Buttons with font icons -->
        <ul class="middleNavA">
            <li><a href="<?php echo home_url(); ?>?m=order&a=list&t=zm" title="照明系列"><span class="iconb" data-icon=""></span><span>照明系列</span></a></li>
            <li><a href="<?php echo home_url(); ?>?m=order&a=list&t=zs" title="指示系列"><span class="iconb" data-icon=""></span><span>指示系列</span></a></li>
            <li><a href="<?php echo home_url(); ?>?m=order&a=list&t=xs" title="显示系列"><span class="iconb" data-icon=""></span><span>显示系列</span></a></li>
            <li><a href="<?php echo home_url(); ?>?m=order&a=list&t=bg" title="背光系列"><span class="iconb" data-icon=""></span><span>背光系列</span></a></li>
        </ul>
        
        <div class="divider"><span></span></div>

    </div>
       
    
</div>
<?php block('footer'); ?>
