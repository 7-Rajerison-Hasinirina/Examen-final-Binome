<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OperateurSeeder extends Seeder
{
    public function run()
    {
        $this->db->query('PRAGMA foreign_keys = OFF;');
        $this->db->table('operateur')->emptyTable();
        $this->db->query('PRAGMA foreign_keys = ON;');

        $data = [
            ['prefixe' => '032', 'operateur' => 'Orange Money'],
            ['prefixe' => '033', 'operateur' => 'Airtel Money'],
            ['prefixe' => '034', 'operateur' => 'MVola'],
            ['prefixe' => '038', 'operateur' => 'MVola'],
        ];

        $this->db->table('operateur')->insertBatch($data);
    }
}
