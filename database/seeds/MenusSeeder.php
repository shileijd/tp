<?php

use think\migration\Seeder;

class MenusSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'parent_id' => 0,
                'name' => '首页',
                'url' => 'admin/index/index',
                'icon' => 'fa fa-home',
                'sort_order' => 0,
                'status' => 1,
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s'),
            ],
            [
                'parent_id' => 0,
                'name' => '系统管理',
                'url' => '',
                'icon' => 'fa fa-cogs',
                'sort_order' => 100,
                'status' => 1,
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s'),
            ],
            [
                'parent_id' => 2,
                'name' => '菜单管理',
                'url' => 'admin/menu/index',
                'icon' => 'fa fa-list',
                'sort_order' => 0,
                'status' => 1,
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s'),
            ],
            [
                'parent_id' => 2,
                'name' => '用户管理',
                'url' => 'admin/user/index',
                'icon' => 'fa fa-user',
                'sort_order' => 1,
                'status' => 1,
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s'),
            ],
        ];
        
        $this->table('menus')->insert($data)->save();
        
        echo "Menus seeder executed successfully\n";
    }
}