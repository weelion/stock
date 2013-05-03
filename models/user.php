<?php 
/**
 * 会员数据模型
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class User_Model
{
    /**
     * 登录
     *
     * @param $username string 帐号
     * @param $password string 密码
     * @param $remember intger 是否记住密码一周
     *
     * @return void
     */
    public static function doLogin($username, $password, $remember) {

        $flat = false;

        $sql = "select id, username,password, salt, role from users where username = '{$username}'";
        $user =  DB::one($sql);

        if(!empty($user)) {

            $pwd = md5($user['salt'] .$password . md5($user['salt']));


            $log_data = '登录';
            $_SESSION['s_uid'] = $user['id'];

            if($pwd == $user['password']) {
                $token = md5($username . md5($pwd));

                $cookie = array('uid' => $user['id'], 'token' => $token);
                $cookie = base64_encode(serialize($cookie));

                if($remember) {
                    setcookie("kc_token", $cookie);
                } else {
                    setcookie("kc_token", $cookie,  time()+3600*24*7);
                }

                $flat = true;

                $log_extra = '成功';

                $_SESSION['s_role'] = $user['role'];
                $_SESSION['s_username'] = $user['username'];
                
                logs('user', $log_data, $log_extra);
            } else {
				$log_extra = '失败'; 
                logs('user', $log_data, $log_extra);
                unset($_SESSION['s_uid']);
            }

            
        } 

        return $flat;
    }

    /**
     * 退出登录
     */
    public static function doLogout() {
        setcookie("kc_token", '', -1);
        unset($_SESSION['s_role']);
        unset($_SESSION['s_username']);
        unset($_SESSION['s_uid']);
        header('location:' . home_url());
    }

    /**
     * 检查登录状态
     */
    public static function checkLogin() {
        $cookie = self::exCookie();

        $flat = false;
        if(!empty($cookie)) {

            $uid    = $cookie['uid'] ? $cookie['uid'] : 0;
            $token  = $cookie['token'] ? $cookie['token'] : '';
            if(self::checkToken($uid, $token)) {
                $flat = true;
            }
        }

        return $flat;
    }

    /**
     * cookie 登录
     */
    public static function autoLogin() {
        $cookie = self::exCookie();

        if(!empty($cookie)) {
            $uid    = $cookie['uid'] ? $cookie['uid'] : 0;
            $token  = $cookie['token'] ? $cookie['token'] : '';


            $flat = false;
            if(self::checkToken($uid, $token)) {
                $extra = '成功';
                $flat = true;
            } else {
                $extra = '失败'; 
                setcookie('kc_token', '', -1);
            }

            // logs('user', '登录', $extra);

            if($flat) header("location:".home_url() . '?m=user&a=center');

        }

    }

    /**
     * 检查token是否合法
     *
     * @param $uid   integer 用户id
     * @param $token string token
     *
     * @return boolean
     */
    private static function checkToken($uid, $token) {
        $sql = "select username, password, salt, role from users where id = " . $uid;
        $user = DB::one($sql);

        $utoken = md5($user['username'] . md5($user['password']));

        $flat = false;
        if($token == $utoken) {
            $flat = true;
            $_SESSION['s_role'] = $user['role'];
            $_SESSION['s_username'] = $user['username'];
            $_SESSION['s_uid'] = $uid;
        }

        return $flat;
    }

    /**
     * 解析cookie数据
     *
     * @return array
     */
    private static function exCookie() {
        $cookie = isset($_COOKIE['kc_token']) ? $_COOKIE['kc_token'] : '';

        $rs = array();
        if($cookie) {
            $rs = unserialize(base64_decode($cookie));
        }

        return $rs;
    }

    /**
     * 会员数目
     */
    public static function total($filter) {

        $sql = "select count(id) as count from `users`";
        if($filter) {
            $sql .= ' where 1=1 ';
            foreach($filter as $field => $value) {
                if($field == 'username'){
                    $sql .= ' and `username` like \''.$value.'%\'';
                }else{
                    $sql .= " and `{$field}` = '{$value}'";
                }
            }
        }

        return DB::only($sql);
    }

    /**
     * 会员搜索
     */
    public static function filter($filter, $page, $pagesize) {
        $offset = ($page -1) * $pagesize;

        $sql = 'select *,u.id from `users` u left join `role` r on u.role=r.id';
        if($filter) {
            $sql .= ' where 1=1 ';
            foreach($filter as $field => $value) {
                if($field == 'username'){
                    $sql .= ' and `username` like \''.$value.'%\'';
                }else{
                    $sql .= " and `{$field}` = '{$value}'";
                }
            }
        }

        $sql .= ' order by u.id desc';
        $sql .= ' limit ' . $offset . ', '. $pagesize;

        return DB::all($sql);
    }

    /**
     * 添加会员
     *
     * @param $username string  用户名
     * @param $password string  密码
     * @param $role     integer 用户角色
     *
     * @return array
     */
    public static function doAdd($username, $password, $role) {
        if(empty($username)) {
            return array('status'=>false,'msg'=>'用户名不能为空');
        }
        if(empty($password)) {
            return array('status'=>false,'msg'=>'密码不能为空');
        }

        if(self::check($username)){
            return array('status'=>false,'msg'=>'用户名已经存在');
        }
        $salt = rand_str();
        $password = md5($salt .$password . md5($salt));
        $sql = 'insert into users (`username`,`password`,`role`,`salt`) values (\''.$username.'\',\''.$password.'\',\''.$role.'\',\''.$salt.'\')';
        $cookie = self::exCookie();

        if(DB::query($sql)) {
            $result = array('status'=>true,'msg'=>'新增会员成功');
            $extra = '成功';
        }else{
            $result = array('status'=>false,'msg'=>'操作失败');
            $extra = '失败';
        }
        logs('user', '新增会员[' . $username . ']', $extra);
        return $result;
    }

    /**
     * 检查用户名
     */
    public static function check($username, $uid = 0) {
        $sql = 'select `id` from `users` where `username`=\''.$username.'\'';
        $id = DB::only($sql);

        if(!empty($uid))
            $return = (empty($id) || $id == $uid) ? false : true;
        else 
            $return = empty($id) ? false : true;

        return $return;
    }

    /**
     * 编辑会员
     *
     * @return boolean
     */
    public static function update($id, $data) {
        if(empty($id) || empty($data)) return false;

        $set = '';
        foreach ($data as $key => $value) {
            $set .= ", `{$key}`='{$value}'";
        }

        $sql = "update `users` set `id` = `id` {$set} where `id` = '{$id}'";

        return DB::query($sql);
    }

    /**
     * 删除会员
     *
     * @param $uid integer 会员id
     *
     * @return array
     */
    static function delete($id) {
        $sql = "DELETE FROM `users` WHERE `id`='{$id}'";

        if(DB::query($sql)) {
            $result = array('status'=>true,'msg'=>"删除会员成功");
            $extra = '成功';
        }else{
            $result = array('status'=>false, 'msg'=>'操作失败');
            $extra = '失败';
        }

        logs('user', "删除会员ID为{$id}", $extra);

        return $result;        
    }

    /**
     * 修改密码
     */
    static function changepwd($oldpwd,$pwd) {
        if(empty($oldpwd)) {
            return array('status'=>false,'msg'=>'请输入原始密码');
        }
        if(empty($pwd)) {
            return array('status'=>false,'msg'=>'请输入新的密码');
        }
        $cookie = self::exCookie();
        $sql = 'select password, salt from users where id =' . $cookie['uid'];
        $user = DB::one($sql);
        $oldpwd = md5($user['salt'] .$oldpwd . md5($user['salt']));

        if($oldpwd != $user['password']) {
            return array('status'=>false,'msg'=>'原始密码输入有误');
        }
        $salt = rand_str();
        $pwd = md5($salt . $pwd . md5($salt));
        $sql = 'UPDATE `users` SET `password`=\''.$pwd.'\',`salt`=\''.$salt.'\' WHERE `id`='.$cookie['uid'];

        if(DB::query($sql)) {
            $result = array('status'=>true,'msg'=>'密码更改成功');
            $extra = '成功';
        }else{
            $result = array('status'=>false,'msg'=>'操作失败');
            $extra = '失败';
        }
        logs('user', '更改密码', $extra);
        return $result;
    }

    /**
     * 会员信息
     *
     * @param $id     array id
     * @param $field  array 字段
     *
     * @return array
     */
    static function info($id, $field = array()) {

        $field = empty($field)? '*' : '`' . implode('`, `', $field) . '`';

        $sql = "select {$field} from `users` where `id` = '{$id}'";

        return DB::one($sql);
    }

    /**
     * 认证权限
     */
    public static function has_auth($auth = '') {
        $flat = true;
        if(empty($auth)) {
            $m = get('m');
            $a = get('a');
            $t = get('t');

            if(empty($m) || empty($a))
                return false;

            $auth = $m . '_' . $a;
            if(get('t')) {
                $auth .= '_' . $t;
            }

            $auth = strtolower($auth);
        }

        // 检查权限
        $all_auths = all_auths();

        if(in_array($auth, all_auths())) {
            $user = self::info($_SESSION['s_uid']);
            $my_auths = unserialize($user['auths']);


            if(!$my_auths || !in_array($auth, $my_auths)) {
                $flat = false;
            }
        }

        return $flat;
    }
}