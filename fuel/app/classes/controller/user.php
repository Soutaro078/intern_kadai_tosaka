<?php

use Fuel\Core\Controller;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\Format;
use Auth\Auth;

class Controller_User extends Controller
{
    public function action_signup()
    {
        // 🔹 `filter_input()` を使ってデータを取得
        // XSSやSQLインジェクション対策のため、`FILTER_SANITIZE_FULL_SPECIAL_CHARS` や `FILTER_SANITIZE_EMAIL` を指定
        //XSS対策が必要な場合は、`FILTER_SANITIZE_FULL_SPECIAL_CHARS` を指定
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        if (!$username || !$password || !$email) {
            return Response::forge(Format::forge(['error' => 'Invalid input'])->to_json(), 400);
        }

        try {
            $user = Model_User::create_user($username, $password, $email);
            return Response::forge(Format::forge(['message' => 'User created', 'user_id' => $user->id])->to_json(), 200);
        } catch (Exception $e) {
            return Response::forge(Format::forge(['error' => 'User creation failed'])->to_json(), 500);
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

