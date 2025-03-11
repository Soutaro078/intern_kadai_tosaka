<?php

use Fuel\Core\Controller;
use Fuel\Core\Response;
use Fuel\Core\Format;
use Auth\Auth;
use Fuel\Core\Log;
use Fuel\Core\Input;
use Fuel\Core\DB;

class Controller_Lists extends Controller
{

    //beforeメソッドを追加（認証されているユーザーのみが操作可能なため）
    public function before()
    {
        parent::before();
    
        // 🔥 FuelPHP のセッション認証を使用
        if (!Auth::instance()->check()) {
            return Response::forge(json_encode(['error' => 'Unauthorized']), 401);
        }
    
        // 🔹 現在ログインしているユーザーを取得
        $user = Auth::instance()->get_screen_name();
        Log::info("🟢 ログインユーザー: " . $user);
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


    public function action_create()
    {
        Log::info('🔥 リスト作成リクエスト受信');
    
        $title = Input::post('title');
    
        if (!$title) {
            Log::error('❌ タイトルが空です');
            return Response::forge(json_encode(['error' => 'タイトルが空です']), 400);
        }
    
        // 🔥 ログインユーザーの ID を取得
        list(, $user_id) = Auth::instance()->get_user_id();
    
        if (!$user_id) {
            Log::error('❌ ユーザーが未認証');
            return Response::forge(json_encode(['error' => 'Unauthorized']), 401);
        }
    
        // 🔥 データベースに保存
        $list = new Model_List();
        $list->title = $title;
        $list->user_id = $user_id;
        $list->save();
    
        Log::info('💾 リストを保存しました: ' . print_r($list, true));
    
        return Response::forge(json_encode(['message' => 'リスト作成成功']), 201);
    }

    //削除作成
    public function action_delete($id = null)
    {
        // IDのバリデーション
        if (!$id || !is_numeric($id)) {
            Log::error("❌ 無効なリストID: " . print_r($id, true));
            return Response::forge(json_encode(['error' => 'Invalid List ID']), 400);
        }
    
        // リストの取得
        $list = Model_List::find_by_id($id);
        if (!$list) {
            Log::error("❌ 指定されたリストが存在しません: " . $id);
            return Response::forge(json_encode(['error' => 'List not found']), 404);
        }
    
        try {
            // 🔥 紐づくタスクを削除（SQL 実行）
            DB::query("DELETE FROM tasks WHERE list_id = :id")->parameters(['id' => $id])->execute();
            Log::info("🟢 リストID: " . $id . " に紐づくタスクを削除");
    
            // 🔥 リストを削除
            if (Model_List::delete_list($id)) {
                Log::info("🟢 リストID: " . $id . " を削除しました");
                return Response::forge(json_encode(['message' => 'List deleted successfully']), 200);
            } else {
                Log::error("❌ リスト削除に失敗しました: " . $id);
                return Response::forge(json_encode(['error' => 'Failed to delete list']), 500);
            }
        } catch (Exception $e) {
            Log::error("❌ リスト削除エラー: " . $e->getMessage());
            return Response::forge(json_encode(['error' => 'Internal Server Error']), 500);
        }
    }
    
    
    
    
    
    
    

}
