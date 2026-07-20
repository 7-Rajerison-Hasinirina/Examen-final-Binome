<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOperateur extends Migration
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
            'prefixe' => [
                'type'       => 'VARCHAR',
                'constraint' => 3,
            ],
            'operateur' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('prefixe');

        $this->forge->createTable('operateur');
    }

    public function down()
    {
        $this->forge->dropTable('operateur');
    }
}