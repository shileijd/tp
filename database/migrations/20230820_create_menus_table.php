<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateMenusTable extends Migrator
{
    public function up()
    {
        $table = $this->table('menus', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $table->addColumn('parent_id', 'integer', ['default' => 0, 'comment' => '父级菜单ID，0表示顶级菜单'])
              ->addColumn('name', 'string', ['limit' => 50, 'comment' => '菜单名称'])
              ->addColumn('url', 'string', ['limit' => 255, 'default' => '', 'comment' => '菜单URL'])
              ->addColumn('icon', 'string', ['limit' => 50, 'default' => '', 'comment' => '菜单图标'])
              ->addColumn('sort_order', 'integer', ['default' => 0, 'comment' => '排序'])
              ->addColumn('status', 'boolean', ['default' => 1, 'comment' => '状态：1显示，0隐藏'])
              ->addColumn('create_time', 'datetime', ['comment' => '创建时间', 'null' => true])
              ->addColumn('update_time', 'datetime', ['comment' => '更新时间', 'null' => true])
              ->addIndex(['parent_id'])
              ->addIndex(['sort_order'])
              ->create();
    }

    public function down()
    {
        $this->table('menus')->drop();
    }
}