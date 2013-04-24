<?php
/**
 * bin数据模型 指示系列
 * @author shaoqi
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class BinZS_Model
{
    /**
     * $data 表格的数据流
     */
    public static function doImport($data) {
        // 表头验证
        $title = array(
                'bin'=>'BIN号', 'pno'=>'生产批号',
                  'current'=>'分光电流',
                  'wavelength'=>'波长（nm)','bright'=>'亮度（mcd)',
                  'voltage'=>'电压(V)');


        $import_hearder = array_shift($data);
        $hearder = array_values($title);
        if($import_hearder!=$hearder){
            return array('status' => 'fail', 'message' => '文件表头不对！');
        }
        foreach ($data as $key => $value) {
            $bin = trim(str_replace('b', '', strtolower($value['0'])));
            $current = trim(str_replace('ma', '', strtolower($value['2'])));
            $sql = 'select id from bin_zs where bin =' . $bin . ' and pno =\''.$value['1'].'\'';
            $bin_zs = DB::one($sql);
            if(empty($bin_zs)){
                $data[$key]='(\''.$bin.'\',\''.$value['1'].'\',\''
                               .$current.'\',\''.$value['3'].'\',\''
                               .$value['4'].'\',\''.$value['5'].'\',\''.NOW.'\')';
            }else{
                $sql = 'update `bin_zs` set 
                        `current`=\''.$current.'\',`wavelength`=\''.$value['3'].'\',`bright`=\''.$value['4'].'\',`voltage`=\''.$value['5'].'\' 
                         where id='.$bin_zs['id'];
                DB::query($sql);
                unset($data[$key]);
            }
        }
        if(!empty($data))
        {
            $sql = 'insert into `bin_zs` (`'.implode('`,`',array_keys($title)).'`,`imported_at`) values '.implode(',',$data);
            if(DB::query($sql)){
                return array('status' => 'success', 'message' => '导入成功');
            }else{
                return array('status' => 'fail', 'message' => '数据处理失败！');
            }
        }else{
            return array('status' => 'success', 'message' => '导入成功');
        }
    }

    public static function load($pno, $bid) {
        if(empty($pno) || empty($bid)) return array();

        $sql = "select `current`, `wavelength`, `bright`, `voltage` from `bin_zs` where `bin` = '{$bid}' and `pno` like '" . $pno ."%'";

        return DB::all($sql);
    }
}