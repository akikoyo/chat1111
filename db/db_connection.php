<?php
$host = 'localhost'; // データベースホスト名
$dbname = 'your_database_name'; // データベース名
$username = 'your_username'; // データベースのユーザー名
$password = 'your_password'; // データベースのパスワード

try {
    // PDOによるデータベース接続
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // エラーモードを例外に設定
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 接続エラー時の処理
    echo "接続失敗: " . $e->getMessage();
    exit();
}
?>
