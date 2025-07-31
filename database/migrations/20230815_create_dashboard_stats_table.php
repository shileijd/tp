<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateDashboardStatsTable extends Migrator
{
    public function change()
    {
        $table = $this->table('dashboard_stats');
        $table->addColumn('today_reg', 'integer', ['default' => 0, 'comment' => '今日注册数'])
              ->addColumn('today_login', 'integer', ['default' => 0, 'comment' => '今日登录数'])
              ->addColumn('three_day_new', 'integer', ['default' => 0, 'comment' => '三日新增数'])
              ->addColumn('seven_day_new', 'integer', ['default' => 0, 'comment' => '七日新增数'])
              ->addColumn('seven_day_active', 'integer', ['default' => 0, 'comment' => '七日活跃数'])
              ->addColumn('month_active', 'integer', ['default' => 0, 'comment' => '月活跃数'])
              ->addColumn('novel_storyboard', 'integer', ['default' => 0, 'comment' => '小说分镜总数'])
              ->addColumn('novel_storyboard_today', 'integer', ['default' => 0, 'comment' => '小说分镜今日新增'])
              ->addColumn('text_to_image', 'integer', ['default' => 0, 'comment' => '文生图总数'])
              ->addColumn('text_to_image_today', 'integer', ['default' => 0, 'comment' => '文生图今日新增'])
              ->addColumn('text_to_video', 'integer', ['default' => 0, 'comment' => '文生视频总数'])
              ->addColumn('text_to_video_today', 'integer', ['default' => 0, 'comment' => '文生视频今日新增'])
              ->addColumn('text_to_speech', 'integer', ['default' => 0, 'comment' => '文字转语音总数'])
              ->addColumn('text_to_speech_today', 'integer', ['default' => 0, 'comment' => '文字转语音今日新增'])
              ->addColumn('create_time', 'datetime', ['comment' => '创建时间', 'null' => true])
              ->addColumn('update_time', 'datetime', ['comment' => '更新时间', 'null' => true])
              ->create();
    }
}