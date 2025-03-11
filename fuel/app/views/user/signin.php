<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>サインイン - Todoアプリ</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- Knockout.jsの読み込み -->
    <script src="https://cdn.jsdelivr.net/npm/knockout@3.5.1/build/output/knockout-latest.js"></script>
</head>
<body>
    <h2>Todoアプリ - サインイン</h2>
    <form id="signinForm" data-bind="submit: login">
        <label>ユーザー名:</label>
        <input type="text" id="username" data-bind="value: username" required>
        <br>
        <label>パスワード:</label>
        <input type="password" id="password" data-bind="value: password" required>
        <br>
        <button type="submit">サインイン</button>
    </form>

    <script src="/assets/js/auth.js"></script>  <!-- JS 読み込み -->
</body>
</html>
