<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class HistoriqueSeeder extends Seeder
{
    public function run()
    {
        $this->db->query('PRAGMA foreign_keys = OFF;');
        $this->db->table('historique_operation')->emptyTable();
        $this->db->query('PRAGMA foreign_keys = ON;');

        $data = [
            ['id_user' => 2, 'id_operation' => 1, 'valeur' => 5000.00],
            ['id_user' => 3, 'id_operation' => 1, 'valeur' => 10000.00],
            ['id_user' => 4, 'id_operation' => 3, 'valeur' => 3000.00],
            ['id_user' => 2, 'id_operation' => 2, 'valeur' => 5000.00],
            ['id_user' => 3, 'id_operation' => 2, 'valeur' => 2500.00],
            ['id_user' => 5, 'id_operation' => 1, 'valeur' => 15000.00],
            ['id_user' => 4, 'id_operation' => 3, 'valeur' => 7000.00],
            ['id_user' => 2, 'id_operation' => 1, 'valeur' => 20000.00],
        ];

        $this->db->table('historique_operation')->insertBatch($data);
    }
}
