<?php
/**
 * 库存查询模块
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class Info extends base
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
     * 列表
     */
    public function action_index() {
        Render::with('info_index')->show();
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
        $fields[]='order';


        $pagesize = get('pagesize') ? get('pagesize') : PAGESIZE; 

        $total = Stock_Model::total($this_series,'info_serach');

        $pagination = new Pagination();
        $page = $pagination->get_page();
        $pagination->records($total);
        $pagination->records_per_page($pagesize);
        $list = Stock_Model::filter($fields, $this_series, $page, $pagesize,'info_serach');

        $data['pagination'] = $pagination;
        $data['list']  = $list;
        $data['width'] = $table_width;
        $data['range'] = json_encode($this_series['range']);
        $data['total'] = Stock_Model::sum_total($this_series,'info_serach',0);
        $data['sell']  =  Stock_Model::sum_total($this_series,'info_serach',1);

        Render::with('info_list', $data)->show();
    }
}