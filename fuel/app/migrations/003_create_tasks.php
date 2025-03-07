<?php

namespace Fuel\Migrations;

use Fuel\Core\DBUtil; // 追加
use Fuel\Core\DB; // 追加

class Create_tasks
{
    public function up()
    {
        DBUtil::create_table('tasks', [
            'id'         => ['type' => 'int', 'auto_increment' => true, 'constraint' => 11, 'unsigned' => true],
            'list_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => false],
            'title'      => ['type' => 'varchar', 'constraint' => 255, 'null' => false],
            'description'=> ['type' => 'text', 'null' => true],
            'deadline'   => ['type' => 'date', 'null' => true],
            'status'     => ['type' => 'enum', 'constraint' => ["未完了", "完了"], 'default' => '未完了'],
            'created_at' => ['type' => 'timestamp', 'default' => DB::expr('CURRENT_TIMESTAMP')],
        ], ['id'], true, 'InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'); 

        // 🔹 FOREIGN KEY を追加
        DB::query("ALTER TABLE tasks ADD CONSTRAINT fk_tasks_list_id FOREIGN KEY (list_id) REFERENCES lists(id) ON DELETE CASCADE;")->execute();
    }

    public function down()
    {
        DB::query("ALTER TABLE tasks DROP FOREIGN KEY fk_tasks_list_id;")->execute();
        DBUtil::drop_table('tasks');
    }
}
