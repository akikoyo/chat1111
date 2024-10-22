<?php
session_start(); // セッションを開始

// 認証チェック
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php"); // ログインページにリダイレクト
    exit();
}

// データベース接続
include 'db/db_connection.php'; // データベース接続ファイルを読み込む

// ユーザーの一覧を取得
$sql = "SELECT username, password, ipv4, banned_until FROM users";
$result = $conn->query($sql);

// アカウント削除処理
if (isset($_POST['delete_user'])) {
    $username_to_delete = $_POST['username_to_delete'];

    // アカウントを削除
    $sql = "DELETE FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username_to_delete);
    if ($stmt->execute()) {
        echo "ユーザー {$username_to_delete} が削除されました。";
    } else {
        echo "エラー: ユーザーを削除できませんでした。";
    }
}

// パスワード変更処理
if (isset($_POST['change_password'])) {
    $username_to_change = $_POST['username_to_change'];
    $new_password = $_POST['new_password'];

    // パスワードを変更
    $sql = "UPDATE users SET password = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_password, $username_to_change);
    if ($stmt->execute()) {
        echo "ユーザー {$username_to_change} のパスワードが変更されました。";
    } else {
        echo "エラー: パスワードを変更できませんでした。";
    }
}

// IPのアクセス禁止処理
if (isset($_POST['ban_ip'])) {
    $username_to_ban = $_POST['username_to_ban'];
    $ban_duration = $_POST['ban_duration']; // 禁止期間（例: 1日, 1週間）

    // 永久BANのチェック
    if ($ban_duration === 'permanent') {
        // 永久BANの処理
        $sql = "UPDATE users SET banned_until = NULL WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username_to_ban);
        if ($stmt->execute()) {
            echo "ユーザー {$username_to_ban} が永久BANされました。";
        } else {
            echo "エラー: 永久BANできませんでした。";
        }
    } else {
        // 一時BANの処理
        $sql = "UPDATE users SET banned_until = NOW() + INTERVAL ? DAY WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $ban_duration, $username_to_ban);
        if ($stmt->execute()) {
            echo "ユーザー {$username_to_ban} のIPがアクセス禁止になりました。";
        } else {
            echo "エラー: IPを禁止できませんでした。";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー一覧</title>
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- CSSファイルのリンク -->
</head>
<body>
    <h1>ユーザー一覧</h1>

    <table>
        <tr>
            <th>ユーザー名</th>
            <th>パスワード</th>
            <th>IPv4アドレス</th>
            <th>バン状態</th>
            <th>アクション</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['password']); ?></td>
                <td><?php echo htmlspecialchars($row['ipv4']); ?></td>
                <td><?php echo $row['banned_until'] ? '禁止中' : '利用可能'; ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="username_to_delete" value="<?php echo htmlspecialchars($row['username']); ?>">
                        <button type="submit" name="delete_user">削除</button>
                    </form>
                    <form method="POST" action="">
                        <input type="hidden" name="username_to_change" value="<?php echo htmlspecialchars($row['username']); ?>">
                        <input type="text" name="new_password" placeholder="新しいパスワード" required>
                        <button type="submit" name="change_password">パスワード変更</button>
                    </form>
                    <form method="POST" action="">
                        <input type="hidden" name="username_to_ban" value="<?php echo htmlspecialchars($row['username']); ?>">
                        <select name="ban_duration">
                            <option value="1">1日</option>
                            <option value="7">1週間</option>
                            <option value="30">1ヶ月</option>
                            <option value="permanent">永久BAN</option>
                        </select>
                        <button type="submit" name="ban_ip">IP禁止</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="logout.php">ログアウト</a> <!-- ログアウト用リンク -->
</body>
</html>
