<?php block('header'); ?>
<!-- Login wrapper begins -->
<div class="loginWrapper" id="login">
    <div class="relative">
        <span class="label">帐号：</span>
        <input type="text" name="username" class="login_input login_username" id="username">
        <div class="clear"></div>
    </div>
    <div class="relative">
        <span class="label">密码：</span>
        <input type="password" name="password" class="login_input login_password" id="password">
        <div class="clear"></div>
    </div>
    <div class="logControl">
        <div class="memory">
            <input name="remember" type="checkbox" value="1" class="check" id="remember">
            <label for="remember">下次自动登录</label>
        </div>
        <input type="submit" name="submit" value="登录" class="login_submit buttonS bBlue" id="submit">
        <div class="clear"></div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('#submit').click(function(){
            var username = $('#username').val();
            var password = $('#password').val();
            var remember = $('#remember').attr('checked') == 'checked' ? 1 : 0;

            if(username.length < 1 || password.length < 1) {
                $.jGrowl('帐号密码不能为空。');
            } else {
                $.ajax({
                    type: 'POST',
                    data: {username: username, password: password, remember: remember},
                    url: '<?php echo action('user@login'); ?>',
                    success: function( data ) {
                        if(data == '0') {
                            $.jGrowl('帐号或密码不正确');
                        } else if( data == '1') {
                            $.jGrowl('登录成功');
                            self.location='<?php echo home_url(); ?>';
                        } else {
                            $.jGrowl('未知错误');
                        }
                    },
                    error: function() {
                        $.jGrowl('内部错误');
                    }
                });
            }


        });
    });
    
</script>
<!-- Login wrapper ends -->
<?php block('footer'); ?>
