<?php 
/**
 * 辅助函数
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */

function start($module, $action) {
    $module = Base::Instance($module);

    if(method_exists($module, 'before'))
        $module->before();
    
    $module->$action();
}
function home_url() {
	return Config::get('home_url');
}

function css_path() {
	return home_url() . 'css/';
}

function js_path() {
	return home_url() . 'js/';
}

function image_path() {
	return home_url() . 'images/';
}

function block($tpl, $tdata=array()) {
	if(file_exists(path('tpl') . $tpl . '.block' . EXT))
		include path('tpl') . $tpl . '.block' . EXT;
	else die($tpl . '块模版不存在.');
}

function tpl_title() {
	return Config::get('app_title');
}

function action($acts) {
	list($module, $action) = explode('@', $acts);

	return home_url() . '?m=' . $module . '&a=' . $action;
}

function rand_str($len=6) {
	return substr(str_shuffle(str_repeat(str_pool(), 5)), 0, $len);
}

function str_pool() {
	return '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
}

function module_url($action = '') {

	$a = $action;

	if(empty($action))
		$a = get('a');

	$base = '?m='.get('m') . '&a=' . $a;

	$t = get('t');
	if(!empty($t))
		$base .= '&t=' . $t;

	return $base;
}

function get($param = '') {
	if(empty($param))
		return $_REQUEST;
	
	return isset($_REQUEST[$param]) ? $_REQUEST[$param] : '';
}

/**
 * 获取系列名称
 *
 * @param $series array  单个系列
 * @param $alias  string 别名
 *
 * @return string
 */
function get_series_name($series, $alias) {
    $name = '';
    foreach($series as $value) {
        if($value['alias'] == $alias)
            return $value['name'];
    }

    return $name;
}

/**
 * tips
 */
function tips() {
    if(isset($_SESSION['tips_error']) && !empty($_SESSION['tips_error'])) {
        echo '<div class="nNote nFailure">' .
             '   <p>提示：' . $_SESSION['tips_error'] . '</p>' .
             '</div>';

        unset($_SESSION['tips_error']);
    }

    if(isset($_SESSION['tips_success']) && !empty($_SESSION['tips_success'])) {
        echo '<div class="nNote nSuccess">' .
             '   <p>提示：' . $_SESSION['tips_success'] . '</p>' .
             '</div>';
        unset($_SESSION['tips_success']);
    }

}


/**
 * 出错页面
 *
 * @param $str string 提示文字
 * 
 * @return void
 */
function error($title, $desc) {

    $data['title'] = $title;
    $data['desc']  = $desc;

    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) ==  'xmlhttprequest') {
    	$data['ajax_error'] = 1;
    	ajax_error($data);
    }
    
    Render::with('error', $data)->show();
    exit;
}

/**
 * ajax 出错
 */
function ajax_error($data) {
	Render::json($data);
	exit;
}


/**
 * 写日志
 */
function logs($type, $data, $extra) {
    $data = array(
        'type'     => $type,
        'uid'      => isset($_SESSION['s_uid']) ? $_SESSION['s_uid'] : 0,
        'data'     => $data,
        'extra'    => $extra,
        'loged_at' => NOW
        );

    return Logs_Model::create($data);
}


/**
 * 压缩文件
 */
function createZIP($data) {

    // 文件夹权限要够
    chmod(path('upload'), 0777);

    // 建立压缩包
    $zip = new ZipArchive();

    $name = path('upload') . 'data_' . date("Y_m_d").'.zip';

    // 看看压缩包能不能打开
    if ($zip->open($name, ZIPARCHIVE::CREATE)!==TRUE) {
        exit("无法打开 <".$name.">\n");
    }

    // 把dump出来的数据放到压缩包里
    $zip->addFromString('data.sql', $data);

    // 关闭压缩包
    $zip->close();

    // 看看压缩包有没有生成
    if(!file_exists($name))
    {
        die("无法生成压缩包");
    }

    return $name;
}

/**
 * 发送
 */
function sendBackup($dateiname) {
    $emails = Config::get('email');

    // 读取邮箱地址
    foreach($emails as $email)
    {
        $to = $email;

        $from = Config::get('serveremail');;

        $message_body = "本邮件中包含的zip压缩包为数据库备份";

        $msep = strtoupper (md5 (uniqid (time ())));

        // 设置email头
        $header =
        "From: $from\r\n" .
        "MIME-Version: 1.0\r\n" .
        "Content-Type: multipart/mixed; boundary=".$msep."\r\n\r\n" .
        "--$msep\r\n" .
        "Content-Type: text/plain\r\n" .
        "Content-Transfer-Encoding: 8bit\r\n\r\n" .
        $message_body . "\r\n";


        // 压缩包大小
        $dateigroesse = filesize ($dateiname);

        // 读取压缩包
        $f = fopen ($dateiname, "r");
        // 保存到附件
        $attached_file = fread ($f, $dateigroesse);
        // 关闭压缩包
        fclose ($f);
        // 建立一个附件
        $attachment = chunk_split (base64_encode ($attached_file));

        // 设置附件头
        $header .=
        "--" . $msep . "\r\n" .
        "Content-Type: application/zip; name='Backup'\r\n" .
        "Content-Transfer-Encoding: base64\r\n" .
        "Content-Disposition: attachment; filename='Backup.zip'\r\n" .
        "Content-Description: Mysql Datenbank Backup im Anhang\r\n\r\n" .
        $attachment . "\r\n";

        // 标记附件结束未知
        $header .= "--$msep--";

        // 邮件标题
        $subject = "数据库备份";

        // 发送邮件需要开启php相应支持哦^^
        if(mail($to, $subject, '', $header) == FALSE)
        {
            die("无法发送邮件，请检查邮箱地址");
        }

        echo "<p><small>邮件发送成功</small></p>";
    }
}
