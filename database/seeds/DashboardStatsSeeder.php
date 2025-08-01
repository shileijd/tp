<?php

use think\migration\Seeder;

class DashboardStatsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'today_reg' => rand(10, 100),
                'today_login' => rand(50, 200),
                'three_day_new' => rand(20, 150),
                'seven_day_new' => rand(50, 300),
                'seven_day_active' => rand(100, 500),
                'month_active' => rand(200, 1000),
                'novel_storyboard' => rand(1000, 5000),
                'novel_storyboard_today' => rand(10, 100),
                'text_to_image' => rand(2000, 10000),
                'text_to_image_today' => rand(20, 200),
                'text_to_video' => rand(500, 3000),
                'text_to_video_today' => rand(5, 50),
                'text_to_speech' => rand(1000, 8000),
                'text_to_speech_today' => rand(10, 100),
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s'),
            ]
        ];
        
        $this->table('dashboard_stats')->insert($data)->save();
        
        echo "Dashboard stats seeder executed successfully\n";
    }
}