<?php
// データベース接続
include 'db/db_connection.php'; // データベース接続ファイルを読み込む

session_start(); // セッションを開始

// ユーザーがフォームを送信した場合
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['create_account'])) {
        // アカウント作成処理
        $username = $_POST['username'];
        $password = $_POST['password'];

        // ユーザー名とパスワードのバリデーション
        if (!empty($username) && !empty($password)) {
            // パスワードをハッシュ化
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // データベースにユーザーを追加
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $hashedPassword);

            if ($stmt->execute()) {
                echo "アカウントが作成されました。";
            } else {
                echo "エラー: アカウントを作成できませんでした。";
            }
        } else {
            echo "ユーザー名とパスワードを入力してください。";
        }
    } elseif (isset($_POST['login'])) {
        // ログイン処理
        $username = $_POST['username'];
        $password = $_POST['password'];

        // データベースからユーザーを取得
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // パスワードを確認
            if (password_verify($password, $user['password'])) {
                // ログイン成功
                $_SESSION['username'] = $username;
                header("Location: user_list.php"); // ユーザー一覧ページへリダイレクト
                exit;
            } else {
                echo "無効なパスワードです。";
            }
        } else {
            echo "ユーザーが見つかりません。";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>チャットサイト - アカウント作成とログイン</title>
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- CSSファイルのリンク -->
</head>
<body>
    <h1>チャットサイト</h1>

    <h2>アカウント作成</h2>
    <form method="POST" action="">
        <label for="username">ユーザー名:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">パスワード:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit" name="create_account">アカウント作成</button>
    </form>

    <h2>ログイン</h2>
    <form method="POST" action="">
        <label for="username">ユーザー名:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">パスワード:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit" name="login">ログイン</button>
    </form>
</body>
</html>
