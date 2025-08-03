<?php

namespace app\controller\admin;

use app\BaseController;
use app\middleware\Auth;
use think\facade\Db;
use think\facade\View;
use think\Request;

class Members extends BaseController
{
    protected $middleware = [Auth::class];
    
    // 用户列表
    public function index()
    {
        $users = Db::name('users')->order('id', 'desc')->paginate(10);
        return View::fetch('', [
            'users' => $users
        ]);
    }
    
    // 删除用户
    public function delete($id)
    {
        $result = Db::name('users')->where('id', $id)->delete();
        
        if ($result) {
            return json(['code' => 1, 'msg' => '删除成功']);
        } else {
            return json(['code' => 0, 'msg' => '删除失败']);
        }
    }
    
    // 用户购买记录
    public function purchaseRecords()
    {
        $records = Db::name('user_vip_purchases')
            ->alias('p')
            ->join('users u', 'p.user_id = u.id')
            ->field('p.*, u.username')
            ->order('p.id', 'desc')
            ->paginate(10);
            
        return View::fetch('', [
            'records' => $records
        ]);
    }
    
    // 会员价格设定列表
    public function pricing()
    {
        $pricings = Db::name('vip_pricing')->order('sort', 'asc')->paginate(10);
        return View::fetch('', [
            'pricings' => $pricings
        ]);
    }
    
    // 保存会员价格设定
    public function savePricing(Request $request)
    {
        $data = $request->post();
        
        // 验证数据
        if (empty($data['level_name']) || !isset($data['level']) || !isset($data['price'])) {
            return json(['code' => 0, 'msg' => '请填写完整信息']);
        }
        
        // 检查会员等级是否已存在
        $exists = Db::name('vip_pricing')->where('level', $data['level'])->where('id', '<>', isset($data['id']) ? $data['id'] : 0)->find();
        if ($exists) {
            return json(['code' => 0, 'msg' => '该会员等级已存在']);
        }
        
        // 保存数据
        $data['update_time'] = date('Y-m-d H:i:s');
        
        if (isset($data['id']) && !empty($data['id'])) {
            // 更新
            $data['create_time'] = Db::name('vip_pricing')->where('id', $data['id'])->value('create_time');
            $result = Db::name('vip_pricing')->where('id', $data['id'])->save($data);
        } else {
            // 新增
            $data['create_time'] = date('Y-m-d H:i:s');
            $result = Db::name('vip_pricing')->insert($data);
        }
        
        if ($result) {
            return json(['code' => 1, 'msg' => '保存成功']);
        } else {
            return json(['code' => 0, 'msg' => '保存失败']);
        }
    }
    
    // 编辑会员价格设定
    public function editPricing($id = null)
    {
        $pricing = [];
        if ($id) {
            $pricing = Db::name('vip_pricing')->where('id', $id)->find();
            if (!$pricing) {
                $this->error('会员价格信息不存在');
            }
        }
        $pricing['id'] = $id;
        return View::fetch('', [
            'pricing' => $pricing
        ]);
    }
    
    // 删除会员价格设定
    public function deletePricing($id)
    {
        $result = Db::name('vip_pricing')->where('id', $id)->delete();
        
        if ($result) {
            return json(['code' => 1, 'msg' => '删除成功']);
        } else {
            return json(['code' => 0, 'msg' => '删除失败']);
        }
    }
    
    // 更改会员价格设定状态
    public function togglePricingStatus($id)
    {
        $pricing = Db::name('vip_pricing')->where('id', $id)->find();
        if (!$pricing) {
            return json(['code' => 0, 'msg' => '记录不存在']);
        }
        
        $status = $pricing['status'] == 1 ? 0 : 1;
        $result = Db::name('vip_pricing')->where('id', $id)->save(['status' => $status]);
        
        if ($result) {
            return json(['code' => 1, 'msg' => '操作成功', 'status' => $status]);
        } else {
            return json(['code' => 0, 'msg' => '操作失败']);
        }
    }
}