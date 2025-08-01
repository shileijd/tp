<?php
namespace app\controller\admin;

use app\BaseController;
use think\facade\Db;
use think\facade\View;
use think\facade\Session;
use think\facade\Request;

class Menu extends BaseController
{
    /**
     * 菜单列表
     */
   

    public function index()
    {
        // 获取所有菜单
        $menus = Db::name('menus')->order('parent_id', 'asc')->order('sort_order', 'asc')->select();
        
        // 构建菜单树
        $menuTree = $this->buildMenuTree($menus);

        return View::fetch('',[
            'menus' => $menuTree
        ]);
    }
    
    /**
     * 构建菜单树
     */
    private function buildMenuTree($menus, $parentId = 0)
    {
        $tree = [];
        
        foreach ($menus as $menu) {
            if ($menu['parent_id'] == $parentId) {
                $children = $this->buildMenuTree($menus, $menu['id']);
                if (!empty($children)) {
                    $menu['children'] = $children;
                }
                $tree[] = $menu;
            }
        }
        
        return $tree;
    }
    
    /**
     * 添加菜单页面
     */
    public function add()
    {
        // 获取所有顶级菜单
        $topMenus = Db::name('menus')->where('parent_id', 0)->order('sort_order', 'asc')->select();
        
        return View::fetch('', [
            'topMenus' => $topMenus
        ]);
    }
    
    /**
     * 保存新增菜单
     */
    public function save(Request $request)
    {
        $data = [
            'parent_id' => $request->param('parent_id', 0),
            'name' => $request->param('name', ''),
            'url' => $request->param('url', ''),
            'icon' => $request->param('icon', ''),
            'sort_order' => $request->param('sort_order', 0),
            'status' => $request->param('status', 1),
            'create_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s')
        ];
        
        try {
            Db::name('menus')->insert($data);
            return json(['code' => 0, 'msg' => '菜单添加成功']);
        } catch (\Exception $e) {
            return json(['code' => 1, 'msg' => '菜单添加失败：' . $e->getMessage()]);
        }
    }
    
    /**
     * 编辑菜单页面
     */
    public function edit($id)
    {
        $menu = Db::name('menus')->where('id', $id)->find();
        if (!$menu) {
            return json(['code' => 1, 'msg' => '菜单不存在']);
        }
        
        // 获取所有顶级菜单
        $topMenus = Db::name('menus')->where('parent_id', 0)->order('sort_order', 'asc')->select();
        
        return View::fetch('', [
            'menu' => $menu,
            'topMenus' => $topMenus
        ]);
    }
    
    /**
     * 更新菜单
     */
    public function update(Request $request, $id)
    {
        $menu = Db::name('menus')->where('id', $id)->find();
        if (!$menu) {
            return json(['code' => 1, 'msg' => '菜单不存在']);
        }
        
        $data = [
            'parent_id' => $request->param('parent_id', 0),
            'name' => $request->param('name', ''),
            'url' => $request->param('url', ''),
            'icon' => $request->param('icon', ''),
            'sort_order' => $request->param('sort_order', 0),
            'status' => $request->param('status', 1),
            'update_time' => date('Y-m-d H:i:s')
        ];
        
        try {
            Db::name('menus')->where('id', $id)->update($data);
            return json(['code' => 0, 'msg' => '菜单更新成功']);
        } catch (\Exception $e) {
            return json(['code' => 1, 'msg' => '菜单更新失败：' . $e->getMessage()]);
        }
    }
    
    /**
     * 删除菜单
     */
    public function delete($id)
    {
        $menu = Db::name('menus')->where('id', $id)->find();
        if (!$menu) {
            return json(['code' => 1, 'msg' => '菜单不存在']);
        }
        
        // 检查是否有子菜单
        $hasChildren = Db::name('menus')->where('parent_id', $id)->count();
        if ($hasChildren > 0) {
            return json(['code' => 1, 'msg' => '该菜单下有子菜单，不能删除']);
        }
        
        try {
            Db::name('menus')->where('id', $id)->delete();
            return json(['code' => 0, 'msg' => '菜单删除成功']);
        } catch (\Exception $e) {
            return json(['code' => 1, 'msg' => '菜单删除失败：' . $e->getMessage()]);
        }
    }
}

