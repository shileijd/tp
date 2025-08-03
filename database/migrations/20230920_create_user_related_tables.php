<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateUserRelatedTables extends Migrator
{
    public function up()
    {
        // 创建用户表
        $table = $this->table('users');
        $table->addColumn('username', 'string', ['limit' => 50, 'comment' => '用户名'])
              ->addColumn('email', 'string', ['limit' => 100, 'comment' => '邮箱'])
              ->addColumn('password', 'string', ['limit' => 255, 'comment' => '密码'])
              ->addColumn('vip_level', 'integer', ['default' => 0, 'comment' => 'VIP会员等级'])
              ->addColumn('tokens', 'integer', ['default' => 0, 'comment' => '会员点数'])
              ->addColumn('vip_expire_time', 'datetime', ['comment' => '会员到期时间', 'null' => true])
              ->addColumn('create_time', 'datetime', ['comment' => '创建时间', 'null' => true])
              ->addColumn('update_time', 'datetime', ['comment' => '更新时间', 'null' => true])
              ->addIndex(['username'], ['unique' => true])
              ->addIndex(['email'], ['unique' => true])
              ->create();

        // 创建用户购买会员等级记录表
        $table = $this->table('user_vip_purchases');
        $table->addColumn('user_id', 'integer', ['comment' => '用户ID'])
              ->addColumn('vip_level', 'integer', ['comment' => '购买会员等级'])
              ->addColumn('purchase_time', 'datetime', ['comment' => '购买时间'])
              ->addColumn('video_limit', 'integer', ['default' => 0, 'comment' => '可创建视频数'])
              ->addColumn('amount', 'decimal', ['precision' => 10, 'scale' => 2, 'comment' => '购买金额'])
              ->addColumn('create_time', 'datetime', ['comment' => '创建时间', 'null' => true])
              ->addIndex(['user_id'])
              ->create();

        // 创建会员价格设定表
        $table = $this->table('vip_pricing');
        $table->addColumn('level_name', 'string', ['limit' => 50, 'comment' => '会员等级名称'])
              ->addColumn('level', 'integer', ['comment' => '会员等级'])
              ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2, 'comment' => '会员价格'])
              ->addColumn('video_limit', 'integer', ['default' => 0, 'comment' => '可创建视频数'])
              ->addColumn('token_amount', 'integer', ['default' => 0, 'comment' => '赠送点数'])
              ->addColumn('duration', 'integer', ['comment' => '会员时长(天)'])
              ->addColumn('description', 'text', ['comment' => '会员详情', 'null' => true])
              ->addColumn('status', 'integer', ['default' => 1, 'comment' => '状态:1正常,0禁用'])
              ->addColumn('sort', 'integer', ['default' => 0, 'comment' => '排序'])
              ->addColumn('create_time', 'datetime', ['comment' => '创建时间', 'null' => true])
              ->addColumn('update_time', 'datetime', ['comment' => '更新时间', 'null' => true])
              ->addIndex(['level'], ['unique' => true])
              ->create();
    }

    public function down()
    {
        $this->dropTable('vip_pricing');
        $this->dropTable('user_vip_purchases');
        $this->dropTable('users');
    }
}