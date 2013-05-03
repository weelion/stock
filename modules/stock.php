<?php
/**
 * 库存模块
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class Stock extends base
{

    /**
     * 模块前置函数
     */
    public function before() {
        $t = get('t');
        if($t) {
            $series = Config::get('series');
            if(!array_key_exists($t, $series)) 
                error('错误', '没有该系列...');
        }

        Stock_Model::setSeries($t);
    }

    /**
     * 库存系列
     */
    public function action_index() {
        Render::with('stock_index')->show();
    }

    /**
     * 库存列表
     */
    public function action_list() {
        $t = get('t');
        $series = Config::get('series');
        $this_series = $series[$t];

        // 获取字段(排序)
        $fields = array('id');
        $table_width = 0;
        foreach($this_series['data'] as $s) {
            if(in_array($s['alias'], $this_series['range'])) {
                $fields[] = 'min_' . $s['alias'];
                $fields[] = 'max_' . $s['alias'];
            } else {
                $fields[] = $s['alias'];
            }

            $table_width += intval($s['width']);
        }


        $pagesize = get('pagesize') ? get('pagesize') : PAGESIZE; 

        $total = Stock_Model::total($this_series);

        $pagination = new Pagination();
        $page = $pagination->get_page();
        $pagination->records($total);
        $pagination->records_per_page($pagesize);
        $list = Stock_Model::filter($fields, $this_series, $page, $pagesize);

        $data['pagination'] = $pagination;
        $data['list'] = $list;
        $data['width'] = $table_width;
        $data['range'] = json_encode($this_series['range']);
        // 当前库存
        $data['total'] = $total = Stock_Model::sum_total($this_series);

        Render::with('stock_list', $data)->show();
    }


    /**
     * 更新操作
     */
    public function action_doEdit() {

        $request            = get();
        $t                  = $request['t'];
        $id                 = $request['id'];
        $series             = Config::get('series');
        $this_series        = $series[$t];
        $operator           = intval($request['operator']) ? intval($request['operator']) : 0;
        $data               = $request;
        $data['updated_at'] = NOW;
        $colour = '';
        // 删除不必要数据
        unset($data['m'], $data['a'], $data['t'], $data['id'], $data['operator']);

        // 验证
        $error = $this->_check_stock_data($data, $this_series);
                   
        // 额外验证
        $total = $data['total'];
        unset($data['total']); 
        if(empty($error) && !empty($total) && empty($operator)) {
            $error = '修改库存数量必须选择增加或者减少。';
        }

        if(empty($error)) {
            if(Stock_Model::update($id, $data, $operator, $total)) 
            $_SESSION['tips_success'] = '编辑库存成功。';
            else $_SESSION['tips_error'] = '编辑失败。';
        } else {
            $_SESSION['tips_error'] = $error;
        }

        if(isset($_SESSION['tips_error'])) {
            $log_data = '编辑库存%s失败';
        } else {
            $log_data = '编辑库存%s成功';
            if($operator !=1 && !empty($operator)){
                $colour = 'red';
            }
        }

        if(!empty($operator)) {
            if($operator ==1) {
                $log_data = sprintf($log_data, '数目+'. $total);
            } else {
                $log_data = sprintf($log_data, '数目-'. $total);
            }
        } else {
            $log_data = str_replace('%s', '', $log_data);
        }


        logs('stock', $log_data . '，批号：', $data['pno'], $colour);

        header('Location:' . home_url() . '?m=stock&a=list&t=' . $t);
    }

    /**
     * 部分编辑
     */
    public function action_doEdit2() {
        $this->action_doEdit();
    }

    /**
     * 添加操作
     */
    public function action_doAdd() {
        $request            = get();
        $t                  = $request['t'];
        $series             = Config::get('series');
        $this_series        = $series[$t];
        $data               = $request;
        $data['updated_at'] = NOW;
        $data['created_at'] = NOW;


        // 删除不必要数据
        unset($data['m'], $data['a'], $data['t'], $data['operator']);

        // 验证
        $error = $this->_check_stock_data($data, $this_series);

        $where = $upodata = array();

        if(empty($error)) {
            foreach($data as $key=>$value){
                if(!in_array($key, array('id','total','created_at','updated_at'))){
                    $where [] = '`'.$key.'` = \''.$value.'\'';
                    $upodata [$key] =  $value;
                }
            }
            $sql = 'select id from '.Stock_Model::table().' where '.implode(' and ', $where);
            $id = DB::only($sql);
            if(empty($id)){
                $operator = 0;
                if(Stock_Model::create($data)){
                    $_SESSION['tips_success'] = '添加库存成功。';
                }else{
                    $_SESSION['tips_error'] = '添加失败。';
                }
            }else{
                $operator = 1;
                if(Stock_Model::update($id,$upodata,1,$data['total'])){
                    $_SESSION['tips_success'] = '添加库存成功。';
                }else{
                    $_SESSION['tips_error'] = '添加失败。';
                }
            }
        } else {
            $_SESSION['tips_error'] = $error;
        }

        if(isset($_SESSION['tips_error'])) {
            if($operator==1){
                $log_data = sprintf('编辑库存%失败', '数目+'. $data['total']);
            }else{
                $log_data = '添加库存失败';
            }
        } else {
            if($operator==1){
                $log_data = sprintf('编辑库存%成功', '数目+'. $data['total']);
            }else{
                $log_data = '添加库存成功';
            }
        }

        logs('stock', $log_data.'，批号：', $data['pno']);

        header('Location:' . home_url() . '?m=stock&a=list&t=' . $t);
    }

    /**
     * 删除
     */
    public function action_doDel() {
        $ids = get('ids');
        $extra = '失败';
        if(Stock_Model::delete($ids)) {
            $_SESSION['tips_success'] = '删除成功。';
            $extra = '成功';
            echo 1;
        } else {
            error('错误', '删除失败。');
        }

        logs('stock', "删除库存数据ID：" . implode(', ', $ids), $extra);
    }

    /**
     * 库存信息
     */
    public function action_info() {
        $t = get('t');
        $series = Config::get('series');
        $this_series = $series[$t];
        $id = intval(get('id'));
        $info = array();

        $info = Stock_Model::get($id);

        foreach($info as $key => $value) {
            $k = trim(trim($key, 'min_'), 'max_');
            if(in_array($k, $this_series['range']) && !isset($info[$k])) {
                if(($info['min_'.$k] != '0') && ($info['max_'.$k] != '0'))
                    $info[$k] = $info['min_'.$k] . '-' . $info['max_' . $k];
            } else {
                if($value == '0')
                    unset($info[$key]);
            }
        }


        Render::json($info);
    }

    // 验证数据
    private function _check_stock_data(&$data, $this_series) {

        $error = '';

        $num_fields = array('bin', 'current', 'total', 'index');
        $range = $this_series['range'];

        foreach ($data as $key => $value) {

            if(empty($value)) {
                unset($data[$key]);
                continue;
            }

            $name = get_series_name($this_series['data'], $key);

            if(empty($error) && in_array($key, $num_fields)) {
                if(! preg_match('/^\d+$/', $value)) {
                    if($key == 'total') $name = '库存数量';
                    $error = $name . $key . '必须为数字。';
                }
            }

            if(empty($error) && in_array($key, $range)) {

                // 范围格式
                if(! preg_match('/^\d+\.?\d?\-\d+\.?\d?$/', $value)) {
                    $error = $name . '格式不正确。';
                } else {

                    $split = explode('-', $value);

                    if($split[0] > $split[1]) {
                        $error = $name . '范围出错了。';
                    }
                    
                    $data['min_' . $key] = $split[0];
                    $data['max_' . $key] = $split[1];
                                               
                    unset($data[$key]);
                }
            }
        }

        return $error;
    }


    /**
     * 数据导出
     */
    public function action_export() {
        $t = get('t');
        $series = Config::get('series');
        $this_series = $series[$t];

        // 获取字段(排序)
        $fields = array('id');
        $table_width = 0;
        $title = array();
        foreach($this_series['data'] as $s) {
            $title[] = $s['name'];
            if(in_array($s['alias'], $this_series['range'])) {
                $fields[] = 'min_' . $s['alias'];
                $fields[] = 'max_' . $s['alias'];
            } else {
                $fields[] = $s['alias'];
            }

            $table_width += intval($s['width']);
        }

        $total = Stock_Model::total($this_series);
        $list = Stock_Model::filter($fields, $this_series, 1, $total);
        foreach ($list as $key => $value) {
            if(isset($value['id']) && !empty($value['id'])) {
                unset($value['id']);
            }

            $set = array();
            foreach($value as $k => $v) {
                $k = trim(trim($k, 'min_'), 'max_');
                if(in_array($k, $this_series['range']) && !in_array($k, $set)) {
                    $v = '';
                    $set[] = $k;
                    if($value['min_'.$k] != '0' && $value['max_'.$k] != '0') {
                                                
                        $v = $value['min_'.$k] . '-' . $value['max_'.$k];
                    }
                    $data[$key][] = $v;
                } else if(!in_array($k, $this_series['range'])) {
                    if($v == '0') {
                        $v = '';
                    }
                    $data[$key][] = $v;
                }
            }
        }
        $filename = $this_series['title'].'系列'.date('Ymd');
        exportData($filename,$title,$data);
    }


}
