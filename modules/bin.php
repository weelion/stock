<?php
/**
 * Bin 模块
 *
 * @author shaoqi
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class Bin extends base
{
    /**
     * Bin信息获取
     */
    public function action_info() {
        $pno = get('pno');
        $bid = intval(get('bin'));

        $info = array();
        switch (get('t')) {
            case 'zm':
                $info = BinZM_Model::load($pno, $bid);
                break;
            case 'zs':
                $info = BinZS_Model::load($pno, $bid);
                break;
            case 'xs':
                $info = BinXS_Model::load($pno, $bid);
                break;
            case 'bg':
                $info = BinBG_Model::load($pno, $bid);
                break;
            default:
                $info = array();
                break;
        }
        if(count($info) == 1){
            $info = $info[0];
        }
        Render::json($info);
    }

    /**
     * Bin 导入引导页面
     *
     */
    public function action_center() {
        Render::with('bin_center')->show();
    }

    /**
     * Bin导入页面
     */
    public function action_import() {
        $t = get('t');
        $bin = Config::get('series');
        $t = array_key_exists($t, $bin)?$t:'zm';
        Render::with('bin_import',array('t'=>$t,'title'=>$bin[$t]['title']))->show();
    }

    public function action_doimport() {
        $bin = Config::get('series');
        $t = get('t');
        if(!array_key_exists($t, $bin)){
            ajax_error(array('status' => 'fail', 'message' => '系统中没有你要找的类别'));
        }
        $filetype = ['application/excel', 'application/vnd.ms-excel', 'application/msexcel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.msexcel'];
        $extname = strtolower(substr($_FILES['file']['name'], strrpos($_FILES['file']['name'], '.') + 1));
        if (is_uploaded_file($_FILES['file']['tmp_name']) && in_array($_FILES['file']['type'],$filetype) && in_array($extname,['xlsx','xls']))
        {
            if ($_FILES['file']['error'] == 0) {
                $dir = path('upload').'bin'.DS.str_replace('.', '', microtime(true)).'.'.$extname;
                move_uploaded_file($_FILES['file']['tmp_name'],$dir);
                $PHPExcel = new PHPExcel();
                $PHPRead = new PHPExcel_Reader_Excel2007();
                if( !$PHPRead->canRead($dir) ) {
                    $PHPRead = new PHPExcel_Reader_Excel5();
                    if( !$PHPRead->canRead($dir) ) {
                        ajax_error(array('status' => 'fail', 'message' => '不能读取导入的信息！'));
                    }
                }
                $PHPExcel = $PHPRead->load($dir);
                $data = $PHPExcel->getSheet(0)->toArray();
                switch($t){
                    case 'zm':
                        $return = BinZM_Model::doImport($data);
                        break;
                    case 'xs':
                        $return = BinXS_Model::doImport($data);
                        break;
                    case 'zs':
                        $return = BinZS_Model::doImport($data);
                        break;
                    case 'bg':
                        $return = BinBG_Model::doImport($data);
                        break;
                }
                ajax_error($return);
            } else {
                $upload_errors = array( 
                    UPLOAD_ERR_OK         => "文件上传成功", 
                    UPLOAD_ERR_INI_SIZE   => "上传的文件超过了php.ini中upload_max_filesize选项限制的值", 
                    UPLOAD_ERR_FORM_SIZE  => "上传文件的大小超过了HTML表单中MAX_FILE_SIZE选项指定的值", 
                    UPLOAD_ERR_PARTIAL    => "文件只有部分被上传", 
                    UPLOAD_ERR_NO_FILE    => "没有文件被上传", 
                    UPLOAD_ERR_NO_TMP_DIR => "找不到临时文件夹", 
                    UPLOAD_ERR_CANT_WRITE => "文件写入失败", 
                  );
                ajax_error(array('status' => 'fail', 'message' => $upload_errors[$_FILES['file']['error']]));
            }
        }
        else
        {
            ajax_error(array('status' => 'fail', 'message' => '文件上传失败！'));
        }
    }
}