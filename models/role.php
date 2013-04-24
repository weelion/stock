<?php 
/**
 * 角色数据模型
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class Role_Model 
{
    /**
     * 获取角色名称
     *
     * @param $id integer 角色id
     *
     * @reutrn string
     */
    public static function getName($id) {
        $sql = "select name from `role` where `id` = '{$id}'";

        return DB::only($sql);
    }

    /**
     * 角色列表
     *
     * @param $filter   array   筛选条件
     * @param $page     integer 当前页
     * @param $pagesize integer 筛选条件
     *
     * @reutrn string
     */
    public static function filter($filter = array(), $page = 1, $pagesize = 15) {
        $sql = "select * from `role`";
        $where = array();
        if (!empty($filter)) {
            foreach($filter as $field => $value) {
                $where[] = "`{$field}` = '{$value}'";
            } 
        }
        $sql = $sql . (empty($where)?'':(' where '. implode(' and ', $where)));

        $offset = ($page -1) * $pagesize;

        $sql .= ' limit ' . $offset . ', '. $pagesize;
        
        return DB::all($sql);
    }

}