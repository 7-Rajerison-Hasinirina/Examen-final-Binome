<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CommissionOperateurSeeder extends Seeder
{
    public function run()
    {
        $this->db->query('PRAGMA foreign_keys = OFF;');
        $this->db->table('commission_operateur')->emptyTable();
        $this->db->query('PRAGMA foreign_keys = ON;');

        $data = [
            ['id_operateur' => 1, 'pourcentage' => 5.00],
            ['id_operateur' => 2, 'pourcentage' => 10.00],
            ['id_operateur' => 3, 'pourcentage' => 15.00],
        ];

        $this->db->table('commission_operateur')->insertBatch($data);
    }
}
