<?php
namespace app\controller\admin;

use app\BaseController;
use think\facade\Captcha;
use think\facade\Session;
use think\facade\Request;
use think\facade\View;
use think\facade\Db;

class Admin extends BaseController
{
    public function index()
    {
        if (Request::isPost()) {
            $username = Request::param('username');
            $password = Request::param('password');
            $captcha = Request::param('captcha');
            
            // 验证码验证
            if (!captcha_check($captcha)) {
                return json(['code' => 0, 'msg' => '验证码错误']);
            }
            
            // 查询用户信息
            $user = Db::table('sz_admin_user')
                ->where('username', $username)
                ->find();
            
            if (!$user) {
                return json(['code' => 0, 'msg' => '用户不存在']);
            }
            
            // 验证密码（实际应用中应使用password_verify()验证加密密码）
            if ($user['password'] !== $password) {
                return json(['code' => 0, 'msg' => '密码错误']);
            }
            
            // 登录成功，保存用户信息到Session
            Session::set('admin_user', $user['username']);
            
            return redirect('dashboard');
        }
        
        return view::fetch();
    }

    public function dashboard()
    {
        $dashboard = Db::table('sz_dashboard_stats')
            ->where('id', '1')
            ->find();
        view::assign('dashboard', $dashboard);
        return view::fetch();
    }

    public function header()
    {
        $user = $this->getCurrentUser();
        View::assign('user', $user);
        return view::fetch();
    }

    public function left()
    {
        return view::fetch();
    }

    public function footer()
    {
        return view::fetch();
    }

    public function logout()
    {
        Session::clear();
        // 使用绝对路径重定向到登录页面，避免路由解析问题
        return redirect('/index');
    }
}