<?php block('header');?>
<?php block('sidebar'); ?>
<div id="content">
    <div class="contentTop">
        <?php 

        ?>
        <span class="pageTitle"><span class="icon-user-2"></span>会员管理</span>
        
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="<?php echo home_url(); ?>" title="管理后台">管理后台</a></li>
                <li class="current"><a href="<?php echo home_url(); ?>?m=users&a=index" title="会员管理">会员管理</a></li>
            </ul>
        </div>
        <div class="breadLinks">
             <div class="clear"></div>
        </div>
        
    </div>
    <!-- Breadcrumbs line ends -->


    <div class="wrapper">
        <div class="widget fluid">
          <div class="formRow">
            <div class="grid1 textR">
              <span>用户名：</span>  
            </div>  
            <div class="grid2">
              <input id="search_username" class="search text" type="text" value="<?php echo get('username');?>">
            </div>
            <div class="grid1">
              <a href="javascript:;" class="buttonS bBlue" id="search"><span>搜索</span></a>
            </div>
            <div class="clear"></div>
          </div>
        </div> 
        <div class="widget fluid">
            <div class="whead"><h6>会员列表</h6><div class="clear"></div></div>          
            <div class="hiddenpars">
                <div class="cOptions">
                    <a href="#" class="tOptions" id="add">
                        <span class="icon-plus-2" style="margin:0; padding:0; color: #666"></span>
                    </a>
                </div>
                <div id="logs_list_table_wrapper" class="dataTables_wrapper" role="grid">
                    <table cellpadding="0" cellspacing="0" border="0" class="dTable checkAll dataTable" id="order_list_table" aria-describedby="order_list_table_info" style="width: 100%;">
                        <thead>
                            <tr role="row">
                                <th class="sorting_disabled" tabindex="0" rowspan="1" colspan="1">帐号</th>
                                <th class="sorting_disabled" tabindex="0" rowspan="1" colspan="1">角色</th>
                                <th class="sorting_disabled" tabindex="0" rowspan="1" colspan="1">操作</th>
                            </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php
                                $i = 0;
                                foreach ($tdata['list'] as $key => $value) :
                                    $i++;
                            ?>
                            <tr class="<?php echo ($i%2 == 0) ? 'even' : 'odd'; ?>" sid="<?php echo $value['uid'];?>" role="<?php echo $value['role'];?>">
                                <td><?php echo $value['username']; ?></td>
                                <td><?php echo $value['name']; ?></td>
                                <td>####</td>
                            </tr>
                            <?php
                                endforeach;
                            ?>
                    </tbody>
                </table>
                <div class="fg-toolbar tableFooter"><?php $tdata['pagination']->render();?></div></div>
            </div>
        </div>
        <div id="add_dialog" class="fluid" style="display: none" title="添加会员">
            <form action="/?m=user&a=doAdd" method="POST" class="main" id="add_form">
                <div class="formRow">
                    <div class="grid2 textR">
                        <span>用户名：</span>  
                    </div>
                    <div class="grid2">
                        <input name="username" class="text" type="text" value="" id="username">
                    </div>  
                    <div class="grid2 textR">
                        <span>密码：</span>  
                    </div>
                    <div class="grid2">
                        <input name="password" class="text" type="password" value="" id="password">
                    </div>  
                    <div class="grid2 textR">
                        <span>角色：</span>  
                    </div>
                    <div class="grid2">
                        <select name="role" id="role">
                            <?php 
                                foreach ($tdata['role'] as $key => $value) {
                                    echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                                }
                            ?>
                        </select>
                    </div>  
                    <div class="clear"></div></div>
                    <div class="formRow">
                    <div class="grid6 textR">
                        <a href="javascript:;" class="buttonM bBlue" id="add_save"><span style="color: #fff">保存</span></a>
                    </div>
                    <div class="clear"></div>
                </div>
            </form>
        </div>
        <div id="edit_dialog" class="fluid" style="display: none" title="编辑会员">
            <form action="/?m=user&a=doEdit" method="POST" class="main" id="edit_form">
                <div class="formRow">
                    <div class="grid2 textR">
                        <span>用户名：</span>  
                    </div>
                    <div class="grid2">
                        <input name="username" class="text" type="text" value="" id="edit_username" readonly="readonly">
                    </div>  
                    <div class="grid2 textR">
                        <span>密码：</span>  
                    </div>
                    <div class="grid2">
                        <input name="password" class="text" type="password" value="" id="edit_password">
                    </div>  
                    <div class="grid2 textR">
                        <span>角色：</span>  
                    </div>
                    <div class="grid2">
                        <select name="role" id="edit_role">
                            <?php 
                                foreach ($tdata['role'] as $key => $value) {
                                    echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                                }
                            ?>
                        </select>
                    </div> 
                    <div class="clear"></div></div>
                    <div class="formRow">
                    <div class="grid6 textR">
                        <input type="hidden" value="0" id="edit_uid" name="uid" class="text">
                        <a href="javascript:;" class="buttonM bBlue" id="edit_save"><span style="color: #fff">保存</span></a>
                    </div>
                    <div class="clear"></div>
                </div>
            </form>
        </div>
</div>
<script src="/js/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function() {
        // 新增会员信息
        var add_dialog = $('#add_dialog');
        add_dialog.dialog({
            autoOpen: false,
            width: "60%",
            modal: true,
        });
        // 编辑会员信息
        var edit_dialog = $('#edit_dialog');
        edit_dialog.dialog({
            autoOpen: false,
            width: "60%",
            modal: true,
        });
        $('#logs_list_table_wrapper > table > tbody > tr').dblclick(function() {
            var id = $(this).attr('sid');
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '<?php echo home_url(); ?>?m=user&a=info&id=' + id,
                success: function(data) {
                    for (var k in data) {
                        $('#edit  .text[name="'+k+'"]').val(data[k]);
                    };
                }
            });
            edit_dialog.dialog('open');
        });

        // 编辑保存
        $('#save').click(function() {
            $('#edit').submit();
        });        

        $('#add').click(function() {
            add_dialog.dialog('open');
        });

        // 新增保存
        $('#add_save').click(function() {
            var uname = $('#username').val();
            if(!uname){
                alert('请输入用户名');
                return false;
            }
            var password = $('#password').val();
            if(!password){
                alert('请输入密码');
                return false;
            }
            var role = $('#role').val();
            $.post('/?m=user&a=doAdd',{username:uname,password:password,role:role},function(data){
                $.jGrowl(data.msg);
                if(data.status){
                    add_dialog.dialog('close');
                }
            },'json');
        });
        $('#search').click(function(){
                var username=$('#search_username').val();
                var url = '?m=user&a=index';
                if(username){
                    url+='&username='+username;
                }
                window.location.href=url;
            });
    });
</script>
<?php block('footer'); ?>