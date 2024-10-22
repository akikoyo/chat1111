<?php
// db_connection.phpをインクルードしてデータベース接続
require 'db_connection.php';

// テーブル作成SQL
$sql = "CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0, -- 0: 一般ユーザー, 1: 管理者
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// クエリの実行
if ($conn->query($sql) === TRUE) {
    echo "テーブルが作成されました。";
} else {
    echo "エラー: " . $conn->error;
}

// 接続を閉じる
$conn->close();
?>
