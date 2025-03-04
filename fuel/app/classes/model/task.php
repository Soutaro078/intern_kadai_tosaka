<?php

use Fuel\Core\Model_Crud;
use Fuel\Core\DB;

class Model_Task extends Model_Crud
{
    protected static $_table_name = 'tasks';

    public static function create_task($list_id, $title, $description = null, $deadline = null)
    {
        $task = new static();
        $task->set([
            'list_id'    => $list_id,
            'title'      => $title,
            'description' => $description,
            'deadline'   => $deadline,
            'status'     => '未完了',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $task->save();

        return $task;
    }

    public static function find_by_list($list_id)
    {
        return static::query()->where('list_id', $list_id)->get();
    }

    public static function find_by_id($id)
    {
        return static::query()->where('id', $id)->get_one();
    }

    public static function find_all_tasks()
    {
        return static::query()->get();
    }

    public static function update_status($id, $status)
    {
        $valid_statuses = ['未完了', '完了'];
        if (!in_array($status, $valid_statuses, true)) {
            return false;
        }

        $task = static::find_by_id($id);
        if ($task) {
            $task->status = $status;
            $task->save();
            return $task;
        }
        return false;
    }

    public static function delete_task($id)
    {
        $task = static::find_by_id($id);
        if ($task) {
            return $task->delete();
        }
        return false;
    }
}
