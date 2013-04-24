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
     * 会员后台
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
     * 修改密码
     */
    public function action_changepwd() {

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
        $password = $_POST['password'];
        $role = $_POST['role'];
        $uid = $_POST['uid'];
        $result = User_Model::doEdit($uid, $password, $role);
        Render::json($result);
    }

    /**
     * 会员删除
     */
    public function action_doDel() {
        $uid = get('uid');
        $result = User_Model::doDel($uid);
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

    public function action_info() {
        $uid = get('uid');
        $filter = array('id'=>$uid);
        $field = array('id','role','username');
        $result = User_Model::info($field, $filter);
        Render::json($result);
    }
}