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
                <?php if(User_Model::has_auth('user_doadd')): ?>
                <div class="cOptions">
                    <a href="javascript:;" class="tOptions" id="add">
                        <span class="icon-plus-2" style="margin:0; padding:0; color: #666"></span>
                    </a>
                <?php endif; ?>
                </div>
                <div id="logs_list_table_wrapper" class="dataTables_wrapper" role="grid">
                    <table cellpadding="0" cellspacing="0" border="0" class="dTable dataTable" id="order_list_table" aria-describedby="order_list_table_info" style="width: 100%;">
                        <thead>
                            <tr role="row">
                                <th class="sorting_disabled" tabindex="0" rowspan="1" colspan="1" width="10%">ID</th>
                                <th class="sorting_disabled" tabindex="0" rowspan="1" colspan="1" width="35%">帐号</th>
                                <th class="sorting_disabled" tabindex="0" rowspan="1" colspan="1" width="25%">角色</th>
                                <th class="sorting_disabled" tabindex="0" rowspan="1" colspan="1">操作</th>
                            </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php
                                $edit_display = '';
                                $del_display = '';
                                $auth_display = '';
                                if(!User_Model::has_auth('user_doedit')){
                                    $edit_display = 'display: none';
                                }
                                if(!User_Model::has_auth('user_dodel')){
                                    $del_display = 'display: none';
                                }
                                if(!User_Model::has_auth('user_doauth')){
                                    $auth_display = 'display: none';
                                }

                                foreach ($tdata['list'] as $key => $value) :
                            ?>
                            <tr>
                                <td><?php echo $value['id']; ?></td>
                                <td><?php echo $value['username']; ?></td>
                                <td><?php echo $value['name']; ?></td>
                                <td class="tableActs">
                                    <a class="tablectrl_small bDefault" style="<?php echo $edit_display; ?>" action="user_modify" uid="<?php echo $value['id'];?>"><span class="iconb" data-icon=""></span></a>
                                    <a class="tablectrl_small bDefault" style="<?php echo $auth_display; ?>" action="user_authority" uid="<?php echo $value['id'];?>"><span class="iconb" data-icon=""></span></a>
                                    <a class="tablectrl_small bDefault" style="<?php echo $del_display; ?>" action="user_delete" uid="<?php echo $value['id'];?>"><span class="iconb" data-icon=""></span></a>
                                </td>
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
        </div>
        <div id="edit_dialog" class="fluid" style="display: none" title="编辑会员">
            <div class="formRow">
                <div class="grid2 textR">
                    <span>用户名：</span>  
                </div>
                <div class="grid2">
                    <input name="username" class="text" type="text" value="" id="edit_username">
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
                    <input type="hidden" value="" id="edit_uid" name="uid" class="text">
                    <a href="javascript:;" class="buttonM bBlue" id="edit_save"><span style="color: #fff">保存</span></a>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div id="del_dialog" class="fluid" style="display: none" title="删除会员">
            <p></p>
        </div>
        <div id="auth_dialog" class="fluid" style="display: none" title="编辑会员权限"></div>
</div>
<script type="text/javascript">
    $(function() {
        // 新增会员信息
        var add_dialog = $('#add_dialog');
        add_dialog.dialog({
            autoOpen: false,
            width: "60%",
            modal: true,
        });
      
        $('#add').click(function() {
            add_dialog.dialog('open');
        });

        // 新增保存
        $('#add_save').click(function() {
            var uname = $('#username').val();
            if(!uname){
                $.jGrowl('请输入用户名');
                return false;
            }

            var password = $('#password').val();
            if(!password){
                $.jGrowl('请输入密码');
                return false;
            }

            var re = /^[0-9a-zA-Z!@#\$%\^&\*\(\)\{\}\[\]'";\:\.,\?\/\+]+$/;
            if(!re.test(password)){
                $.jGrowl('密码只能是字母数字符号。');
                return false;
            }

            var role = $('#role').val();
            $.post('<?php echo home_url(); ?>?m=user&a=doAdd',{username:uname,password:password,role:role},function(data){
                if(data.msg) {            
                    $.jGrowl(data.msg);
                }
                if(data.status){
                    location.reload();
                }
            },'json');
        });

        // 编辑会员信息
        var edit_dialog = $('#edit_dialog');
        edit_dialog.dialog({
            autoOpen: false,
            width: "60%",
            modal: true,
            close: function() {
                $('#edit_form')[0].reset();
            }
        });

        $('a[action="user_modify"]').click(function() {
            var id = $(this).attr('uid');
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '<?php echo home_url(); ?>?m=user&a=info&id=' + id,
                success: function(data) {
                    for (var k in data) {
                        $('#edit_dialog .text[name="'+k+'"]').val(data[k]);

                        if(k == 'role') {
                            $('#edit_role').find('option').attr('selected', false);
                            var option = $('#edit_role').find('option[value="' + data[k] + '"]');
                            option.attr("selected",true);
                            $('#uniform-edit_role > span').html(option.text());
                        }
                    };

                    $('#edit_dialog').attr('uid', id);
                }
            });
            edit_dialog.dialog('open');
        });

        // 编辑保存
        $('#edit_save').click(function() {
            var username = $('#edit_username').val();
            var password = $('#edit_password').val();
            var role     = $('#edit_role').val();
            var id       = $('#edit_dialog').attr('uid');

            if(!username){
                $.jGrowl('请输入用户名');
                return false;
            }

            if(password.length > 0) {
                var re = /^[0-9a-zA-Z!@#\$%\^&\*\(\)\{\}\[\]'";\:\.,\?\/\+]+$/;
                if(!re.test(password)){
                    $.jGrowl('密码只能是字母数字符号。');
                    return false;
                }
            }

            $.post('<?php echo home_url(); ?>?m=user&a=doEdit',{id:id, username:username,password:password,role:role},function(data){
                if(data.msg) {
                    $.jGrowl(data.msg);
                } 
                if(data.status){
                     location.reload();
                }
            },'json');
        });

         // 删除确认
        var del_dialog = $('#del_dialog');
        del_dialog.dialog({
            autoOpen: false,
            width: "40%",
            modal: true,
            buttons: {
                "确定": function() {
                    var id = $('#del_dialog').attr('del-data');
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: '<?php echo home_url(); ?>?m=user&a=doDel&id=' + id,
                        success: function(data) {
                            if(data.msg) {
                                $.jGrowl(data.msg);
                            }
                            if(data.status) {
                                location.reload();
                            }
                        }
                    });
                },
                "取消": function() {
                    $(this).dialog( "close" );
                },
            }
        });
        $('a[action="user_delete"]').click(function() {
            var id = $(this).attr('uid');
            $('#del_dialog').attr('del-data', id).find('p').html('删除ID为' + id + '的会员？');
            del_dialog.dialog('open');
        });

        // 权限管理
        var auth_dialog = $('#auth_dialog');
        auth_dialog.dialog({
            autoOpen: false,
            width: "60%",
            modal: true,
        });
        $('a[action="user_authority"]').click(function() {
            var id = $(this).attr('uid');
            var role = $(this).attr('role');
            $('#auth_dialog').attr('uid', id);
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '<?php echo home_url(); ?>?m=user&a=auth',
                data: {id:id},
                success: function(data) {
                    var div = '<div class="widget fluid"><div class="formRow">';
                    if(data.auths) {
                        var i =1;
                        for(k in data.auths) {
                            div += '<div class="grid3">' +
                                   '<input name="auths[]" class="check" type="checkbox" id="ckb' + k +'" value="' + k + '" key="auths"/>' +
                                   '<label for="ckb' + k + '"  class="nopadding">' + data.auths[k] + '</label></div>';

                            if(i%4 == 0) div += '<div class="clear"></div></div><div class="formRow">';
                            i++;
                        }
                    }
                    div += '<div class="clear"></div></div>';
                    div += '<div class="formRow" style="text-align: center;"><input class="check" key="auths" type="checkbox" id="checkall"><a style="color: #fff" href="javascript:;" class="buttonS bBlue" id="auth_save"><span>保存</span></a></div>';
                    div += '</div>';

                    $('#auth_dialog').html(div);

                    if(data.my_auth) {
                        for(k in data.my_auth) {
                            $('input[name="auths[]"][id="ckb'+data.my_auth[k]+'"]').attr('checked', 'checked');
                        }
                    }

                    $(".check").uniform();
                    auth_dialog.dialog('open');
                }
            });
        });

        // 提交权限
        $('#auth_save').live('click', function(){
            var id = $('#auth_dialog').attr('uid');
            var auths = new Array();
            var i = 0;
            $(':checkbox[name="auths[]"]:checked').each(function() {
                auths[i] = $(this).val();
                i++;
            });

            $.ajax({
                url: '<?php echo home_url(); ?>?m=user&a=doAuth',
                type: 'post',
                dataType: 'json',
                data: {auths: auths, id: id},
                success: function(data) {
                    if(data.msg) {
                        $.jGrowl(data.msg);
                    }
                    if(data.status) {
                        auth_dialog.dialog('close');
                    }
                }
            });
        });

        // 搜索
        $('#search').click(function(){
            var username=$('#search_username').val();
            var url = '?m=user&a=index';
            if(username){
                url+='&username='+username;
            }
            window.location.href=url;
        });
        $("#checkall").live('click',function() {
            var checkedStatus = this.checked;

            // 指定范围
            var key = $(this).attr('key');

            // 多个全选按钮
            var multi = $('#checkAll').attr('checked', this.checked);
            if (this.checked) multi.parent().addClass('checked');
            else multi.parent().removeClass('checked');

            $('input:checkbox[name="auths[]"]').each(function() {
                this.checked = checkedStatus;
                if (checkedStatus == this.checked) {
                    $(this).closest('.checker > span').removeClass('checked');
                }
                if (this.checked) {
                    $(this).closest('.checker > span').addClass('checked');
                }
            });
        });
    });
</script>
<?php block('footer'); ?>