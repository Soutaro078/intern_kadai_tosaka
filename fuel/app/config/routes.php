<?php
return array(
	'_root_'  => 'welcome/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route

    // 🔹 認証ページ (HTML を表示するルート)
    'signup' => 'user/signup_page',  // signup.html を表示
    'signin' => 'user/signin_page',  // signin.html を表示

    // 🔹 `home` ページのルート
    'home' => 'user/home',

    // 🔹 他のページ
    'list_create' => 'user/list_create',
    'task_create' => 'user/task_create',
    'task_edit' => 'user/task_edit',

	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
    'api/signup' => 'user/signup', // ユーザー登録API
    'api/signin' => 'user/signin', // サインインAPI
    'api/signout' => 'user/signout', // サインアウトAPI
    'api/check_session' => 'user/check_session', // 🔹 セッション確認APIを追加
    'api/lists' => 'lists/index',  // 🔹 GET: リスト一覧取得
    'api/lists/create' => 'lists/create',  // 🔹 POST: 新しいリストを作成
    'api/lists/(:num)' => 'lists/view/$1', // 指定されたIDのリスト取得API
    'api/tasks' => 'tasks/index', // タスク取得API
    'api/tasks/create' => 'tasks/create',  // 🔹 POST: 新しいタスクを作成
    'api/tasks/update/(:num)' => 'tasks/update/$1',  // 🔹 PUT: タスクの更新
    'api/tasks/delete/(:num)' => 'tasks/delete/$1',  // 🔹 DELETE: タスク削除
    'api/tasks/(:num)' => 'tasks/view/$1', // 指定されたIDのタスク取得API

    'api/lists/delete/(:num)' => 'lists/delete/$1',  // 🔥 DELETE: リスト削除
    

);
