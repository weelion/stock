<?php block('header');?>
<?php block('sidebar'); ?>
<div id="content">
    <div class="contentTop">
        <?php 

        ?>
        <span class="pageTitle"><span class="icon-user-2"></span>密码修改</span>
        
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="<?php echo home_url(); ?>" title="管理后台">管理后台</a></li>
                <li class="current"><a href="<?php echo home_url(); ?>?m=users&a=changepwd" title="密码修改">密码修改</a></li>
            </ul>
        </div>
        <div class="breadLinks">
             <div class="clear"></div>
        </div>
        
    </div>
    <!-- Breadcrumbs line ends -->


    <div class="wrapper">
        <div class="widget fluid">
            <div class="whead"><h6>密码修改</h6><div class="clear"></div></div>
            <div class="formRow">
                <div class="grid1"><label style="float: right;">原始密码:</label></div>
                <div class="grid2"><input type="password" id="oldpwd" name="oldpwd" class="validate[required]" autocomplete="off"></div><div class="clear"></div>
             </div>
             <div class="formRow">
                <div class="grid1"><label style="float: right;">新的密码:</label></div>
                <div class="grid2"><input type="password" id="newpwd" name="newpwd" class="validate[required]" autocomplete="off" onKeyUp="EvalPwdStrength(this.value);"></div><div class="grid4"><table cellpadding="0" cellspacing="0" class="pwdChkTbl2"><tr><td id="idSM1" width="25%" class="pwdChkCon0" align="center"><span style="font-size:1px;"></span><span id="idSMT1" style="display:none;">密码强度很弱</span></td><td id="idSM2" width="25%" class="pwdChkCon0" align="center" style="border-left:solid 1px #fff"><span style="font-size:1px;"></span><span id="idSMT0" style="display:inline;font-weight:normal;color:#666">无效</span><span id="idSMT2" style="display:none;">密码强度一般</span></td><td id="idSM3" width="25%" class="pwdChkCon0" align="center" style="border-left:solid 1px #fff"><span style="font-size:1px;"></span><span id="idSMT3" style="display:none;">密码强度良好</span></td><td id="idSM4" width="25%" class="pwdChkCon0" align="center" style="border-left:solid 1px #fff"><span style="font-size:1px;"></span><span id="idSMT4" style="display:none;">密码强度高</span></td></tr></table></div><div class="clear"></div>
             </div>
             <div class="formRow">
                <div class="grid1"><label style="float: right;">确认密码:</label></div>
                <div class="grid2"><input type="password" id="repwd" name="repwd" class="validate[required]" autocomplete="off" ></div><div class="clear"></div>
             </div>
             <div class="formRow"><div style="margin-left: 20px"><input type="submit" value="保存" class="buttonS bLightBlue" id="save"></div><div class="clear"></div></div>
        </div>
</div>
<script src="/js/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
/*密码强度校验 来自微软*/
var alpha = "abcdefghijklmnopqrstuvwxyz";
var upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
var upper_punct = "~`!@#$%^&*()-_+=";
var digits = "1234567890";

var totalChars = 0x7f - 0x20;
var alphaChars = alpha.length;
var upperChars = upper.length;
var upper_punctChars = upper_punct.length;
var digitChars = digits.length;
var otherChars = totalChars - (alphaChars + upperChars + upper_punctChars + digitChars);

function GEId(sID) {
    return document.getElementById(sID);
}
function calculateBits(passWord) {
    
    if (passWord.length < 0) {
        return 0;
    }

    var fAlpha = false;
    var fUpper = false;
    var fUpperPunct = false;
    var fDigit = false;
    var fOther = false;
    var charset = 0;

    for (var i = 0; i < passWord.length; i++) {
        var char = passWord.charAt(i);

        if (alpha.indexOf(char) != -1)
            fAlpha = true;
        else if (upper.indexOf(char) != -1)
            fUpper = true;
        else if (digits.indexOf(char) != -1)
            fDigit = true;
        else if (upper_punct.indexOf(char) != -1)
            fUpperPunct = true;
        else
            fOther = true;

    }

   
    if (fAlpha)
        charset += alphaChars;
    if (fUpper)
        charset += upperChars;
    if (fDigit)
        charset += digitChars;
    if (fUpperPunct)
        charset += upper_punctChars;
    if (fOther)
        charset += otherChars;

    var bits = Math.log(charset) * (passWord.length / Math.log(2));
    
    //alert(Math.floor(bits));
    return Math.floor(bits);
}

function DispPwdStrength(iN, sHL) {
    if (iN > 4) {
        iN = 4;
    }
    for (var i = 0; i < 5; i++) {
        var sHCR = "pwdChkCon0"; if (i <= iN) {
            sHCR = sHL;
        } if (i > 0) {
            GEId("idSM" + i).className = sHCR;
        }
        GEId("idSMT" + i).style.display = ((i == iN) ? "inline" : "none");
    }
}

function EvalPwdStrength(sP) {
    //alert("start");
    var bits = calculateBits(sP);
    //alert(bits);
    if (bits >= 128) {
        DispPwdStrength(4, 'pwdChkCon4');
    }
    else if (bits < 128 && bits >= 64) {
        DispPwdStrength(3, 'pwdChkCon3');
    }
    else if (bits<64 && bits>=56) {
        DispPwdStrength(2, 'pwdChkCon2');
    }
    else if (bits<56) {
        DispPwdStrength(1, 'pwdChkCon1');
    }
    else {
        DispPwdStrength(0, 'pwdChkCon0');
    }
}
    $(function() {
        $('#save').click(function(){
                var oldpwd = $.trim($('#oldpwd').val());
                if(!oldpwd){
                    $.jGrowl('请输入原始密码');
                    return false;
                }
                var newpwd = $.trim($('#newpwd').val());
                if(!newpwd){
                    $.jGrowl('请输入新的密码');
                    return false;
                }
                var repwd = $.trim($('#repwd').val());
                if(!repwd){
                    $.jGrowl('请输入确认密码');
                    return false;
                }

                var re = /^[0-9a-zA-Z!@#\$%\^&\*\(\)\{\}\[\]'";\:\.,\?\/\+]+$/;
                if(!re.test(newpwd)){
                    $.jGrowl('密码只能是字母数字符号。');
                    return false;
                }

                if(newpwd!=repwd){
                    $.jGrowl('两次输入的密码不一致');
                    return false;
                }
                $.post('/?m=user&a=doChangepwd',{oldpwd:oldpwd,newpwd:newpwd},function(data){
                    $.jGrowl(data.msg);
                },'json');
            });
    });
</script>
<style>
.pwdChkTbl2 {
    background-color: #FFFFFF;
    border: 0 none;
    font-family: Tahoma,sans-serif;
    height: 19px;
    width: 100%;
}
.pwdChkTbl2 tr td {
    border-bottom: medium none;
    padding-right: 6px;
    color: #fff;
}
.pwdChkCon0 {
    background-color: #EBEBEB;
    border-right: 1px solid #BEBEBE;
    text-align: center;
}
.pwdChkTbl2 span {
    font-size: 12px;
}
.pwdChkCon1 {
    background-color: #FF4545;
    border-right: 1px solid #BB2B2B;
    text-align: center;
}
.pwdChkCon2 {
    background-color: #FFD35E;
    border-right: 1px solid #E9AE10;
    text-align: center;
}
.pwdChkCon3 {
    background-color: #3ABB1C;
    border-right: 1px solid #267A12;
    text-align: center;
}
.pwdChkCon4 {
    background-color: #3ABB1C;
    /*border-right: 1px solid #267A12;*/
    text-align: center;
}
</style>
<?php block('footer'); ?>