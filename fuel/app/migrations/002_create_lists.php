<?php

namespace Fuel\Migrations;

use Fuel\Core\DBUtil; // 追加
use Fuel\Core\DB; // 追加

class Create_lists
{
    public function up()
    {
        DBUtil::create_table('lists', [
            'id'        => ['type' => 'int', 'auto_increment' => true, 'constraint' => 11, 'unsigned' => true],
            'user_id'   => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => false],
            'title'     => ['type' => 'varchar', 'constraint' => 255, 'null' => false],
            'created_at'=> ['type' => 'timestamp', 'default' => DB::expr('CURRENT_TIMESTAMP')],
        ], ['id'], true, 'InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'); // ✅ 修正

        // 🔹 FOREIGN KEY を追加
        DB::query("ALTER TABLE lists ADD CONSTRAINT fk_lists_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;")->execute();
    }

    public function down()
    {
        DB::query("ALTER TABLE lists DROP FOREIGN KEY fk_lists_user_id;")->execute();
        DBUtil::drop_table('lists');
    }
}


