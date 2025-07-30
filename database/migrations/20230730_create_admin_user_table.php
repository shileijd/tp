<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateAdminUserTable extends Migrator
{
    public function change()
    {
        $table = $this->table('admin_user');
        $table->addColumn('username', 'string', ['limit' => 50, 'comment' => '用户名'])
              ->addColumn('password', 'string', ['limit' => 255, 'comment' => '密码'])
              ->addColumn('email', 'string', ['limit' => 100, 'comment' => '邮箱', 'null' => true])
              ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'comment' => '状态:1正常,0禁用'])
              ->addColumn('create_time', 'datetime', ['comment' => '创建时间', 'null' => true])
              ->addColumn('update_time', 'datetime', ['comment' => '更新时间', 'null' => true])
              ->addIndex(['username'], ['unique' => true])
              ->create();
    }
}