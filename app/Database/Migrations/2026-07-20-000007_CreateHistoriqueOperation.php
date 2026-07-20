<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateHistoriqueOperation extends Migration
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
            'date' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'id_user' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_operation' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'valeur' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('id_user');
        $this->forge->addKey('id_operation');

        $this->forge->addForeignKey(
            'id_user',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'id_operation',
            'type_operation',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('historique_operation');
    }

    public function down()
    {
        $this->forge->dropTable('historique_operation');
    }
}