<?php
/**
 * 日志数据模型
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class Logs_Model
{

    public static function filter($filter, $page, $pagesize) {

        $sql = "select `l`.`loged_at`, `l`.`type`, `u`.`username`, `l`.`data`, 
                `l`.`extra` from `logs` l left join `users` u on `u`.`id` = `l`.`uid` ";

        if($filter) {
            $sql .= ' where 1=1 ';
            foreach($filter as $field => $value) {
                if($field=='loged_at') {
                    $sql .= ' and `'.$field.'`  between '.$value[0].' and '.$value[1];
                }else {
                    $sql .= " and `{$field}` = '{$value}'";
                }
            }
        }

        $offset = ($page -1) * $pagesize;

        $sql .= ' order by l.id desc limit ' . $offset . ', '. $pagesize;

        return DB::all($sql);
    }

    /**
     * 记录数
     */
    public static function total($filter = array()) {

        $sql = "select count(id) as count from `logs`";
        if($filter) {

            $sql .= ' where 1=1 ';
            foreach($filter as $field => $value) {
                if($field=='loged_at') {
                    $sql .= ' and `'.$field.'`  between '.$value[0].' and '.$value[1];
                }else {
                    $sql .= " and `{$field}` = '{$value}'";
                }
            }
        }

        return DB::only($sql);
    }

    /**
     * 写日志
     */
    public static function create($data) {
        if(empty($data)) return false;

        $fields = array_keys($data);
        $values = array_values($data);

        $sql = "insert into `logs` (`" . implode('`, `', $fields) . "`) values " .
               "('" . implode("', '", $values) . "')" ;

        return DB::query($sql);
    }
}