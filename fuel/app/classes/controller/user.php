<?php

use Fuel\Core\Controller;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\Format;
use Auth\Auth;
use Fuel\Core\View;

class Controller_User extends Controller
{
    // home画面を表示する機能
    public function action_home()
    {
        return Response::forge(View::forge('user/home'));
    }

    // list_create画面を表示する機能
    public function action_list_create()
    {
        return Response::forge(View::forge('user/list_create'));
    }

    // task_create画面を表示する機能
    public function action_task_create()
    {
        return Response::forge(View::forge('user/task_create'));
    }

    // task_edit画面を表示する機能
    public function action_task_edit()
    {
        return Response::forge(View::forge('user/task_edit'));
    }

    // signup.html を表示するアクション
    public function action_signup_page()
    {
        return Response::forge(View::forge('user/signup'));
    }

    // signin.html を表示するアクション
    public function action_signin_page()
    {
        return Response::forge(View::forge('user/signin'));
    }

    // public function action_signup()
    // {
    //     // `filter_input()` を使ってデータを取得
    //     // XSSやSQLインジェクション対策のため、`FILTER_SANITIZE_FULL_SPECIAL_CHARS` や `FILTER_SANITIZE_EMAIL` を指定
    //     //XSS対策が必要な場合は、`FILTER_SANITIZE_FULL_SPECIAL_CHARS` を指定
    //     // $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    //     // $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    //     // $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    //     $username = Input::json('username');
    //     $password = Input::json('password');
    //     $email = Input::json('email');

    //     if (!$username || !$password || !$email) {
    //         return Response::forge(Format::forge(['error' => 'Invalid input'])->to_json(), 400);
    //     }

    //     try {
    //         $user = Model_User::create_user($username, $password, $email);
    //         return Response::forge(Format::forge(['message' => 'User created', 'user_id' => $user->id])->to_json(), 200);
    //     } catch (Exception $e) {
    //         return Response::forge(Format::forge(['error' => 'User creation failed'])->to_json(), 500);
    //     }
    // }

    public function action_signup()
    {
        try {
            // JSON で受け取る
            $json = file_get_contents('php://input');
            $data = json_decode($json, true); // JSON を配列に変換
    
            // 入力データのバリデーション
            if (!isset($data['username']) || !isset($data['password']) || !isset($data['email'])) {
                return Response::forge(Format::forge(['error' => 'Invalid input'])->to_json(), 400);
            }
    
            $username = filter_var($data['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = filter_var($data['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
    
            // FuelPHP の Auth パッケージを使用してユーザー作成
            if (!class_exists('\Auth\Auth')) {
                return Response::forge(Format::forge(['error' => 'Auth class not found'])->to_json(), 500);
            }
    
            try {
                $user_id = Auth::create_user($username, $password, $email);
                if (!$user_id) {
                    return Response::forge(Format::forge(['error' => 'User creation failed'])->to_json(), 500);
                }
                return Response::forge(Format::forge(['message' => 'User created', 'user_id' => $user_id])->to_json(), 200);
            } catch (\Auth\AuthException $e) {
                return Response::forge(Format::forge(['error' => 'Auth Error: ' . $e->getMessage()])->to_json(), 500);
            }
        } catch (Exception $e) {
            return Response::forge(Format::forge(['error' => 'Internal Server Error: ' . $e->getMessage()])->to_json(), 500);
        }
    }
    

    public function action_signin()
    {
        // 🔹 `filter_input()` を使ってデータを取得
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (Auth::instance()->login($username, $password)) {
            return Response::forge(Format::forge(['message' => 'Login successful'])->to_json(), 200);
        } else {
            return Response::forge(Format::forge(['error' => 'Invalid credentials'])->to_json(), 401);
        }
    }

    public function action_signout()
    {
        Auth::instance()->logout();
        return Response::forge(Format::forge(['message' => 'Logged out successfully'])->to_json(), 200);
    }
}





// <?php

// use Fuel\Core\Controller;
// use Fuel\Core\Input;
// use Fuel\Core\Response;
// use Fuel\Core\Format;
// use Auth\Auth;

// class Controller_User extends Controller
// {
//     public function action_signup()
//     {
//         $username = Input::post('username');
//         $password = Input::post('password');
//         $email = Input::post('email');

//         if (!$username || !$password || !$email) {
//             return Response::forge(Format::forge(['error' => 'Invalid input'])->to_json(), 400);
//         }

//         try {
//             $user = Model_User::create_user($username, $password, $email);
//             return Response::forge(Format::forge(['message' => 'User created', 'user_id' => $user->id])->to_json(), 200);
//         } catch (Exception $e) {
//             return Response::forge(Format::forge(['error' => 'User creation failed'])->to_json(), 500);
//         }
//     }

//     public function action_signin()
//     {
//         $username = Input::post('username');
//         $password = Input::post('password');

//         if (Auth::instance()->login($username, $password)) {
//             return Response::forge(Format::forge(['message' => 'Login successful'])->to_json(), 200);
//         } else {
//             return Response::forge(Format::forge(['error' => 'Invalid credentials'])->to_json(), 401);
//         }
//     }

//     public function action_signout()
//     {
//         Auth::instance()->logout();
//         return Response::forge(Format::forge(['message' => 'Logged out successfully'])->to_json(), 200);
//     }
// }

