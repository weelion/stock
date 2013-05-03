<?php

class Bdata_Model {

    /**
     * 获取记录数
     */
    public static function total($filter) {

        $sql = "select count(id) as count from `bdata`";
        if($filter) {
            $sql .= ' where 1=1 ';
            foreach($filter as $field => $value) {
                if(in_array($field,array('code','model'))) {
                    $sql .= " and `{$field}` like '{$value}%'";
                }else{
                    $sql .= " and `{$field}` = '{$value}'";
                }
            }
        }
        return DB::only($sql);
    }

    /**
     * 新增基础数据
     *
     * @param $data array 库存数据
     *
     * @return boolean
     */
    public static function create($data) {
        if(empty($data)) return false;

        $fields = array_keys($data);
        $values = array_values($data);

        $sql = "insert into `bdata` (`" . implode('`, `', $fields) . "`) values " .
               "('" . implode("', '", $values) . "')" ;

        return DB::query($sql);
    }

    /**
     * 更新基础数据
     *
     * @param $id   integer    记录id
     * @param $data array      数据
     *
     * @return boolean
     */
    public static function update($id, $data) {
        if(empty($id) || empty($data)) return false;

        $set = '';
        foreach ($data as $key => $value) {
            $set .= ", `{$key}`='{$value}'";
        }

        $sql = "update `bdata` set `id` = `id` {$set} where `id` = '{$id}'";

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
        $sql = "delete from `bdata` where `id` in (" . implode(',', $ids) . ")";

        return DB::query($sql);
    }

    /**
     * 筛选
     */
    public static function filter($filter, $page, $pagesize) {

        $sql = "select * from `bdata`";

        if($filter) {
            $sql .= ' where 1=1 ';
            foreach($filter as $field => $value) {
                if(in_array($field,array('code','model'))) {
                    $sql .= " and `{$field}` like '{$value}%'";
                }else{
                    $sql .= " and `{$field}` = '{$value}'";
                }
            }
        }

        $offset = ($page -1) * $pagesize;

        $sql .= ' limit ' . $offset . ', '. $pagesize;

        return DB::all($sql);
    }

    /**
     * 信息
     *
     * @param $t string type
     * @param $id integer id
     * @param $code string 物料代码
     *
     * @return array
     */
    public static function info($t, $id, $code) {
        $where = " where series =  '{$t}'";

        if(!empty($id))
            $where .= " and `id` = '{$id}'";

        if(!empty($code))
            $where .= " and `code` like '{$code}%'";

        $sql = "select * from `bdata`" . $where;

        if(!empty($id))
            return DB::one($sql);
        else return DB::all($sql);
    }
}