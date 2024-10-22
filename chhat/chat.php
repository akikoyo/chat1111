<?php
session_start(); // セッションの開始

// データベース接続
include 'db/db_connection.php'; // データベース接続ファイルを読み込む

// メッセージ送信処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']); // メッセージの取得とトリム
    $username = $_SESSION['username']; // セッションからユーザー名を取得

    if (!empty($message)) { // メッセージが空でない場合
        // メッセージをデータベースに保存
        $stmt = $conn->prepare("INSERT INTO messages (username, message, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $username, $message);
        $stmt->execute();
    }
}

// メッセージの取得
$sql = "SELECT username, message, created_at FROM messages ORDER BY created_at DESC";
$result = $conn->query($sql);

// メッセージの表示
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>チャット</title>
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- CSSファイルのリンク -->
    <script src="assets/js/chat.js" defer></script> <!-- JavaScriptファイルのリンク -->
</head>
<body>
    <h1>チャット</h1>
    <div id="messagesContainer">
        <?php
        // メッセージを表示
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='message'>";
                echo "<strong>" . htmlspecialchars($row['username']) . ":</strong> ";
                echo htmlspecialchars($row['message']) . " ";
                echo "<span class='timestamp'>" . htmlspecialchars($row['created_at']) . "</span>";
                echo "</div>";
            }
        } else {
            echo "<div>まだメッセージはありません。</div>";
        }
        ?>
    </div>
    <form method="POST" action="">
        <input type="text" id="messageInput" name="message" placeholder="メッセージを入力" required>
        <button type="submit" id="sendButton">送信</button>
    </form>
</body>
</html>
