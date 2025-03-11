<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>サインアップ - Todoアプリ</title>
    <!-- <link rel="stylesheet" href="/assets/css/style.css">  CSS 読み込み -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- Knockout.jsの読み込み (auth.js より前に配置) -->
    <script src="https://cdn.jsdelivr.net/npm/knockout@3.5.1/build/output/knockout-latest.js" defer></script>

    <!-- `auth.js` を `defer` で読み込む -->
    <script src="/assets/js/auth.js" defer></script>
</head>
<body>
    <h2>Todoアプリ - サインアップ</h2>
    <form id="signupForm" data-bind="submit: signup">
        <label>ユーザー名:</label>
        <input type="text" id="username" data-bind="value: username" required>
        <br>
        <label>メールアドレス:</label>
        <input type="email" id="email" data-bind="value: email" required>
        <br>
        <label>パスワード:</label>
        <input type="password" id="password" data-bind="value: password" required>
        <br>
        <button type="submit">作成</button>
    </form>
</body>
</html>


