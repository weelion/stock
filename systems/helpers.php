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
 * 获取所有权限
 */
function all_auths() {
    $auths = Config::get('auths');
    $data = array();
    foreach ($auths as $key => $value) {
        $data = array_merge($data, $value);
    }

    return array_keys($data);
}

/**
 * 写日志
 */
function logs($type, $data, $extra, $colour='') {
    $data = array(
        'type'     => $type,
        'uid'      => isset($_SESSION['s_uid']) ? $_SESSION['s_uid'] : 0,
        'data'     => $data,
        'extra'    => $extra,
        'loged_at' => NOW,
        'colour'   => $colour
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
    $m = new MAIL5;
    $emails = Config::get('email');
    $from = Config::get('serveremail');
    $c=$m->connect($from['smtp'],$from['port'], $from['user'], $from['pass']);
    $m->from($from['user'], $from['name']);
    $m->subject(date("Y_m_d").'数据库备份');
    $m->text(date("Y_m_d").'数据库备份');
    $m->attach(file_get_contents($dateiname), FUNC5::mime_type($dateiname), 'data_' . date("Y_m_d").'.zip', null, null, 'inline', MIME5::unique());
    // 读取邮箱地址
    foreach($emails as $email)
    {
        $m->addto($email['address'], $email['name']);
    }

    $flat = false;
    if($m->send($c)){
        $flat = true;
    }

    return $flat;
}
function StopAttack($StrFiltKey,$StrFiltValue,$ArrFiltReq){  

	$StrFiltValue=arr_foreach($StrFiltValue);
	if (preg_match("/".$ArrFiltReq."/is",$StrFiltValue)==1){   
			//slog("<br><br>操作IP: ".$_SERVER["REMOTE_ADDR"]."<br>操作时间: ".strftime("%Y-%m-%d %H:%M:%S")."<br>操作页面:".$_SERVER["PHP_SELF"]."<br>提交方式: ".$_SERVER["REQUEST_METHOD"]."<br>提交参数: ".$StrFiltKey."<br>提交数据: ".$StrFiltValue);
            error('错误', '您的提交带有不合法参数,谢谢合作!');
	}
	if (preg_match("/".$ArrFiltReq."/is",$StrFiltKey)==1){   
			//slog("<br><br>操作IP: ".$_SERVER["REMOTE_ADDR"]."<br>操作时间: ".strftime("%Y-%m-%d %H:%M:%S")."<br>操作页面:".$_SERVER["PHP_SELF"]."<br>提交方式: ".$_SERVER["REQUEST_METHOD"]."<br>提交参数: ".$StrFiltKey."<br>提交数据: ".$StrFiltValue);
			error('错误', '您的提交带有不合法参数,谢谢合作!');
	}  
}
function arr_foreach($arr) {
	static $str;
	if (!is_array($arr)) {
	return $arr;
	}
	foreach ($arr as $key => $val ) {

	if (is_array($val)) {

		arr_foreach($val);
	} else {

	  $str[] = $val;
	}
	}
	return implode($str);
}


//导出excel格式表
function exportData($filename,$title,$data){
    $cache = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;
    PHPExcel_Settings::setCacheStorageMethod($cache);
    $objExcel = new PHPExcel();
    $objExcel->setActiveSheetIndex(0); 
    $objActSheet = $objExcel->getActiveSheet();
    $objActSheet->setTitle($filename);
    $objActSheet->setCellValue('A1', '晶台光电库存系统'.$filename);
    $title_array = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    //合并单元格
    $objActSheet->mergeCells('A1:'.$title_array[count($title)-1].'1');    
    //设置样式   
    $objStyleA1 = $objActSheet->getStyle('A1');       
    $objStyleA1->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);   
    $objFontA1 = $objStyleA1->getFont();       
    $objFontA1->setName('宋体');       
    $objFontA1->setSize(18);     
    $objFontA1->setBold(true);
    // 设置表头
    foreach($title as $k=>$v){
        $objActSheet->setCellValue($title_array[$k].'2', $v);
    }
    foreach($data as $key=>$value){
        $k=$key+3;
        foreach($value as $a=>$b){
            $objActSheet->setCellValueExplicit($title_array[$a].$k, $b,PHPExcel_Cell_DataType::TYPE_STRING); 
        }
    }
    $ua = $_SERVER['HTTP_USER_AGENT'];
    if(preg_match('/MSIE/',$ua)) {  
        $outputFileName = str_replace('+','%20',urlencode('晶台光电库存系统'.$filename));
    }else{
        $outputFileName = '晶台光电库存系统'.$filename;
    }
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$outputFileName.'.xls"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
    $objWriter->setPreCalculateFormulas(false);
    $objWriter->save('php://output');
    exit;
}