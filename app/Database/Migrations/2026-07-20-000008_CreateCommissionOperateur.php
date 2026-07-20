<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommissionOperateur extends Migration
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
            'id_operateur' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'pourcentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('id_operateur');

        $this->forge->addForeignKey(
            'id_operateur',
            'operateur',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('commission_operateur');
    }

    public function down()
    {
        $this->forge->dropTable('commission_operateur');
    }
}
