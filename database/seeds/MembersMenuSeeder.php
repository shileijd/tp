<?php

use think\migration\Seeder;
use think\facade\Db;

class MembersMenuSeeder extends Seeder
{
    public function run(): void
    {
        // 检查是否已存在会员管理菜单
        $exists = Db::name('menus')->where('name', '会员管理')->find();
        if ($exists) {
            echo "Members menu already exists\n";
            return;
        }
        
        // 插入会员管理顶级菜单
        $parentId = Db::name('menus')->insertGetId([
            'parent_id' => 0,
            'name' => '会员管理',
            'url' => '',
            'icon' => 'fa fa-users',
            'sort_order' => 50,
            'status' => 1,
            'create_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s'),
        ]);
        
        // 插入子菜单项
        $subMenus = [
            [
                'parent_id' => $parentId,
                'name' => '用户管理',
                'url' => 'admin/members/index',
                'icon' => 'fa fa-user',
                'sort_order' => 0,
                'status' => 1,
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s'),
            ],
            [
                'parent_id' => $parentId,
                'name' => '购买记录',
                'url' => 'admin/members/purchaseRecords',
                'icon' => 'fa fa-history',
                'sort_order' => 1,
                'status' => 1,
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s'),
            ],
            [
                'parent_id' => $parentId,
                'name' => '价格设定',
                'url' => 'admin/members/pricing',
                'icon' => 'fa fa-dollar',
                'sort_order' => 2,
                'status' => 1,
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s'),
            ],
        ];
        
        Db::name('menus')->insertAll($subMenus);
        
        echo "Members menu seeder executed successfully\n";
    }
}