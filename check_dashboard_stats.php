<?php
// 导入数据库配置
require_once 'vendor/autoload.php';

// 加载环境变量
$env = parse_ini_file('.env');

try {
    // 创建PDO连接
    $dsn = "mysql:host={$env['DB_HOST']};port={$env['DB_PORT']};dbname={$env['DB_NAME']};charset={$env['DB_CHARSET']}";
    $pdo = new PDO($dsn, $env['DB_USER'], $env['DB_PASS']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 查询dashboard_stats表中的数据
    $stmt = $pdo->query('SELECT * FROM sz_dashboard_stats');
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 输出结果
    echo "Dashboard Stats Data:\n";
    foreach ($results as $row) {
        print_r($row);
    }
    
} catch (PDOException $e) {
    echo "数据库错误: " . $e->getMessage();
}
?>