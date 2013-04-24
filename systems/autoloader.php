<?php 
/**
 * 自加载类
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class Autoloader 
{

	/**
	 * 类自加载
	 */
	public static function load($class)	{
		$lower = strtolower($class);
		$k = $lower;

		$is_model = strpos($lower,'_model');

		if($is_model !== false) {
			$lower = str_replace('_model', '', $lower);
		}

		if(file_exists(path('sys'). $lower . EXT)) {
			require path('sys') . $lower . EXT;
		} else if (file_exists(path('lib') . $lower . EXT)) {
			require path('lib') . $lower . EXT;
		} else if ($is_model === false && file_exists(path('module') . $lower . EXT)) {
			require  path('module') . $lower . EXT;
		} else if ($is_model  !== false && file_exists(path('model') . $lower . EXT)) {
			require path('model') . $lower .EXT;
		} else if(file_exists(path('sys').implode('/', explode('_', $class)).EXT)) {
			require path('sys').implode('/', explode('_', $class)).EXT;
		} else {
			error('错误',$class . ' 类不存在.');
		}
	}
}