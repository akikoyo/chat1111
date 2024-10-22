document.addEventListener('DOMContentLoaded', function() {
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const messagesContainer = document.getElementById('messagesContainer');

    // メッセージ送信処理
    sendButton.addEventListener('click', function() {
        const message = messageInput.value;

        if (message) {
            // AJAXでメッセージを送信
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'comments.php', true); // メッセージ送信先
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // メッセージ送信後、入力欄をクリア
                    messageInput.value = '';
                    loadMessages(); // メッセージを再読み込み
                }
            };
            xhr.send('message=' + encodeURIComponent(message));
        }
    });

    // メッセージを読み込む処理
    function loadMessages() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'comments.php', true); // メッセージ取得先
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                messagesContainer.innerHTML = xhr.responseText; // 受け取ったメッセージを表示
            }
        };
        xhr.send();
    }

    // 初期メッセージ読み込み
    loadMessages();
    
    // 定期的にメッセージを更新（例: 3秒ごと）
    setInterval(loadMessages, 3000);
});
