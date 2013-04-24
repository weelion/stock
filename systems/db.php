<?php
/**
 * mysql类
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class DB 
{
	public static $link;

    /**
     * 连接数据库
     */
    public static function instance() {

        if(Null !== self::$link)
            return self::$link;

    	extract(Config::get('mysql'));

		$link = mysql_connect($host, $username, $password);
		if (!$link) {
		    die('Could not connect: ' . mysql_error());
		}

        mysql_query("set names utf8");
		mysql_select_db($database, $link); // select db

        self::$link = $link;
    }

    /**
     * query
     *
     * @param $sql string sql语句
     *
     * @return object
     */
    public static function query($sql) {
        self::instance();

    	return mysql_query($sql, self::$link);
    }

    /**
     * 只获取单条记录一个字段数据
     *
     * @param $sql string sql语句
     *
     * @return string
     */
    public static function only($sql) {
        $query = self::query($sql);

        if ($row = mysql_fetch_array($query, MYSQL_NUM)) {
            return $row[0];
        }
    }

    /**
     * 获取一条记录
     *
     * @param $sql string sql语句
     *
     * @return array
     */
    public static function one($sql) {
        $query = self::query($sql . ' limit 1');

        if($row = mysql_fetch_assoc($query)) {
            return $row;
        }
    }

    /**
     * 获取所有记录
     *
     * @param $sql string sql语句
     *
     * @return array
     */
    public static function all($sql) {
        $query = self::query($sql);

        $rows = array();
        while ($row = mysql_fetch_assoc($query)) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * backup all
     *
     * @return string
     */
    public static function backup() {
        // 表
        $sql = 'show tables';
        $query = self::query($sql);

        $backup_sql = '';
        while ($row = mysql_fetch_assoc($query, 2)) {
            $table = $row[0];
            // 结构
            $q = self::query("SHOW CREATE TABLE `{$table}`");
            $create = mysql_result($q, 0, 1);
            $create = "\n\n".$create.";\n\n\n";
            $backup_sql .= $create;

            // 数据
            $sql = "SELECT * FROM `{$table}`";
            $q = self::query($sql);
            $fields = mysql_num_fields($q);

            while($obj = mysql_fetch_object($q)) {
                $backup_sql .= 'INSERT INTO `'.$table.'` (`';

                    for ($i = 0; $i < $fields; $i++) {
                        if ($i == $fields - 1) {
                            $backup_sql .= mysql_field_name($q, $i);
                        } else {
                            $backup_sql .= mysql_field_name($q, $i).'`, `';
                        }
                    }

                    $backup_sql .= '`) VALUES (';

                    for ($i = 0;$i <$fields;$i++){
                        $name = mysql_field_name($q,$i);
                        if (empty($obj->$name)){
                            $obj->$name = 'NULL';
                        }
                        if ($i ==$fields - 1){
                            $backup_sql .= '\''.$obj->$name.'\'';
                        } else {
                            $backup_sql .= '\''.$obj->$name.'\', ';
                        }
                    }

                $backup_sql .= ");\n";
            }
        }
        
        return  $backup_sql;
    }

}
?>
