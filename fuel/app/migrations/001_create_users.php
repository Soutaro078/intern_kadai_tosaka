<?php

namespace Fuel\Migrations;

use Fuel\Core\DBUtil; // 追加
use Fuel\Core\DB; // 追加

class Create_users
{
    public function up()
    {
        DBUtil::create_table('users', [
            'id'        => ['type' => 'int', 'auto_increment' => true, 'constraint' => 11, 'unsigned' => true],
            'username'  => ['type' => 'varchar', 'constraint' => 50],
            'password'  => ['type' => 'varchar', 'constraint' => 255],
            'email'     => ['type' => 'varchar', 'constraint' => 100],
            'created_at'=> ['type' => 'timestamp', 'default' => DB::expr('CURRENT_TIMESTAMP')],
        ], ['id']);
    }

    public function down()
    {
        DBUtil::drop_table('users');
    }
}
