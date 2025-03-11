<?php
return array(
  'version' => 
  array(
    'app' => 
    array(
      'default' => 
      array(
        // 0 => '001_create_users',
        // 1 => '002_create_lists',
        // 2 => '003_create_tasks',
        // 3 => '004_create_sessions',
      ),
    ),
    'module' => 
    array(
    ),
    'package' => 
    array(
      'auth' => 
      array(
        // 0 => '001_auth_create_usertables',
        // 1 => '002_auth_create_grouptables',
        // 2 => '003_auth_create_roletables',
        // 3 => '004_auth_create_permissiontables',
        // 4 => '005_auth_create_authdefaults',
        // 5 => '006_auth_add_authactions',
        // 6 => '007_auth_add_permissionsfilter',
        // 7 => '008_auth_create_providers',
        // 8 => '009_auth_create_oauth2tables',
        // 9 => '010_auth_fix_jointables',
        // 10 => '011_auth_group_optional',
      ),
    ),
  ),
  'folder' => 'migrations/',
  'table' => 'migration',
);


// user.phpの勉強について
// /Users/TosakaSota/Documents/intern_kadai_tosaka/fuel/app/migrations/001_create_users.php
// <?php

// namespace Fuel\Migrations;

// use Fuel\Core\DBUtil; // 追加
// use Fuel\Core\DB; // 追加

// class Create_users
// {
//     public function up()
//     {
//         DBUtil::create_table('users', [
//             'id'        => ['type' => 'int', 'auto_increment' => true, 'constraint' => 11, 'unsigned' => true],
//             'username'  => ['type' => 'varchar', 'constraint' => 50],
//             'password'  => ['type' => 'varchar', 'constraint' => 255],
//             'email'     => ['type' => 'varchar', 'constraint' => 100],
//             'created_at'=> ['type' => 'timestamp', 'default' => DB::expr('CURRENT_TIMESTAMP')],
//         ], ['id']);
//     }

//     public function down()
//     {
//         DBUtil::drop_table('users');
//     }
// }