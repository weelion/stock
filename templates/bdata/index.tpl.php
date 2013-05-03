<?php block('header'); ?>
<?php block('sidebar'); ?>
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>基础数据</span>
        
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="<?php echo home_url(); ?>" title="管理后台">管理后台</a></li>
                <li class="current"><a href="<?php echo home_url(); ?>" title="基础数据">基础数据</a></li>
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
            <?php if(User_Model::has_auth('bdata_list_zm')): ?>
            <li><a href="<?php echo home_url(); ?>?m=bdata&a=list&t=zm" title="照明系列"><span class="iconb" data-icon=""></span><span>照明系列</span></a></li>
            <?php endif; ?>
            <?php if(User_Model::has_auth('bdata_list_zs')): ?>
            <li><a href="<?php echo home_url(); ?>?m=bdata&a=list&t=zs" title="指示系列"><span class="iconb" data-icon=""></span><span>指示系列</span></a></li>
            <?php endif; ?>
            <?php if(User_Model::has_auth('bdata_list_xs')): ?>
            <li><a href="<?php echo home_url(); ?>?m=bdata&a=list&t=xs" title="显示系列"><span class="iconb" data-icon=""></span><span>显示系列</span></a></li>
            <?php endif; ?>
            <?php if(User_Model::has_auth('bdata_list_bg')): ?>
            <li><a href="<?php echo home_url(); ?>?m=bdata&a=list&t=bg" title="背光系列"><span class="iconb" data-icon=""></span><span>背光系列</span></a></li>
            <?php endif; ?>
        </ul>
        
        <div class="divider"><span></span></div>

    </div>
       
    
</div>
<?php block('footer'); ?>
