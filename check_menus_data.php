<?php
// 引入自动加载文件
require_once __DIR__ . '/vendor/autoload.php';

// 创建数据库连接
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=test;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 查询菜单数据
    $stmt = $pdo->query('SELECT * FROM sz_menus ORDER BY parent_id, sort_order');
    $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 输出菜单数据
    echo "菜单数据:\n";
    foreach ($menus as $menu) {
        echo "ID: {$menu['id']}, 名称: {$menu['name']}, 父级ID: {$menu['parent_id']}, URL: {$menu['url']}, 图标: {$menu['icon']}, 排序: {$menu['sort_order']}, 状态: {$menu['status']}\n";
    }
} catch (PDOException $e) {
    echo "数据库错误: " . $e->getMessage() . "\n";
}