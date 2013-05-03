<?php
/**
 * bin数据模型 照明系列
 *
 * @author shaoqi
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class BinZM_Model
{
    /**
     * $data 表格的数据流
     */
    public static function doImport($data) {
        // 表头验证
        $title = array(
                'bin'=>'BIN号', 'pno'=>'生产批号',
                  'current'=>'分光电流','index'=>'显指',
                  'ctemp'=>'色温(k)','bright'=>'流明(lm)',
                  'voltage'=>'电压(v)','ccode'=>'色区代码');


        $import_hearder = array_shift($data);
        $hearder = array_values($title);
        if($import_hearder!=$hearder){
            return array('status' => 'fail', 'message' => '文件表头不对！');
        }
        foreach ($data as $key => $value) {
            $bin = trim(str_replace('b', '', strtolower($value['0'])));
            $current = trim(str_replace('ma', '', strtolower($value['2'])));
            $index = trim(str_replace(array('>','<','='), '', strtolower($value['3'])));
            $sql = 'select id from bin_zm where bin =' . $bin . ' and pno =\''.$value['1'].'\'';
            $bin_zm = DB::one($sql);
            if(empty($bin_zm)){
                $data[$key]='(\''.$bin.'\',\''.$value['1'].'\',\''
                               .$current.'\',\''.$index.'\',\''.$value['4'].'\',\''
                               .$value['5'].'\',\''.$value['6'].'\',\''
                               .$value['7'].'\',\''.NOW.'\')';
            }else{
                $sql = 'update `bin_zm` set 
                        `current`=\''.$current.'\',`index`=\''.$index.'\',`ctemp`=\''.$value['4'].'\',`bright`=\''.$value['5'].'\',`voltage`=\''.$value['6'].'\',`ccode`=\''.$value['7'].'\'  
                        where id='.$bin_zm['id'];
                DB::query($sql);
                unset($data[$key]);
            }
        }
        if(!empty($data))
        {
            $sql = 'insert into `bin_zm` (`'.implode('`,`',array_keys($title)).'`,`imported_at`) values '.implode(',',$data);
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

        $sql = "select `current`, `index`, `ctemp`, `bright`, `voltage`, `ccode` from `bin_zm` where `bin` = '{$bid}' and `pno` like '" . $pno ."%'";

        return DB::all($sql);
    }
}