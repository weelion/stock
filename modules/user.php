<?php
/**
 * 用户类
 *
 * @author weelion
 * @via    深圳市木槿软件工作室
 * @email  377658@qq.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */
class User extends base
{

    /**
     * 登录页面
     */
    public function action_login() {

        if($_POST) {
        	$username = get('username');
        	$password = get('password');
        	$remember = get('remember');

        	$check = User_Model::doLogin($username, $password, $remember);

        	if($check) {
        		echo 1;
        	} else {
        		echo 0;
        	}

        	die;
        } else {
        	User_Model::autoLogin();
        	Render::with('user_login')->show();
        }
    }

    /**
     * 登出
     */
    public function action_logout() {
    	User_Model::doLogout();
    }

    /**
     * 会员后台跳转
     */
    public function action_center() {

        if(in_array($_SESSION['s_role'], Config::get('admin')))
            header('location:' . home_url() . '?m=user&a=index');

        if(in_array($_SESSION['s_role'], Config::get('store_clerk'))) 
            header('location:' . home_url() . '?m=stock&a=index');

        if(in_array($_SESSION['s_role'], Config::get('salesman'))) 
            header('location:' . home_url() . '?m=info&a=index');

    }

    /**
     * 修改密码页面
     */
    public function action_changepwd() {
        Render::with('user_changepwd')->show();
    }

    /**
     * 修改密码
     */
    public function action_doChangepwd() {
        $pwd = isset($_POST['newpwd'])?$_POST['newpwd']:'';
        $oldpwd = isset($_POST['oldpwd'])?$_POST['oldpwd']:'';
        $do = User_Model::changepwd($oldpwd,$pwd);

        Render::json($do);
    }

    /**
     * 会员列表
     */
    public function action_index() {
        $filter = array();
        $uname = get('username');
        if(!empty($uname)) {
            $filter['username'] = $uname;
        }
        $total = User_Model::total($filter);
        $pagesize = PAGESIZE ;
        $pagination = new Pagination();
        $pagination->records($total);
        $pagination->records_per_page($pagesize);

        $page = $pagination->get_page();
        $data['list'] = User_Model::filter($filter, $page, $pagesize);
        $data['pagination'] = $pagination;
        $data['role'] = Role_Model::filter();

        Render::with('user_list', $data)->show();

    }

    /**
     * 会员编辑
     */
    public function action_doEdit() {
        $id = get('id');

        $role = get('role');
        $username = get('username');
        $password = get('password');

        $extra = '失败';
        $result = array('status' => false, 'msg' => '用户名已存在。');
        if(!User_Model::check($username, $id)) {

            $data = array(
                'username' => $username,
                'role'     => $role,
                );

            if(!empty($password)) {
                $salt = rand_str();
                $password = md5($salt .$password . md5($salt));
                $data['password'] = $password;
                $data['salt']     = $salt;
            }
            
            $result = array('status' => false, 'msg' => '编辑会员失败。');
            if(User_Model::update($id, $data)) {
                $extra = '成功';
                $result = array('status' => true, 'msg' => '编辑会员成功。');
            }
        }

        logs('user', "编辑会员ID为{$id}", $extra);

        Render::json($result);
    }

    /**
     * 会员删除
     */
    public function action_doDel() {
        $id = get('id');
        $result = User_Model::delete($id);

        Render::json($result);
    }

    /**
     * 新增会员
     */
    public function action_doAdd() {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $result = User_Model::doAdd($username, $password, $role);

        Render::json($result);
    }

    /**
     * 会员信息
     */
    public function action_info() {
        $id     = get('id');
        $field  = array('id','role','username');
        $result = User_Model::info($id, $field);

        Render::json($result);
    }

    /**
     * 会员权限
     */
    public function action_auth() {
        $id = get('id');
        $user = User_Model::info($id);
        $role = $user['role'];

        $role_auths = Config::get('auths');
        if(!isset($role_auths[$role])) {
            error('错误', '权限加载有误。');
        }

        $this_auths = $role_auths[$role];

        $auths = array('auths' => $this_auths, 'my_auth' => unserialize($user['auths']));

        Render::json($auths);
    }

    /**
     * 保存权限
     */
    public function action_doAuth() {
        $id = get('id');
        $auths = get('auths');

        $auths = serialize($auths);
        $data = array('auths' => $auths);

        $result = array('status' => false, 'msg' => '编辑会员权限失败。');
        $extra = '失败';
        if(User_Model::update($id, $data)){
            $result = array('status' => true, 'msg' => '编辑会员权限成功。');
            $extra = '成功';
        }

        logs('user', "编辑会员ID为{$id}权限", $extra);

        Render::json($result);
    }

}