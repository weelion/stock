<?php
/**
 * 基础数据模块
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class Bdata extends base
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
     * 基础数据列表
     */
    public function action_index() {
        Render::with('bdata_index')->show();
    }

    /**
     * 列表
     */
    public function action_list() {

        $t = get('t');

        $series = Config::get('series');

        $pagesize = PAGESIZE;
        $filter = array('series' => $t);
        if(get('code')){
            $filter['code'] = get('code');
        }
        if(get('model')){
            $filter['model'] = get('model');
        }
        $total = Bdata_Model::total($filter);
        $pagination = new Pagination();
        $pagination->records($total);
        $pagination->records_per_page($pagesize);

        $page = $pagination->get_page();
        $data['list'] = Bdata_Model::filter($filter, $page, $pagesize);
        $data['pagination'] = $pagination;

        Render::with('bdata_list', $data)->show();
    }

    /**
     * 信息
     */
    public function action_info() {
        $t = get('t');
        $id = get('id');
        $code = get('code');

        $rs = Bdata_Model::info($t, $id, $code);

        if(count($rs) == 1){
            $rs = $rs[0];
        }

        Render::json($rs);
    }

    /**
     * 添加
     */
    public function action_doAdd() {
        $t = get('t');
        $data['series']     = $t;
        $data['code']       = get('code');
        $data['model']      = get('model');
        $data['spec']       = get('spec');
        $data['created_at'] = NOW;
        $data['updated_at'] = NOW;

        // 验证
        $error = $this->_check_bdata_data($data);
        $extra = '失败';
        if(empty($error)) {
            if(Bdata_Model::create($data)) {
                $_SESSION['tips_success'] = '添加基础数据成功。';
                $extra = '成功';
            } else $_SESSION['tips_error'] = '添加失败。';
        } else {
            $_SESSION['tips_error'] = $error;
        }

        logs('bdata', '添加基础数据', $extra);
   
        header('Location:' . home_url() . '?m=bdata&a=list&t=' . $t);
    }

    /**
     * 编辑
     */
    public function action_doEdit() {
        $t = get('t');
        $id = get('id');
        $data['code']       = get('code');
        $data['model']      = get('model');
        $data['spec']       = get('spec');
        $data['updated_at'] = NOW;

        // 验证
        $error = $this->_check_bdata_data($data);

        $extra = '失败';
        if(empty($error)) {
            if(Bdata_Model::update($id, $data)) {
                $_SESSION['tips_success'] = '编辑基础数据成功。';
                $extra = '成功';
            }else $_SESSION['tips_error'] = '编辑失败。';
        } else {
            $_SESSION['tips_error'] = $error;
        }

        logs('bdata', "编辑基础数据ID：{$id}", $extra);
   
        header('Location:' . home_url() . '?m=bdata&a=list&t=' . $t);
    }

    /**
     * 删除
     */
    public function action_doDel() {
        $ids = get('ids');
        $extra = '失败';
        if(Bdata_Model::delete($ids)) {
            $_SESSION['tips_success'] = '删除成功。';
            $extra = '成功';
            echo 1;
        } else {
            error('错误', '删除失败。');
        }

        logs('bdata', "删除基础数据ID：" . implode(', ', $ids), $extra);
    }

    /**
     * 检查表单
     */
    private function _check_bdata_data($data) {
        $keys = array('code' => '物料代码', 'model'=>'产品型号', 'spec'=>'规格');

        $error = '';
        foreach ($data as $key => $value) {
            if(empty($value) && in_array($key, array_keys($keys))) {
                $error = $keys[$key] . '不能为空。';
            } elseif(empty($value)) {
                $error = '参数错误。';
            }
        }

        return $error;
    }

}