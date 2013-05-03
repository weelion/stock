<?php
/**
 * 邮箱服务配置文件
 *
 * @author shaoqi
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
return array(
    'serveremail' => array(
                    'smtp' => 'smtp.jt-led.com', // smtp 服务器地址
                    'port' => 25, // smtp 端口 默认端口为25
                    'user' => 'yanghai@jt-led.com', // 发件人的邮箱地址
                    'pass' => 'sea320625', // 发件人的邮箱登录密码
                    'name' => 'system', // 发件人名称
                    ),
    // 收件人 address:收件人邮箱地址 name:收件人昵称
    'email' => array(
                array('address'=>'yanghai@jt-led.com','name'=>'user'),
              ),
);