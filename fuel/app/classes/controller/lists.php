<?php

use Fuel\Core\Controller;
use Fuel\Core\Response;
use Fuel\Core\Format;
use Auth\Auth;

class Controller_Lists extends Controller
{

    //beforeメソッドを追加（認証されているユーザーのみが操作可能なため）
    public function before()
    {
        parent::before(); // 親クラスの before() を実行

        // 認証されていない場合はエラーレスポンスを返す
        if (!Auth::instance()->check()) {
            return Response::forge(json_encode(['error' => 'Unauthorized']), 401);
        }
    }

    public function action_index()
    {
        $lists = Model_List::find_all();
        return Response::forge(Format::forge($lists)->to_json(), 200);
    }

    public function action_view($id = null)
    {
        if (!$id) {
            return Response::forge(json_encode(['error' => 'Invalid ID']), 400);
        }

        $list = Model_List::find_by_id($id);

        if (!$list) {
            return Response::forge(json_encode(['error' => 'List not found']), 404);
        }

        return Response::forge(Format::forge($list)->to_json(), 200);
    }
}
