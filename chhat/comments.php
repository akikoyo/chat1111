<?php
// データベース接続
include 'db/db_connection.php'; // データベース接続ファイルを読み込む

session_start(); // セッションを開始
$username = $_SESSION['username'] ?? null; // ログインユーザーの名前を取得

// コメント投稿処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = $_POST['comment'];
    
    if ($username) {
        // コメントをデータベースに挿入
        $sql = "INSERT INTO comments (username, comment, created_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $comment);
        
        if ($stmt->execute()) {
            echo "コメントが投稿されました。";
        } else {
            echo "エラー: コメントを投稿できませんでした。";
        }
    } else {
        echo "エラー: ログインしていません。";
    }
}

// コメントの取得
$sql = "SELECT username, comment, created_at FROM comments ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>コメント</title>
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- CSSファイルのリンク -->
</head>
<body>
    <h1>コメントセクション</h1>

    <?php if ($username): ?>
        <form method="POST" action="">
            <textarea name="comment" placeholder="コメントを入力..." required></textarea>
            <button type="submit">投稿</button>
        </form>
    <?php else: ?>
        <p>コメントするには <a href="index.php">ログイン</a> してください。</p>
    <?php endif; ?>

    <h2>過去のコメント</h2>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <strong><?php echo htmlspecialchars($row['username']); ?></strong> 
                <span><?php echo htmlspecialchars($row['created_at']); ?></span>
                <p><?php echo nl2br(htmlspecialchars($row['comment'])); ?></p>
            </li>
        <?php endwhile; ?>
    </ul>

    <a href="logout.php">ログアウト</a> <!-- ログアウト用リンク -->
</body>
</html>
