<?php
session_start(); // セッションを開始

// セッション変数をすべて解除
$_SESSION = [];

// セッションを破棄
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"], $params["secure"], $params["httponly"]
    );
}

session_destroy(); // セッションを破棄

// ログインページやホームページにリダイレクト
header("Location: index.php"); // 適宜リダイレクト先を変更してください
exit();
?>
