<?php
include 'paths.php';
require path('sys').'core.php';

$bak = DB::backup();
$file = createZIP($bak);
if(sendBackup($file)){
    $extra = '成功';
    echo '发送成功！';
} else {
    $extra = '失败';
    echo '发送失败！';
}
@unlink($file);
logs('system', '发送备份邮件', $extra);


