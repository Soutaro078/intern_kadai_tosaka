<?php

use Fuel\Core\Model_Crud;

class Model_Session extends Model_Crud
{
    protected static $_table_name = 'sessions';  // テーブル名を指定

    /**
     * セッションを作成
     */
    public static function create_session($user_id, $expires_at)
    {
        $session = new static();
        $session->user_id = $user_id;
        $session->expires_at = $expires_at;
        $session->save();

        return $session;
    }

    /**
     * セッションを取得
     */
    public static function find_by_id($id)
    {
        return static::query()->where('id', $id)->get_one();
    }

    /**
     * セッションを削除
     */
    public static function delete_session($id)
    {
        $session = static::find_by_id($id);
        if ($session) {
            return $session->delete();
        }
        return false;
    }
}
