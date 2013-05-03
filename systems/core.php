<?php 
/**
 * 核心
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
date_default_timezone_set('PRC');
define('EXT', '.php');
define('NOW', time());
define('PAGESIZE', 10);
define('LIKE', 1);
define('LEFT_LIKE', 2);
define('RIGHT_LIKE', 3);

require path('sys').'config'.EXT;
require path('sys').'helpers'.EXT;
require path('sys').'autoloader'.EXT;

Config::init();

spl_autoload_register(array('Autoloader', 'load'));

