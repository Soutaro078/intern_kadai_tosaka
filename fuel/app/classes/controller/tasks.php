<?php

use Fuel\Core\Controller;
use Fuel\Core\Response;
use Fuel\Core\Input;
use Fuel\Core\Format;
use Auth\Auth;
use Fuel\Core\Log;

class Controller_Tasks extends Controller
{

    // beforeメソッドを追加（認証されているユーザーのみが操作可能にする）
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
        $tasks = Model_Task::find_all();
        return Response::forge(Format::forge($tasks)->to_json(), 200);
    }

    // public function action_index()
    // {
    //     // `list_id` を取得し、確実に整数に変換
    //     $list_id = Input::get('list_id', 1);
    
    //     // 🔹 `list_id` が `null`、空文字、もしくは `0` の場合はデフォルト値 `1` にする
    //     if (empty($list_id) || $list_id === '0') {
    //         Log::debug("⚠️ list_id が空または無効な値のため、1 に変更");
    //         $list_id = 1;
    //     }
    
    //     // 🔹 `list_id` が数値でない場合はエラーを返す
    //     if (!ctype_digit((string)$list_id)) { // 🔥 `is_numeric()` より厳密なチェック
    //         Log::error("❌ list_id に無効な値が渡されました: " . print_r($list_id, true));
    //         return Response::forge(json_encode(['error' => 'Invalid list_id']), 400);
    //     }
    
    //     // 🔹 `list_id` を整数に変換
    //     $list_id = intval($list_id);
    
    //     Log::debug("🟢 確定した list_id: " . $list_id);
    
    //     // 🔹 指定されたリストのタスクのみ取得
    //     $tasks = Model_Task::find('all', [
    //         'where' => [['list_id', '=', $list_id]]
    //     ]);
    
    //     // 🔹 デバッグ用ログ出力
    //     Log::debug("🟢 取得したタスク: " . print_r($tasks, true));
    
    //     return Response::forge(Format::forge($tasks)->to_json(), 200);
    // }
    
    

    // public function action_index()
    // {
    //     try {
    //         // クエリパラメータを取得し、整数に変換
    //         $list_id = isset($_GET['list_id']) && is_numeric($_GET['list_id']) ? intval($_GET['list_id']) : null;
    //         Log::debug('list_id = ' . print_r($list_id, true));
    
    //         if ($list_id !== null) {
    //             // 🔹 指定されたリストのタスクのみ取得
    //             $tasks = Model_Task::find('all', [
    //                 'where' => [['list_id', '=', $list_id]]
    //             ]);
    //         } else {
    //             // 🔹 `list_id` が指定されていない場合は、全タスクを取得
    //             $tasks = Model_Task::find('all');
    //         }
    
    //         // 🔹 データ構造をログに出力（デバッグ用）
    //         Log::debug('取得したタスクデータ: ' . print_r($tasks, true));
    
    //         // 🔹 データが空の場合、エラーメッセージを返す
    //         if (!$tasks) {
    //             return Response::forge(json_encode(['error' => 'No tasks found']), 404);
    //         }
    
    //         return Response::forge(Format::forge($tasks)->to_json(), 200);
    
    //     } catch (Exception $e) {
    //         Log::error('Task取得中のエラー: ' . $e->getMessage());
    //         return Response::forge(json_encode(['error' => 'Internal Server Error']), 500);
    //     }
    // }
    
    
    
    

    public function action_view($id = null)
    {
        if (!$id) {
            return Response::forge(json_encode(['error' => 'Invalid ID']), 400);
        }

        $task = Model_Task::find_by_id($id);

        if (!$task) {
            return Response::forge(json_encode(['error' => 'Task not found']), 404);
        }

        return Response::forge(Format::forge($task)->to_json(), 200);
    }

    // 🔹 タスク作成API（POSTリクエスト）
    public function action_create()
    {
        // クライアントからのリクエストを受け取る
        $list_id = Input::post('list_id');
        $title = Input::post('title');
        $limit = Input::post('limit');

        // 必須パラメータのバリデーション
        if (empty($list_id) || empty($title)) {
            return Response::forge(json_encode(['error' => 'Missing required fields']), 400);
        }

        // ログインユーザーの取得
        // $user_id = Auth::instance()->get_user_id()[1]; // FuelPHPのAuthは [driver_id, user_id] の形式

        // タスクを作成
        $task = new Model_Task();
        $task->list_id = $list_id;
        // $task->user_id = $user_id;
        $task->title = $title;
        $task->deadline = $limit; // `deadline` に変更
        $task->created_at = date('Y-m-d H:i:s');

        // 保存処理
        if ($task->save()) {
            return Response::forge(json_encode([
                'message' => 'Task created successfully',
                'task_id' => $task->id
            ]), 201);
        } else {
            return Response::forge(json_encode(['error' => 'Failed to create task']), 500);
        }
    }

    public function action_update($id = null)
    {
        if (!$id) {
            return Response::forge(json_encode(['error' => 'Invalid Task ID']), 400);
        }

        // 認証ユーザーの確認
        if (!Auth::instance()->check()) {
            return Response::forge(json_encode(['error' => 'Unauthorized']), 401);
        }

        // タスクを取得
        $task = Model_Task::find_by_id($id);
        if (!$task) {
            return Response::forge(json_encode(['error' => 'Task not found']), 404);
        }

        // リクエストデータを取得
        $title = Input::put('title', $task->title);
        $deadline = Input::put('deadline', $task->deadline);
        $status = Input::put('status', $task->status);

        // 日付フォーマットの修正
        if ($deadline) {
            $deadline = date('Y-m-d H:i:s', strtotime($deadline)); // 🔥 `YYYY-MM-DD HH:MM:SS` に変換
        }

        // タスクの更新
        $task->title = $title;
        $task->deadline = $deadline;
        $task->status = $status;

        // 保存処理
        if ($task->save()) {
            return Response::forge(json_encode(['message' => 'Task updated successfully']), 200);
        } else {
            return Response::forge(json_encode(['error' => 'Failed to update task']), 500);
        }
    }

    public function action_delete($id = null)
    {
        // IDがない場合はエラーレスポンスを返す
        if (!$id) {
            return Response::forge(json_encode(['error' => 'Invalid Task ID']), 400);
        }
    
        // 認証チェック
        if (!Auth::instance()->check()) {
            return Response::forge(json_encode(['error' => 'Unauthorized']), 401);
        }
    
        // タスクを取得
        $task = Model_Task::find_by_id($id);
        if (!$task) {
            return Response::forge(json_encode(['error' => 'Task not found']), 404);
        }
    
        // タスク削除
        if ($task->delete()) {
            return Response::forge(json_encode(['message' => 'Task deleted successfully']), 200);
        } else {
            return Response::forge(json_encode(['error' => 'Failed to delete task']), 500);
        }
    }
    

}


