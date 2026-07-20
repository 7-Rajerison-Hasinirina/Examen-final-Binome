<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRole extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'libelle' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('libelle');

        $this->forge->createTable('role');
    }

    public function down()
    {
        $this->forge->dropTable('role');
    }
}