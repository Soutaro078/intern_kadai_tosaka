<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>リスト作成 - Todoアプリ</title>
    <script src="https://cdn.jsdelivr.net/npm/knockout@3.5.1/build/output/knockout-latest.js"></script>
    <script src="/assets/js/list_create.js" defer></script>
</head>
<body>
    <h2>リスト作成</h2>
    
    <form data-bind="submit: createList">
        <label>リスト名:</label>
        <input type="text" placeholder="リスト名" data-bind="value: listName">
        <button type="submit">作成</button>
    </form>
</body>
</html>
