<?php block('header'); ?>
<?php block('sidebar'); ?>
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-home"></span>库存管理</span>
        
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="<?php echo home_url(); ?>" title="管理后台">管理后台</a></li>
                <li class="current"><a href="<?php echo home_url(); ?>" title="库存管理">库存管理</a></li>
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
            <?php if(User_Model::has_auth('stock_list_zm')): ?>
            <li><a href="<?php echo home_url(); ?>?m=stock&a=list&t=zm" title="照明系列"><span class="iconb" data-icon=""></span><span>照明系列</span></a></li>
            <?php endif; ?>
            <?php if(User_Model::has_auth('stock_list_zs')): ?>
            <li><a href="<?php echo home_url(); ?>?m=stock&a=list&t=zs" title="指示系列"><span class="iconb" data-icon=""></span><span>指示系列</span></a></li>
            <?php endif; ?>
            <?php if(User_Model::has_auth('stock_list_xs')): ?>
            <li><a href="<?php echo home_url(); ?>?m=stock&a=list&t=xs" title="显示系列"><span class="iconb" data-icon=""></span><span>显示系列</span></a></li>
            <?php endif; ?>
            <?php if(User_Model::has_auth('stock_list_bg')): ?>
            <li><a href="<?php echo home_url(); ?>?m=stock&a=list&t=bg" title="背光系列"><span class="iconb" data-icon=""></span><span>背光系列</span></a></li>
            <?php endif; ?>
        </ul>
        
        <div class="divider"><span></span></div>

    </div>
       
    
</div>
<?php block('footer'); ?>
