<?php

// 引入ThinkPHP核心文件
require_once './vendor/autoload.php';

// 加载环境变量
if (file_exists('.env')) {
    $env = file_get_contents('.env');
    $lines = explode("\n", $env);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// 数据库配置
$config = [
    'type'            => $_ENV['DB_TYPE'] ?? 'mysql',
    'hostname'        => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'database'        => $_ENV['DB_NAME'] ?? '',
    'username' => $_ENV['DB_USER'] ?? 'root',
    'password' => $_ENV['DB_PASS'] ?? '',
    'hostport'        => $_ENV['DB_PORT'] ?? '3306',
    'charset'         => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
    'prefix'          => $_ENV['DB_PREFIX'] ?? '',
];

try {
    // 创建数据库连接
    $dsn = "{$config['type']}:host={$config['hostname']};port={$config['hostport']};dbname={$config['database']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // 插入测试数据
    $sql = "INSERT INTO `{$config['prefix']}news` 
            (menu_id, title, author, date, views, content, created_at, updated_at, status) 
            VALUES 
            (1, '测试新闻标题', '测试作者', '2023-09-15', 0, '这是测试新闻的内容', NOW(), NOW(), 1)";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute();
    
    if ($result) {
        echo "成功插入测试数据！插入的记录ID为: " . $pdo->lastInsertId() . "\n";
    } else {
        echo "插入数据失败。\n";
    }
} catch (Exception $e) {
    echo "数据库操作出错: " . $e->getMessage() . "\n";
}