<?php 
/**
 * 配置类
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class Config
{

    private static $_config;

    /**
     * 初始化配置
     */
    public static function init() {

        $series = include path('sys').'series.php';

        self::$_config = array(
                'app_title'   => '晶台光电库存系统 demo v1.0',
                'home_url'    => '/',
                'series'      => $series,
                'admin'       => array(1),   // 管理员
                'store_clerk' => array(2,3), // 仓管
                'salesman'    => array(4),   // 业务
                'mysql'       => array(
                'host'        => 'localhost',
                'database'    => 'kc',
                'username'    => 'root',
                'password'    => '',
                ),
        );
    }

    /**
     * 获取配置
     *
     * @param $index string 配置索引
     *
     * @return mix
     */
    public static function get($index) {
        return self::$_config[$index];
    }

    /**
     * 添加配置
     *
     * @param $index string 配置索引
     * @param $data  mix    配置数据
     *
     * @return void
     */
    public static function set($index, $data) {
        self::$_config[$index] = $data;
    }

}
