<div id="sidebar">
    <div class="mainNav">
        <!-- Main nav -->
        <ul class="nav">

            <?php if(User_Model::has_auth('stock_index')): ?>
            <li class="sideBarDrop">
                <a href="<?php echo home_url(); ?>?m=stock&a=index" title="" mark="stock"><img src="<?php echo home_url(); ?>images/tables.png" alt="库存管理"><span>库存管理</span></a>
            </li>
            <?php endif;?>
            <?php if(User_Model::has_auth('bin_index')): ?>
            <li>
                <a href="<?php echo home_url(); ?>?m=bin&a=index" title="" mark="bin"><img src="<?php echo home_url(); ?>images/tables.png" alt="Bin 导入"><span>Bin 导入</span></a>
            </li>
            <?php endif;?>
            <?php if(User_Model::has_auth('bdata_index')): ?>
            <li>
                <a href="<?php echo home_url(); ?>?m=order&a=index" title="" mark="bin"><img src="<?php echo home_url(); ?>images/tables.png" alt="出单管理"><span>出单管理</span></a>
            </li>
            <li>
                <a href="<?php echo home_url(); ?>?m=bdata&a=index" title="" mark="bdata"><img src="<?php echo home_url(); ?>images/tables.png" alt="基础数据"><span>基础数据</span></a>
            </li>
            <?php endif;?>
            <?php if(User_Model::has_auth('info_index')): ?>
            <li>
                <a href="<?php echo home_url(); ?>?m=info&a=index" title="" mark="stock"><img src="<?php echo home_url(); ?>images/tables.png" alt="库存查询"><span>库存查询</span></a>
            </li>
            <?php endif;?>
            <?php if(User_Model::has_auth('user_index')): ?>
            <li>
                <a href="<?php echo home_url(); ?>" title="" mark="user"><img src="<?php echo home_url(); ?>images/ui.png" alt="用户管理"><span>用户管理</span></a>
            </li>
            <?php endif;?>
            <?php if(User_Model::has_auth('user_changepwd')): ?>
            <li>
                <a href="<?php echo home_url(); ?>?m=user&a=changepwd" title="" mark="changepwd"><img src="<?php echo home_url(); ?>images/tables.png" alt="修改密码"><span>修改密码</span></a>
            </li>
            <?php endif;?>
            <?php if(User_Model::has_auth('logs_index')): ?>
            <li>
                <a href="<?php echo home_url(); ?>?m=logs&a=index" title="" mark="logs"><img src="<?php echo home_url(); ?>images/tables.png" alt="系统日志"><span>系统日志</span></a>
            </li>
            <?php endif; ?>
        </ul>
        <!-- Main nav ends -->
    </div>
</div>