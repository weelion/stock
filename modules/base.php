<?php 
/**
 * 模块基类
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class Base {
	private  static $_instance = array();

	public static function Instance($class) {
		
		if(isset(self::$_instance[$class]))
			$obj = self::$_instance[$class];
		else 
			$obj = self::$_instance[$class] = new $class;

		return $obj;
	}

	public function __call($method, $args) {
		error($method, '请求错误');
	}
}

