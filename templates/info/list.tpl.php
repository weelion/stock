<?php
    block('header');
    block('sidebar');
    $t = get('t');
    $series = Config::get('series');
    $this_series = $series[$t];
?>
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-list"></span><?php echo $this_series['title']; ?>系列</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="<?php echo home_url(); ?>" title="管理后台">管理后台</a></li>
                <li><a href="<?php echo home_url() . module_url('index'); ?>" title="库存查询">库存查询</a></li>
                <li class="current"><a href="<?php echo home_url(); ?>?m=info&a=list&t=<?php echo $t;?>" title="<?php echo $this_series['title']; ?>系列"><?php echo $this_series['title']; ?>系列</a></li>
            </ul>
        </div>

        <div class="breadLinks">
             <div class="clear"></div>
        </div>
        
    </div>
    <!-- Breadcrumbs line ends -->

    <div class="wrapper">

        <?php tips(); ?>

        <?php block('search'); ?>
        <div class="widget fluid">
            <div class="whead"><h6><?php echo $this_series['title']; ?>系列</h6><div class="clear"></div></div>
            <div class="hiddenpars">
                <div id="stock_list_table_wrapper" class="dataTables_wrapper" role="grid" style="overflow:auto;">
                    <table  style="width:<?php echo $tdata['width']; ?>px" cellpadding="0" cellspacing="0" border="0" class="dTable dataTable">
                        <thead>
                            <tr role="row">
                                <?php 
                                foreach ($this_series['data'] as $value) {
                                    $width = !empty($value['width']) ? ' style="' .$value['width'] .'px;"' : '';
                                    echo '<th class="sorting_disabled" tabindex="0" rowspan="1" colspan="1" '.$width.'>' . $value['name'] . '</th>';
                                }?>
                            </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php
                                foreach ($tdata['list'] as $key => $value) {
                                    $i++;
                                    echo '<tr sid="'.$value['id'].'">';
                                    if(isset($value['id']) && !empty($value['id'])) {
                                        unset($value['id']);
                                    }

                                    $set = array();
                                    foreach($value as $k => $v) {
                                        if($k=='order'){
                                            break;
                                        }
                                        $k = trim(trim($k, 'min_'), 'max_');
                                        if(in_array($k, $this_series['range']) && !in_array($k, $set)) {
                                            $v = '';
                                            $set[] = $k;
                                            if($value['min_'.$k] != 0 && $value['max_'.$k] != 0) {
                                                
                                                $v = $value['min_'.$k] . '-' . $value['max_'.$k];
                                            }

                                            echo '<td>' . $v . '</td>';
                                        } else if(!in_array($k, $this_series['range'])) {
                                            if($v == 0) $v = '';
                                            if($k=='total'){
                                                echo '<td>' . ($value['order']?'-':'').$v . '</td>';
                                            }else{
                                                echo '<td>' . $v . '</td>';
                                            }
                                        }
                                    }
                                    echo '</tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="fg-toolbar tableFooter">
                    <span>当前条件下的总库存量为：<?php echo $tdata['total'];?>，待出库数量<?php echo $tdata['sell'];?></span>
                    <?php $tdata['pagination']->render();?>
                </div>
            </div>
        </div>
</div>

<script src="<?php echo js_path(); ?>jquery.mCustomScrollbar.concat.min.js"></script>
<link href="<?php echo css_path(); ?>jquery.mCustomScrollbar.css" media="all" type="text/css" rel="stylesheet">
<script type="text/javascript">
    $(function() {

        $(window).load(function(){
            $("#stock_list_table_wrapper").mCustomScrollbar({
                horizontalScroll:true,
                autoHideScrollbar:true,
                mouseWheel:false,
                theme:"light-thin"
            });
        });


        // 滚动
        var table_wrapper = $('.dataTables_wrapper').width();
        var table_width = $('.dTable').width();

        if(table_wrapper > table_width) {
            $('.dTable').css('width', '100%');
        }

        $('#pagesize').change(function(){
            var pagesize = $(this).val();
            var url = location.href.replace(/\&pagesize=\d+/, '');
            url = url.replace(/\&page=\d+/, '&page=1');
            url = url + '&pagesize=' + pagesize;

            location.href = url;
        });


        // 搜索
        $('#search').click(function() {
            var param = '';
            $('.search').each(function() {
                var field = $(this).attr('id');
                var value = $(this).val();
                if(value){
                    param += '&' + field + '=' + value;
                }
            });
            if(!param) {
                $.jGrowl('请填写检索条件');
                return false;
            }
            var href = "<?php echo home_url() . module_url();?>" + param;

            location.href = href;
        });
    });
</script>
<?php block('footer'); ?>
