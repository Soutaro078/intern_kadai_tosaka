<?php

namespace Fuel\Migrations;

use Fuel\Core\DBUtil;
use Fuel\Core\DB; // 追加

class Create_sessions
{
    public function up()
    {
        DBUtil::create_table('sessions', [
            'id'         => ['type' => 'varchar', 'constraint' => 255, 'null' => false], // ✅ `primary_key` を削除
            'user_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => false],
            'expires_at' => ['type' => 'timestamp', 'null' => false],
        ], ['id'], true, 'InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'); // ✅ 第3引数の ['id'] はそのまま

        // 🔹 FOREIGN KEY を追加
        DB::query("ALTER TABLE sessions ADD CONSTRAINT fk_sessions_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;")->execute();
    }

    public function down()
    {
        DB::query("ALTER TABLE sessions DROP FOREIGN KEY fk_sessions_user_id;")->execute();
        DBUtil::drop_table('sessions');
    }
}



