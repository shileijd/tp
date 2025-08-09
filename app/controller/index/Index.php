<?php

namespace app\controller\index;

use app\BaseController;
use app\common\service\MailService;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;
use think\facade\Db;

class Index extends BaseController
{
    public function index($user=null)
    {   
        $user = $this->getCurrentUser();
        view::assign('user', $user);
        return view::fetch('');
    }

   

    public function login()
    {
        if (request()->isPost()) {
            $username = request()->param('username');
            $password = request()->param('password');
            
            // 验证用户名和密码是否为空
            if (empty($username) || empty($password)) {
                return json(['code' => 0, 'msg' => '用户名和密码不能为空']);
            }
            
            // 在数据库中查找用户
            $user = \think\facade\Db::name('users')->where('username', $username)->find();
            
            // 检查用户是否存在
            if (!$user) {
                return json(['code' => 0, 'msg' => '用户不存在']);
            }
            
            // 验证密码
            if (!password_verify($password, $user['password'])) {
                return json(['code' => 0, 'msg' => '密码错误']);
            }
            
            // 登录成功，将用户信息保存到session
            session('user_id', $user['id']);
            session('username', $user['username']);
            
            // 返回成功信息
            return json(['code' => 1, 'msg' => '登录成功', 'url' => '/index/index']);
        }
        
        return view('');
    }

    public function register()
    {
        if (request()->isPost()) {
            $username = request()->param('username');
            $email = request()->param('email');
            $password = request()->param('password');
            $confirmPassword = request()->param('confirm_password');
            $code = request()->param('code');
            
            // 验证必填字段
            if (empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($code)) {
                return json(['code' => 0, 'msg' => '所有字段都不能为空']);
            }
            
            // 验证密码一致性
            if ($password !== $confirmPassword) {
                return json(['code' => 0, 'msg' => '两次输入的密码不一致']);
            }
            
            // 验证验证码
            $sessionCode = session('email_code');
            $sessionCodeTime = session('email_code_time');
            
            // 检查验证码是否存在
            if (empty($sessionCode)) {
                return json(['code' => 0, 'msg' => '请先获取验证码']);
            }
            
            // 检查验证码是否过期（5分钟）
            if (time() - $sessionCodeTime > 300) {
                return json(['code' => 0, 'msg' => '验证码已过期，请重新获取']);
            }
            
            // 检查验证码是否正确
            if ($code != $sessionCode) {
                return json(['code' => 0, 'msg' => '验证码错误']);
            }
            
            // 验证用户名是否已存在
            $existingUser = \think\facade\Db::name('users')->where('username', $username)->find();
            if ($existingUser) {
                return json(['code' => 0, 'msg' => '用户名已存在']);
            }
            
            // 验证邮箱是否已存在
            $existingEmail = \think\facade\Db::name('users')->where('email', $email)->find();
            if ($existingEmail) {
                return json(['code' => 0, 'msg' => '邮箱已被注册']);
            }
            
            // 密码加密
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // 插入新用户
            $userId = \think\facade\Db::name('users')->insertGetId([
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword,
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s')
            ]);
            
            // 清除验证码session
            session('email_code', null);
            session('email_code_time', null);
            
            if ($userId) {
                return json(['code' => 1, 'msg' => '注册成功', 'url' => '/index/index/login']);
            } else {
                return json(['code' => 0, 'msg' => '注册失败，请重试']);
            }
        }
        
        return view('');
    }

    public function forgot()
    {
        return view('');
    }
    
    public function logout()
    {
        // 清除用户session
        session('user_id', null);
        session('username', null);
        
        // 重定向到首页
        return redirect('/index');
    }
    
    public function profile()
    {
        // 获取当前用户信息
        $user = session('username');
        
        // 如果用户未登录，重定向到登录页面
        if (empty($user)) {
            return redirect('/index/index/login');
        }
        
        // 将用户信息传递给视图
        view()->assign('user', $user);
        
        return view('');
    }
    
    public function sendCode()
    {
        try {
            if (request()->isPost()) {
                $email = request()->param('email');
                
                // 验证邮箱是否为空
                if (empty($email)) {
                    return json(['code' => 0, 'msg' => '邮箱不能为空']);
                }
                
                // 验证邮箱格式
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return json(['code' => 0, 'msg' => '邮箱格式不正确']);
                }
                
                // 生成6位随机验证码
                $code = rand(100000, 999999);
                
                // 将验证码保存到session中，设置5分钟过期
                session('email_code', $code);
                session('email_code_time', time());
                
                // 发送验证码邮件
                $mailService = new MailService();
                $subject = '由山竹AI发来的验证码';
                $content = "您的验证码是：{$code}，5分钟内有效。";
                $result = $mailService->sendMail($email, $subject, $content);
                
                if ($result['status']) {
                    return json(['code' => 1, 'msg' => '验证码已发送到您的邮箱']);
                } else {
                    return json(['code' => 0, 'msg' => '验证码发送失败：' . $result['message']]);
                }
            }
            
            return json(['code' => 0, 'msg' => '请求方式错误']);
        } catch (\Exception $e) {
            // 记录错误日志
            trace('Send code error: ' . $e->getMessage(), 'error');
            return json(['code' => 0, 'msg' => '服务器内部错误']);
        }
    }
}
