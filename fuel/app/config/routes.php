<?php
return array(
	'_root_'  => 'welcome/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route
	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
    'api/signup' => 'user/signup', // ユーザー登録API
    'api/signin' => 'user/signin', // サインインAPI
    'api/signout' => 'user/signout', // サインアウトAPI
    'api/lists' => 'lists/index', // リスト取得API
    'api/lists/(:num)' => 'lists/view/$1', // 指定されたIDのリスト取得API
    'api/tasks' => 'tasks/index', // タスク取得API
    'api/tasks/(:num)' => 'tasks/view/$1', // 指定されたIDのタスク取得API
);
