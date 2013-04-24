<?php 
/**
 * 模板渲染类
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class Render
{
	private static $_render;
	private static $_tpl;
	private static $_data;

	/**
	 * 单例实例化
	 */
	public static function instance() {
		if(is_object(self::$_render))
			$render = self::$_render;
		else $render = self::$_render = new self;

		return $render;
	}

	/**
	 * 模版渲染
	 */
	public static function show() {

		$tdata = self::$_data;
		$tpl = str_replace('_', DS, self::$_tpl);
		$tpl_file = path('tpl') . $tpl . '.tpl' . EXT;

		if(file_exists($tpl_file))
			include $tpl_file;
		else die($tpl . '模版文件不存在.');
	}

	/**
	 * 模版参数
	 *
	 * @param $tpl  string 模版文件名
	 * @param $data array  模版用到的数据
	 *
	 * @return void
	 */
	public static function with($tpl, $data = array()) {

		$render = self::Instance();

		$render::$_tpl = $tpl;
		$render::$_data = $data;

		return self::$_render;
	}

	/**
	 * 输出json数据，供ajax调用
	 */
	public static function json($data) {
		echo json_encode($data);
	}

}