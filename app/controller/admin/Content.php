<?php
namespace app\controller\admin;

use app\BaseController;
use think\facade\Request;
use think\facade\View;
use think\facade\Db;
use think\facade\Session;

class Content extends BaseController
{
    // 文章列表
    public function index()
    {
        $news = Db::table('sz_news')
            ->where('status', 1)
            ->order('created_at', 'desc')
            ->paginate(10);
        
        View::assign('news', $news);
        return View::fetch();
    }
    
    // 新撰写文章页面
    public function create()
    {
        // 获取菜单列表
        $menus = Db::table('sz_menus')
            ->where('status', 1)
            ->select();
        
        View::assign('menus', $menus);
        return View::fetch();
    }
    
    // 保存新文章
    public function save()
    {
        if (Request::isPost()) {
            $data = [
                'menu_id' => Request::param('menu_id'),
                'title' => Request::param('title'),
                'author' => Request::param('author'),
                'date' => Request::param('date'),
                'content' => Request::param('content'),
                'views' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $result = Db::table('sz_news')->insert($data);
            
            if ($result) {
                return json(['code' => 1, 'msg' => '文章发布成功']);
            } else {
                return json(['code' => 0, 'msg' => '文章发布失败']);
            }
        }
    }
    
    // 编辑文章页面
    public function edit($id)
    {
        $article = Db::table('sz_news')
            ->where('id', $id)
            ->where('status', 1)
            ->find();
            
        if (!$article) {
            return '文章不存在';
        }
        
        // 获取菜单列表
        $menus = Db::table('sz_menus')
            ->where('status', 1)
            ->select();
        
        View::assign('article', $article);
        View::assign('menus', $menus);
        return View::fetch();
    }
    
    // 更新文章
    public function update($id)
    {
        if (Request::isPost()) {
            $data = [
                'menu_id' => Request::param('menu_id'),
                'title' => Request::param('title'),
                'author' => Request::param('author'),
                'date' => Request::param('date'),
                'content' => Request::param('content'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $result = Db::table('sz_news')
                ->where('id', $id)
                ->where('status', 1)
                ->update($data);
            
            if ($result !== false) {
                return json(['code' => 1, 'msg' => '文章更新成功']);
            } else {
                return json(['code' => 0, 'msg' => '文章更新失败']);
            }
        }
    }
    
    // 删除文章
    public function delete($id)
    {
        $result = Db::table('sz_news')
            ->where('id', $id)
            ->update(['status' => 0]);
            
        if ($result) {
            return json(['code' => 1, 'msg' => '文章删除成功']);
        } else {
            return json(['code' => 0, 'msg' => '文章删除失败']);
        }
    }
}