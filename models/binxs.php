<?php
/**
 * Bin数据模型 显示系列
 *
 * @author shaoqi
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class BinXS_Model
{
    /**
     * $data 表格的数据流
     */
    public static function doImport($data) {
        // 表头验证
        $title = array(
                    'bin'=>'BIN号', 'pno'=>'生产批号',
                    'r_wavelength'=>'R：波长','g_wavelength'=>'G：波长','b_wavelength'=>'B：波长',
                    'r_bright'=>'R：亮度','g_bright'=>'G：亮度','b_bright'=>'B：亮度',
                    'r_voltage'=>'R：电压','g_voltage'=>'G：电压','b_voltage'=>'B：电压',
                    );


        $import_hearder = array_shift($data);
        $hearder = array_values($title);
        if($import_hearder!=$hearder){
            return array('status' => 'fail', 'message' => '文件表头不对！');
        }
        foreach ($data as $key => $value) {
            $bin = trim(str_replace('b', '', strtolower($value['0'])));
            $sql = 'select id from bin_xs where bin =' . $bin . ' and pno =\''.$value['1'].'\'';
            $bin_xs = DB::one($sql);
            if(empty($bin_xs)){
                $data[$key]='(\''.$bin.'\',\''.$value['1'].'\',\''
                               .$value['2'].'\',\''.$value['3'].'\',\''.$value['4'].'\',\''
                               .$value['5'].'\',\''.$value['6'].'\',\''.$value['7'].'\',\''
                               .$value['8'].'\',\''.$value['9'].'\',\''.$value['10'].'\',\''.NOW.'\')';
            }else{
                $sql = 'update `bin_xs` set 
                        `r_wavelength`=\''.$value['2'].'\',`g_wavelength`=\''.$value['3'].'\',`b_wavelength`=\''.$value['4'].'\',`r_bright`=\''.$value['5'].'\',`g_bright`=\''.$value['6'].'\',`b_bright`=\''.$value['7'].'\',`r_voltage`=\''.$value['8'].'\',`g_voltage`=\''.$value['9'].'\',`b_voltage`=\''.$value['10'].'\' where id='.$bin_xs['id'];
                DB::query($sql);
                unset($data[$key]);
            }
        }
        if(!empty($data))
        {
            $sql = 'insert into `bin_xs` (`'.implode('`,`',array_keys($title)).'`,`imported_at`) values '.implode(',',$data);
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

        $sql = "select `r_wavelength`,`g_wavelength`,`b_wavelength`,`r_bright`,`g_bright`,`b_bright`,`r_voltage`,`g_voltage`,`b_voltage` from `bin_xs` where `bin` = '{$bid}' and `pno` like '" . $pno ."%'";

        return DB::all($sql);
    }
}