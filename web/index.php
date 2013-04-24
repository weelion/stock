<?php
session_start();
require '../paths.php';

require path('sys').'core.php';

$module = isset($_REQUEST['m']) ? $_REQUEST['m'] : 'user';
$action = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'login';



if( !empty($module) ) 
{
	$action = 'action_' . $action;
	$module = ucfirst($module);

	// if($module != 'user' && $action != 'login') {
	// 	$is_login = User_Model::checkLogin();

	// 	if(!$is_login) die('没有权限');
	// }
    start($module, $action);

} else {
	error('错误','参数错误');
}

