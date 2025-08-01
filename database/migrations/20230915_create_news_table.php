<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateNewsTable extends Migrator
{
    public function change()
    {
        $table = $this->table('news');
        $table->addColumn('menu_id', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '所属菜单ID'])
            ->addColumn('title', 'string', ['limit' => 255, 'default' => '', 'comment' => '标题'])
            ->addColumn('author', 'string', ['limit' => 100, 'default' => '', 'comment' => '作者名'])
            ->addColumn('date', 'date', ['comment' => '发布日期'])
            ->addColumn('views', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '阅读量'])
            ->addColumn('content', 'text', ['comment' => '内容'])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'comment' => '创建时间'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'comment' => '更新时间'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'comment' => '状态：1-正常，0-删除'])
            ->addIndex(['menu_id'])
            ->create();
    }
}