<?php
declare (strict_types = 1);

namespace app;

use think\App;
use think\exception\ValidateException;
use think\Validate;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
        // 白名单路由，不需要登录验证
        $whitelist = [
            'admin/admin/index',  // 登录页面
            'admin.admin/index',   // 登录页面的另一种格式
            'index/index/index',
            'index.index/index',
            'index/index/login',
            'index.index/login',
            'index.index/register',
            'index.index/register',
            'index.index/forgot',
            'index.index/forgot',
            'index.index/sendcode',
            'index.index/sendcode',
        ];
        
        // 获取当前路由
        $controller = strtolower($this->request->controller());
        $action = strtolower($this->request->action());
        $route = $controller . '/' . $action;
        
        // 如果在白名单中，直接返回，不执行任何认证逻辑
        if (in_array($route, $whitelist)) {
            return;
        }
        
        // 检查用户是否已登录
        if (!$this->getCurrentUser()) {
            // 未登录则重定向到登录页面
            header('Location: /index');
            exit();
        }
        
        // 将用户信息分配到视图变量
        \think\facade\View::assign('user', $this->getCurrentUser());
    }
    
    /**
     * 获取当前登录用户
     * 
     * @return mixed
     */
    protected function getCurrentUser()
    {
        // 获取当前路由信息
        $controller = strtolower($this->request->controller());
        
        // 根据不同控制器使用不同的session键
        if (strpos($controller, 'admin') !== false) {
            // 后台用户使用admin_user作为session键
            return \think\facade\Session::get('admin_user');
        } else {
            // 前台用户使用username作为session键
            return \think\facade\Session::get('username');
        }
    }

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, string|array $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }

}
