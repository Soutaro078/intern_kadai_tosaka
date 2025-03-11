<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タスク編集 - Todoアプリ</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/knockout@3.5.1/build/output/knockout-latest.js"></script>
    <script src="/assets/js/task_edit.js" defer></script>
</head>
<body>
    <h2>タスク編集</h2>
    
    <form data-bind="submit: updateTask">
        <label>タスク名:</label>
        <input type="text" data-bind="value: taskTitle" required>
        <br>
        <label>期限:</label>
        <input type="datetime-local" data-bind="value: taskLimit">
        <br>
        <label>状態:</label>
        <select data-bind="value: taskStatus">
            <option value="未完了">未完了</option>
            <option value="完了">完了</option>
        </select>
        <br>
        <button type="submit">更新</button>
    </form>
</body>
</html>
