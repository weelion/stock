<?php
session_start();
$referer=empty($_SERVER['HTTP_REFERER']) ? array() : array($_SERVER['HTTP_REFERER']);
$getfilter="'|\b(alert|confirm|prompt)\b|<[^>]*?>|^\\+\/v(8|9)|\\b(and|or)\\b.+?(>|<|=|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
$postfilter="^\\+\/v(8|9)|\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|<\\s*img\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
$cookiefilter="\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
require '../paths.php';

require path('sys').'core.php';
foreach($_GET as $key=>$value){ 
	StopAttack($key,$value,$getfilter);
}
foreach($_POST as $key=>$value){ 
	StopAttack($key,$value,$postfilter);
}
foreach($_COOKIE as $key=>$value){ 
	StopAttack($key,$value,$cookiefilter);
}
foreach($referer as $key=>$value){ 
	StopAttack($key,$value,$getfilter);
}
$module = isset($_REQUEST['m']) ? $_REQUEST['m'] : 'user';
$action = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'login';

if( ! empty($module) ) {
	$action = 'action_' . $action;
	$module = ucfirst($module);

	if(($module != 'user' && $action != 'action_login') && ($module != 'user' && $action != 'action_Logout')) {
		$is_login = User_Model::checkLogin();
		if(!$is_login) {
            setcookie("kc_token", '', -1);
            unset($_SESSION['s_role']);
            unset($_SESSION['s_username']);
            unset($_SESSION['s_uid']);
            
            error('错误', '你还没有登录');
        }

        if(!User_Model::has_auth()) error('错误', '你没有此操作权限。');

	}

    start($module, $action);
} else {
	error('错误','参数错误');
}

