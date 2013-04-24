<?php block('header');?>
<?php block('sidebar'); ?>
<div id="content">
    <div class="contentTop">
        <?php 
            $t = get('t');
            $series = Config::get('series');
            $this_series = $series[$t];
        ?>
        <span class="pageTitle"><span class="icon-user-2"></span><?php echo $this_series['title']; ?>基础数据</span>
        
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="<?php echo home_url(); ?>" title="管理后台">管理后台</a></li>
                <li><a href="<?php echo home_url(); ?>?m=bdata&a=index" title="基础数据">基础数据</a></li>
                <li class="current"><a href="<?php echo home_url(); ?>?m=bdata&a=index&t=<?php echo $t; ?>" title="<?php echo $this_series['title']; ?>基础数据"><?php echo $this_series['title']; ?>基础数据</a></li>
            </ul>
        </div>
        <div class="breadLinks">
             <div class="clear"></div>
        </div>
        
    </div>
    <!-- Breadcrumbs line ends -->

    <div class="wrapper">

        <?php tips(); ?>

        <div class="widget fluid">
            <div class="whead"><h6><?php echo $this_series['title']; ?>数据</h6><div class="clear"></div></div>
            <div class="hiddenpars">
                <div class="cOptions">
                    <a href="javascript:;" class="tOptions" id="add">
                        <span class="icon-plus-2" style="margin:0; padding:0; color: #666"></span>
                    </a>
                </div>
                <div id="bdata_list_table_wrapper" class="dataTables_wrapper" role="grid">
                    <table cellpadding="0" cellspacing="0" border="0" class="dTable checkAll dataTable" style="width: 100%;">
                        <thead>
                            <tr role="row">
                                <th><input class="checkAll check checkbox_id" type="checkbox" key="bdata_list_table_wrapper"></th>
                                <th class="sorting_disabled" tabindex="0" rowspan="1" colspan="1" width="31%">物料代码</th>
                                <th class="sorting_disabled" tabindex="0" rowspan="1" colspan="1" width="31%">产品型号</th>
                                <th class="sorting_disabled" tabindex="0" rowspan="1" colspan="1">产品规格</th>
                            </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php
                                $i = 0;
                                foreach ($tdata['list'] as $key => $value) :
                                    $i++;

                            ?>
                            <tr class="<?php echo ($i%2 == 0) ? 'even' : 'odd'; ?>"  sid="<?php echo $value['id']; ?>">
                                <?php 
                                    $id = $value['id'];
                                    echo '<td><input type="checkbox" class="check" value="'.$id.'" name="ids[]"></td>';
                                    unset($value['id']);
                                ?>
                                <td><?php echo $value['code']; ?></td>
                                <td><?php echo $value['model']; ?></td>
                                <td><?php echo $value['spec']; ?></td>
                            </tr>
                            <?php
                                endforeach;
                            ?>
                    </tbody>
                </table>
                <div class="fg-toolbar tableFooter">
                    <input class="checkAll check" key="bdata_list_table_wrapper" type="checkbox">
                    <a href="javascript:;" class="buttonH bRed" id="delete">删除</a>
                    <?php $tdata['pagination']->render();?>
                </div>
            </div>
        </div>
        <div id="add_dialog" class="fluid" style="display: none" title="添加基础数据">
            <form action="<?php echo home_url().module_url('doAdd'); ?>" method="POST" class="main" id="add_form">
                <div class="formRow">
                    <div class="grid2 textR">
                        <span>物料代码：</span>  
                    </div>
                    <div class="grid2">
                        <input name="code" class="text" type="text" value="">
                    </div>
                    <div class="grid2 textR">
                        <span>产品型号：</span>  
                    </div>
                    <div class="grid2">
                        <input name="model" class="text" type="text" value="">
                    </div> 
                    <div class="grid2 textR">
                        <span>规格：</span>  
                    </div>
                    <div class="grid2">
                        <input name="spec" class="text" type="text" value="">
                    </div> 
                    <div class="clear"></div>
                </div>
                <div class="formRow">
                    <div class="grid2" style="float:right;text-align: right;">
                        <a href="javascript:;" class="buttonS bBlue" id="add_save"><span style="color: #fff">保存</span></a>
                    </div>
                    <div class="clear"></div>
                </div>
            </form>
        </div>
        <div id="edit_dialog" class="fluid" style="display: none" title="编辑基础数据">
            <form action="<?php echo home_url().module_url('doEdit'); ?>" method="POST" class="main" id="edit_form">
                <div class="formRow">
                    <div class="grid2 textR">
                        <span>物料代码：</span>  
                    </div>
                    <div class="grid2">
                        <input name="code" class="text" type="text" value="">
                    </div>
                    <div class="grid2 textR">
                        <span>产品型号：</span>  
                    </div>
                    <div class="grid2">
                        <input name="model" class="text" type="text" value="">
                    </div> 
                    <div class="grid2 textR">
                        <span>规格：</span>  
                    </div>
                    <div class="grid2">
                        <input name="spec" class="text" type="text" value="">
                    </div> 
                    <div class="clear"></div>
                </div>
                <div class="formRow">
                    <div class="grid2" style="float:right;text-align: right;">
                        <input name="id" type="hidden"/>
                        <a href="javascript:;" class="buttonS bBlue" id="edit_save"><span style="color: #fff">保存</span></a>
                    </div>
                    <div class="clear"></div>
                </div>
            </form>
        </div>
        <div id="del_dialog" class="fluid" style="display: none" title="删除基础数据">
            <p>删除这些基础数据？</p>
        </div>
</div>
<script type="text/javascript">
    $(function() {

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
            var inputs = $('#add_form input');
            if(check_form(inputs)){
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
                $('#edit_form')[0].reset();
            }
        });

        $('#bdata_list_table_wrapper > table > tbody > tr').dblclick(function() {
            var id = $(this).attr('sid');
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '<?php echo home_url(); ?>?m=bdata&a=info&t=<?php echo $t; ?>&id=' + id,
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
            var inputs = $('#edit_form input');
            if(check_form(inputs)){
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
                        url: '<?php echo home_url(); ?>?m=bdata&a=doDel&t=<?php echo $t; ?>',
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
                $.jGrowl('请先选择要删除的数据记录。');
                return false;
            }

            var ids = ids.join(',');
            $('#del_dialog').attr('del-data', ids);
            del_dialog.dialog('open');
        });
    });

    function check_form(inputs) {
        var flat = true;
        inputs.each(function() {
            var val = $(this).val();
            var field = $(this).parent().prev().find('span').text();
            if(val.length < 1) {
                $.jGrowl(field + '不能为空。');
                flat = false;
            }
        });

        return flat;
    }
</script>
<?php block('footer'); ?>
