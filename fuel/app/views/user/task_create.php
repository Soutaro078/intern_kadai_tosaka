<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タスク作成 - Todoアプリ</title>
    <script src="https://cdn.jsdelivr.net/npm/knockout@3.5.1/build/output/knockout-latest.js"></script>
    <script src="/assets/js/task_create.js" defer></script>
</head>
<body>
    <h2>タスク作成</h2>
    
    <form data-bind="submit: createTask">
        <label>リストを選択:</label>
        <select data-bind="options: lists, optionsText: 'title', optionsValue: 'id', value: selectedList"></select>
        <br>
        <label>タスク名:</label>
        <input type="text" placeholder="タスク名を入力" data-bind="value: taskTitle" required>
        <br>
        <label>期限:</label>
        <input type="datetime-local" data-bind="value: taskLimit">
        <br>
        <button type="submit">作成</button>
    </form>
</body>
</html>
