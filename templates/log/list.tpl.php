<?php block('header');?>
<?php block('sidebar'); ?>
<div id="content">
    <div class="contentTop">
        <?php 

        ?>
        <span class="pageTitle"><span class="icon-user-2"></span>系统系列</span>
        
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="<?php echo home_url(); ?>" title="管理后台">管理后台</a></li>
                <li class="current"><a href="<?php echo home_url(); ?>?m=log&a=index" title="系统日志">系统日志</a></li>
            </ul>
        </div>
        <div class="breadLinks">
             <div class="clear"></div>
        </div>
        
    </div>
    <!-- Breadcrumbs line ends -->


    <div class="wrapper">
        <?php block('searchlog'.$tdata['t']); ?>
        <div class="widget fluid">
            <div class="whead"><h6>日志</h6><div class="clear"></div></div>
            <div class="hiddenpars">
                <div id="logs_list_table_wrapper" class="dataTables_wrapper" role="grid">
                    <table cellpadding="0" cellspacing="0" border="0" class="dTable checkAll dataTable" id="order_list_table" aria-describedby="order_list_table_info" style="width: 100%;">
                        <thead>
                            <tr role="row">
                                <th class="sorting_disabled" tabindex="0" rowspan="1" colspan="1">时间</th>
                                <th class="sorting_disabled" tabindex="0" rowspan="1" colspan="1">类型</th>
                                <th class="sorting_disabled" tabindex="0" rowspan="1" colspan="1">描述</th>
                                <th class="sorting_disabled" tabindex="0" rowspan="1" colspan="1">操作者</th>
                            </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php
                                $types = array(
                                    'user' => '会员',
                                    'system' => '系统',
                                    'stock' =>'库存',
                                    'bin' => 'Bin',
                                    );
                                $i = 0;
                                foreach ($tdata['list'] as $key => $value) :
                                    $i++;
                            ?>
                            <tr class="<?php echo ($i%2 == 0) ? 'even' : 'odd'; ?>">
                                <td><?php echo date('Y-m-d H:i:s', $value['loged_at']); ?></td>
                                <td><?php echo $types[$value['type']]; ?></td>
                                <td><?php echo $value['data'] . $value['extra']; ?></td>
                                <td><?php echo $value['username'] ? $value['username'] : '系统' ?></td>
                            </tr>
                            <?php
                                endforeach;
                            ?>
                    </tbody>
                </table>
                <div class="fg-toolbar tableFooter"><?php $tdata['pagination']->render();?></div></div>
            </div>
        </div>
</div>
<?php block('footer'); ?>
