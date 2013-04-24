<?php
/**
 * 日志模块
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class Logs extends base
{

    /**
     * 日志信息
     */
    public function action_index() {

        $filter = array();
        $t = '';
        $min = min(get('min'),get('max'));
        $max = max(get('min'),get('max'));
        if(!empty($max)) {
            $min = empty($min)?strtotime($max):strtotime($min);
            $max = strtotime($max.' 23:59:59');
            $filter['loged_at'] = array($min,$max);
        }
        if(get('type')) {
            $filter['type'] = get('type');
        }
        if(get('username')) {
            $filter['username'] = get('username');
        }
        if(in_array($_SESSION['s_role'], Config::get('store_clerk'))) {
            $filter['type'] = 'stock';
            $t = 'stock';
        } 

        $total = Logs_Model::total($filter);


        $pagesize = PAGESIZE;
        $pagination = new Pagination();
        $pagination->records($total);
        $pagination->records_per_page($pagesize);

        $page = $pagination->get_page();

        $data['list'] = Logs_Model::filter($filter, $page, $pagesize);
        $data['pagination'] = $pagination;
        $data['t'] = $t;

        Render::with('log_list', $data)->show();
    }
}