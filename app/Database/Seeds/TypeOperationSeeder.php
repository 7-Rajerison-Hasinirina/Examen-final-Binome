<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TypeOperationSeeder extends Seeder
{
    public function run()
    {
        $this->db->query('PRAGMA foreign_keys = OFF;');
        $this->db->table('type_operation')->emptyTable();
        $this->db->query('PRAGMA foreign_keys = ON;');

        $data = [
            ['libelle' => 'Depot'],
            ['libelle' => 'Retrait'],
            ['libelle' => 'Transfert'],
        ];

        $this->db->table('type_operation')->insertBatch($data);
    }
}
