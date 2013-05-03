<?php 
/**
 * 照明库存数据模型
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class Stock_Model 
{

    private static $t;

    /**
     * 系列设置
     */
    public static function setSeries($t) {
        self::$t = $t;
    }

    public static function table() {
        return 'stock_' . self::$t;
    }

    /**
     * 新增库存数据
     *
     * @param $data array 库存数据
     *
     * @return boolean
     */
    public static function create($data) {
        if(empty($data)) return false;

        $fields = array_keys($data);
        $values = array_values($data);

        $sql = "insert into `" . self::table() . "` (`" . implode('`, `', $fields) . "`) values " .
               "('" . implode("', '", $values) . "')" ;

        return DB::query($sql);
    }

    /**
     * 更新库存数据
     *
     * @param $id   integer    记录id
     * @param $data array      数据
     * @param $oprator integer 运算符代号 1加 2减
     * @param $total integer   库存数量
     *
     * @return boolean
     */
    public static function update($id, $data, $operator, $total) {
        if(empty($id) || empty($data)) return false;

        $set_total = '';
        if(!empty($operator) && !empty($total)) {
            $ops = array(1, 2);
            if(!in_array($operator, $ops)) return false;
            $op = $operator == 1 ? '+' : '-';
            $set_total = ', `total` = `total`' . $op . $total;
        }

        $set = '';
        foreach ($data as $key => $value) {
            $set .= ", `{$key}`='{$value}'";
        }

        $sql = "update `" . self::table() . "` set `id` = `id` {$set} {$set_total} where `id` = " . $id;

        return DB::query($sql);
    }

    /**
     * 删除
     *
     * @param $ids array ids
     *
     * @return boolean
     */
    public static function delete($ids) {
        $ids = array_merge(array(0), $ids);
        $sql = "delete from `" . self::table() . "` where `id` in (" . implode(',', $ids) . ")";

        return DB::query($sql);
    }

    /**
     * 获取单条库存记录
     *
     * @param $id integer 记录id
     *
     * @return array
     */
    public static function get($id) {
        if(empty($id)) return array();

        $sql = "select * from `" . self::table() . "` where `id` = '{$id}'";

        return DB::one($sql);
    }

    /**
     * 过滤
     */
    public static function filter($fields, $filter, $page = 1, $pagesize = 10, $type='serach',$isorder=0) {
        $where = array();
        self::_filter_maker($where, $filter, $type);
        $condition = (empty($where)?'':'where ' . implode(' and ', $where));
        if('info_serach'==$type && empty($condition)) {
            return array();
        }

        if('serach'==$type){
            $condition = (empty($condition)?' where ':$condition.' and ').'`order`='.$isorder;
        }
        $fields = '`' . implode('`, `', $fields).'`';
        $offset = (($page - 1) * $pagesize);
        $sql = 'select '. $fields .' from `' . self::table() . '` '. $condition . ' order by `id` desc ' .
               ' limit ' . $offset . ', ' . $pagesize;
        return DB::all($sql);
    }

    /**
     * 记录数
     */
    public static function total($filter, $type='serach',$isorder=0){
        $where = array();
        self::_filter_maker($where, $filter, $type);
        $condition = (empty($where)?'':'where ' . implode(' and ', $where));
        if('info_serach'==$type && empty($condition)) {
            return 0;
        }
        if('serach'==$type){
            $condition = (empty($condition)?' where ':$condition.' and ').'`order`='.$isorder;
        }
        $sql = 'select count(`id`) AS rows from `' . self::table() . '` '.$condition;

        return DB::only($sql);
    }


    /**
     * 库存总量
     */
    public static function sum_total($filter, $type='serach',$isorder=0){
        $where = array();
        self::_filter_maker($where, $filter, $type);
        $condition = (empty($where)?'':'where ' . implode(' and ', $where));
        if('info_serach'==$type && empty($condition)) {
            return 0;
        }
        if('serach'==$type){
            $condition = (empty($condition)?' where ':$condition.' and ').'`order`='.$isorder;
        }else{
           $condition = $condition.' and `order`='.$isorder;
        }
        $sql = 'select sum(`total`) AS total from `' . self::table() . '` '.$condition;

        return DB::only($sql);
    }


    /**
     * filter maker
     */
    private static function _filter_maker(&$where, $filter, $type='serach') {
        $serach = $filter[$type];
        foreach ($serach as $key=>$value) {
            if($value){
                if(get($key)){
                    $where[$key] = '`'.$key.'` like \''.self::_filter_like(get($key), $value).'\'';
                }
            }else{
                if(in_array($key,$filter['range'])){
                    if(get('min_'.$key)) {
                        $where['min_'.$key]='`min_'.$key.'` >= \''.get('min_'.$key).'\'';
                    }
                    if(get('max_'.$key)) {
                        $where['max_'.$key]='`max_'.$key.'` <= \''.get('max_'.$key).'\'';
                    }
                }else{
                    if(get($key)) {
                        $where[$key] = '`'.$key.'` = \''.get($key).'\'';
                    }
                }
            }
        }
    }

    private static function _filter_like($value, $like) {
        switch ($like) {
            case LIKE:
                $value = '%'.$value.'%';
            break;
            case LEFT_LIKE:
                $value = '%'.$value;
            break;
            case RIGHT_LIKE:
                $value = $value.'%';
            break;
        }
        return $value;
    }
}
 ?>