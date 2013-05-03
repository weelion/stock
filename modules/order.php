<?php
/**
 * 待出货订单模块
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class Order extends base
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
     * 订单库存选择
     */
    public function action_index() {
        Render::with('order_index')->show();
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
        foreach($this_series['data'] as $value){
            $width[$value['alias']] = $value['width'];
        }
        foreach($this_series['info_serach'] as $key=>$value) {
            if(in_array($key, $this_series['range'])) {
                $fields[] = 'min_' . $key;
                $fields[] = 'max_' . $key;
            }else{
                $fields[] = $key;
            }
                $table_width += intval($width[$key]);
        }
        $fields[] = 'total';


        $pagesize = get('pagesize') ? get('pagesize') : PAGESIZE; 

        $total = Stock_Model::total($this_series);

        $pagination = new Pagination();
        $page = $pagination->get_page();
        $pagination->records($total,$type='serach',$isorder=1);
        $pagination->records_per_page($pagesize);
        $list = Stock_Model::filter($fields, $this_series, $page, $pagesize,$type='serach',$isorder=1);
        $data['pagination'] = $pagination;
        $data['list'] = $list;
        $data['width'] = $table_width;
        $data['range'] = json_encode($this_series['range']);


        Render::with('order_list', $data)->show();
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


        logs('stock', $log_data . '，批号：', $data['pno']);

        header('Location:' . home_url() . '?m=stock&a=list&t=' . $t);
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
        $data['order'] = 1;
        // 删除不必要数据
        unset($data['m'], $data['a'], $data['t'], $data['operator']);

        // 验证
        $error = $this->_check_stock_data($data, $this_series);

        if(empty($error)) {
            if(Stock_Model::create($data)) 
                $_SESSION['tips_success'] = '添加库存成功。';
            else $_SESSION['tips_error'] = '添加失败。';
        } else {
            $_SESSION['tips_error'] = $error;
        }

        header('Location:' . home_url() . '?m=order&a=list&t=' . $t);
    }

    /**
     * 删除
     */
    public function action_doDel() {
        $ids = get('ids');
        if(Stock_Model::delete($ids)) {
            $_SESSION['tips_success'] = '删除成功。';
            echo 1;
        } else {
            error('错误', '删除失败。');
        }
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
                if(($info['min_'.$k] != 0) && ($info['max_'.$k] != 0))
                    $info[$k] = $info['min_'.$k] . '-' . $info['max_' . $k];
            } else {
                if($value == 0)
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
}