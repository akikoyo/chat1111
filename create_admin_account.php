<?php
// db_connection.phpをインクルードしてデータベース接続
require 'db_connection.php';

// 管理者アカウントの情報
$username = 'admin'; // 管理者ユーザー名
$password = password_hash('admin_password', PASSWORD_DEFAULT); // パスワードをハッシュ化
$is_admin = 1; // 管理者フラグ

// 管理者アカウントの作成SQL
$sql = "INSERT INTO users (username, password, is_admin) VALUES ('$username', '$password', $is_admin)";

// クエリの実行
if ($conn->query($sql) === TRUE) {
    echo "管理者アカウントが作成されました。";
} else {
    echo "エラー: " . $conn->error;
}

// 接続を閉じる
$conn->close();
?>
