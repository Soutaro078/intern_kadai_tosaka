<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todoアプリ - ホーム</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/knockout@3.5.1/build/output/knockout-latest.js"></script>
    <script src="/assets/js/home.js" defer></script>
</head>
<body>
    <h2>Todoアプリ - ホーム</h2>

    <!-- サインアウトボタン -->
    <button data-bind="click: signout">サインアウト</button>

    <div class="list-header">
        <h2>リスト一覧</h2>
        <a href="/list_create">リスト新規作成</a>
    </div>

    <ul data-bind="foreach: lists">
        <li>
            <a href="#" data-bind="text: title, click: $parent.selectList"></a>
        </li>
    </ul>

    <div class="tasks">
        <h2>タスク一覧</h2>
        <a href="/task_create">タスク新規作成</a>
        <ul data-bind="foreach: tasks">
            <li>
                <span data-bind="text: title"></span>
                <span data-bind="text: status"></span>
                <a href="#" data-bind="attr: { href: '/task_edit?id=' + id }">編集</a>
                <button data-bind="click: $parent.deleteTask">削除</button>
            </li>
        </ul>
    </div>
</body>
</html>
