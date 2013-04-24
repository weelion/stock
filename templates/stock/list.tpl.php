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
                <li><a href="<?php echo home_url() . module_url('index'); ?>" title="库存管理">库存管理</a></li>
                <li class="current"><a href="<?php echo home_url(); ?>?m=stock&a=list&t=<?php echo $t;?>" title="<?php echo $this_series['title']; ?>系列"><?php echo $this_series['title']; ?>系列</a></li>
            </ul>
        </div>

        <div class="breadLinks">
             <div class="clear"></div>
        </div>
        
    </div>
    <!-- Breadcrumbs line ends -->

    <div class="wrapper">

        <?php tips(); ?>

        <?php //block('search'.$t); ?>
        <div class="widget fluid">
            <div class="whead"><h6><?php echo $this_series['title']; ?>系列</h6><div class="clear"></div></div>
            <div class="hiddenpars">
                <div class="cOptions">
                    <a href="javascript:;" class="tOptions" id="add">
                        <span class="icon-plus-2" style="margin:0; padding:0; color: #666"></span>
                    </a>
                </div>
                <div id="stock_list_table_wrapper" class="dataTables_wrapper" role="grid" style="overflow:auto;">
                    <table  style="width:<?php echo $tdata['width']; ?>px" cellpadding="0" cellspacing="0" border="0" class="dTable checkAll dataTable">
                        <thead>
                            <tr role="row">
                                <th><input class="checkAll check checkbox_id" type="checkbox" key="stock_list_table_wrapper"></th>
                                <?php 
                                foreach ($this_series['data'] as $value) {
                                    $width = !empty($value['width']) ? ' style="' .$value['width'] .'px;"' : '';
                                    echo '<th class="sorting_disabled" tabindex="0" rowspan="1" colspan="1" '.$width.'>' . $value['name'] . '</th>';
                                }?>
                            </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php
                                $i = 0;
                                foreach ($tdata['list'] as $key => $value) {
                                    $i++;
                                    $class = ($i%2 == 0) ? 'even' : 'odd';
                                    echo '<tr class="'.$class.'" sid="'.$value['id'].'">';
                                    if(isset($value['id']) && !empty($value['id'])) {
                                        $id = $value['id'];
                                        echo '<td><input type="checkbox" class="check" value="'.$id.'" name="ids[]"></td>';
                                        unset($value['id']);
                                    }

                                    $set = array();
                                    foreach($value as $k => $v) {
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
                                            echo '<td>' . $v . '</td>';
                                        }
                                    }
                                    echo '</tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="fg-toolbar tableFooter">
                    <input class="checkAll check" key="stock_list_table_wrapper" type="checkbox">
                    <a href="javascript:;" class="buttonH bRed" id="delete">删除</a>
                    <select name="pagesize" id="pagesize">
                        <option value="10" <?php if(get('pagesize') == 10) echo ' selected'; ?>>每页10条</option>
                        <option value="20" <?php if(get('pagesize') == 20) echo ' selected'; ?>>每页20条</option>
                        <option value="50" <?php if(get('pagesize') == 50) echo ' selected'; ?>>每页50条</option>
                        <option value="100" <?php if(get('pagesize') == 100) echo ' selected'; ?>>每页100条</option>
                    </select>
                    <?php $tdata['pagination']->render();?>
                </div>
            </div>
        </div>

        <?php block('addstock'); ?>
        <?php block('editstock'); ?>
        <div id="del_dialog" class="fluid" style="display: none" title="删除库存">
            <p>删除这些库存信息？</p>
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
                param += '&' + field + '=' + value;
            });

            var href = "<?php echo home_url() . module_url();?>" + param;

            location.href = href;
        });

        // 添加库存
        var add_dialog = $('#add_dialog');
        add_dialog.dialog({
            autoOpen: false,
            width: "60%",
            modal: true,
            close: function() {
                $('#add_form')[0].reset();
            }
        });

        $('#add').click(function() {
            add_dialog.dialog('open');
        });

        // 新增保存
        $('#add_save').click(function() {

            // 范围
            var range = eval('(' + '<?php echo $tdata['range']; ?>' + ')');

            var inputs = $('#add_form input');
            if(check_form(inputs, range)){
                $('#add_form').submit();
            }
        });

        // 编辑库存
        var edit_dialog = $('#edit_dialog');
        edit_dialog.dialog({
            autoOpen: false,
            width: "60%",
            modal: true,
            close: function() {
                $('#edit_form input[name="total_type"]').val(0);
                $('.ui-type').removeAttr('style');
                $('#edit_form')[0].reset();
            }
        });

        $('#stock_list_table_wrapper > table > tbody > tr').dblclick(function() {
            var id = $(this).attr('sid');
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '<?php echo home_url(); ?>?m=stock&a=info&t=<?php echo $t; ?>&id=' + id,
                success: function(data) {
                    for (var k in data) {
                        $('#edit_form  .text[name="'+k+'"]').val(data[k]);
                    };

                    $('#edit_form input[name="id"]').val(id);
                    edit_dialog.dialog('open');
                }
            });

        });

        // 编辑保存
        $('#edit_save').click(function() {

            // 数量
            var total = $('#edit_form input[name="total"]').val().trim();

            if(total.length > 0) {
                var ttype = $('#edit_form input[name="total_type"]').val();
                if(ttype == 0) {
                    $.jGrowl('修改库存数量必须选择增加或者减少。');
                    return false;
                }
            }

            // 范围
            var range = eval('(' + '<?php echo $tdata['range']; ?>' + ')');

            var inputs = $('#edit_form input');
            if(check_form(inputs, range)){
                $('#edit_form').submit();
            }
        });


        // 删除确认
        var del_dialog = $('#del_dialog');
        del_dialog.dialog({
            autoOpen: false,
            width: "40%",
            modal: true,
            buttons: {
                "确定": function() {
                    var ids = $('#del_dialog').attr('del-data');
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: '<?php echo home_url(); ?>?m=stock&a=doDel&t=<?php echo $t; ?>',
                        data: {ids: ids.split(',')},
                        success: function(data) {
                            if(data == 1) {
                                location.href = location.href;
                            }
                        }
                    });
                },
                "取消": function() {
                    $(this).dialog( "close" );
                },
            }
        });

        // 删除
        $('#delete').click(function() {
            var ids = new Array();
            var i = 0;
            $(':checkbox[name="ids[]"]:checked').each(function() {
                ids[i] = $(this).val();
                i++;
            });

            if(ids.length < 1) {
                $.jGrowl('请先选择要删除的库存记录。');
                return false;
            }

            var ids = ids.join(',');
            $('#del_dialog').attr('del-data', ids);
            del_dialog.dialog('open');
        });


        // bin自动完成
        $('#add_dialog input[name="pno"], #add_dialog input[name="bin"]').keyup(function() {
            binAutoComplate();
        });

        // code自动完成
        $('#add_dialog input[name="code"]').keyup(function() {
            codeAutoComplate();
        });

    });


    function check_form(inputs, range) {

        var flat  = true;

        inputs.each(function() {
            var val = $(this).val();

            if(val.length > 0) {
                var name = $(this).attr('name');
                var field = $(this).parent().prev().find('span').text();

                // 范围验证
                var re = /^\d+\.?\d?\-\d+\.?\d?$/;
                for( i in range ) {
                    if(name == range[i]) {
                        if(!re.test(val)) {
                            $.jGrowl(field + '格式不正确。');
                            flat = false;
                        }
                    }
                }

                // 其他数字表单验证
                var num_fields = new Array('bin', 'current', 'total', 'index');
                re = /^\d+$/
                for (i in num_fields) {
                    if(name == num_fields[i] && !re.test(val)) {
                        field = field.length > 0 ? field : '数量';
                        $.jGrowl(field + '必须为数字。');
                        flat = false;
                    }
                }
            }
        });

        return flat;
    }

    function binAutoComplate() {
        var pno = $('#add_dialog input[name="pno"]').val().trim();
        var bin = $('#add_dialog input[name="bin"]').val().trim();

        if(pno.length > 0 && bin.length > 0) {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '<?php echo home_url(); ?>?m=bin&a=info&t=<?php echo $t; ?>&pno=' + pno + '&bin=' + bin,
                success: function(data) {
                    for (var k in data) {
                        $('#add_form  .text[name="'+k+'"]').val(data[k]);
                    };
                }
            });
        }
    }

    function codeAutoComplate() {
        var code = $('#add_dialog input[name="code"]').val().trim();

        if(code.length > 0) {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '<?php echo home_url(); ?>?m=bdata&a=info&t=<?php echo $t; ?>&code=' + code ,
                success: function(data) {
                    for (var k in data) {
                        $('#add_form  .text[name="'+k+'"]').val(data[k]);
                    };
                }
            });
        }
    }
</script>
<?php block('footer'); ?>
