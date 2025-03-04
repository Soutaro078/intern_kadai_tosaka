<?php

use Fuel\Core\Model_Crud;
use Fuel\Core\DB;
use Auth\Auth;

class Model_User extends Model_Crud
{
    protected static $_table_name = 'users';

    public static function create_user($username, $password, $email)
    {
        $hashed_password = Auth::hash_password($password);

        $user = new static();
        $user->set([
            'username'   => $username,
            'password'   => $hashed_password,
            'email'      => $email,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $user->save();

        return $user;
    }

    public static function find_by_id($id)
    {
        return static::query()->where('id', $id)->get_one();
    }

    public static function delete_user($id)
    {
        $user = static::find_by_id($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}

