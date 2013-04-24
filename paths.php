<?php
/**
 * 目录定义
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
$paths['sys']    = 'systems';
$paths['lib']    = 'libs';
$paths['module'] = 'modules';
$paths['model']  = 'models';
$paths['tpl']    = 'templates';
$paths['web']    = 'web';
$paths['upload'] = 'uploads';

chdir(__DIR__);

if ( ! defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

$GLOBALS['sys_paths']['base'] = __DIR__ . DS;

foreach ($paths as $name => $path) {
	if ( ! isset($GLOBALS['sys_paths'][$name]))
	{
		$GLOBALS['sys_paths'][$name] = realpath($path).DS;
	}
}


function path($path) {
	return $GLOBALS['sys_paths'][$path];
}

function set_path($path, $value)
{
	$GLOBALS['sys_paths'][$path] = $value;
}

