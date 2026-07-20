<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBaremeFrais extends Migration
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
            'intervalle1' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'intervalle2' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'frais' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'id_operateur' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'unsigned'   => true,
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

        $this->forge->createTable('bareme_frais');
    }

    public function down()
    {
        $this->forge->dropTable('bareme_frais');
    }
}