<?php

use Fuel\Core\Model_Crud;
use Fuel\Core\DB;

class Model_List extends Model_Crud
{
    protected static $_table_name = 'lists';

    public static function create_list($user_id, $title)
    {
        $list = new static();
        $list->set([
            'user_id'    => $user_id,
            'title'      => $title,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $list->save();

        return $list;
    }

    public static function find_by_user($user_id)
    {
        return static::query()->where('user_id', $user_id)->get();
    }

    public static function find_by_id($id)
    {
        return static::query()->where('id', $id)->get_one();
    }

    public static function get_all_lists()
    {
        return static::query()->get();
    }

    public static function delete_list($id)
    {
        $list = static::find_by_id($id);
        if ($list) {
            return $list->delete();
        }
        return false;
    }
}

