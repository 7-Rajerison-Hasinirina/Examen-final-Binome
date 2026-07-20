<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateNumeroUser extends Migration
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
            'id_prefixe' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'numero' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'id_user' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'date_creation' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('id_prefixe');
        $this->forge->addKey('id_user');

        $this->forge->addUniqueKey('numero');

        $this->forge->addForeignKey(
            'id_prefixe',
            'operateur',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'id_user',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('numero_user');
    }

    public function down()
    {
        $this->forge->dropTable('numero_user');
    }
}