<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NumeroUserSeeder extends Seeder
{
    public function run()
    {
        $this->db->query('PRAGMA foreign_keys = OFF;');
        $this->db->table('numero_user')->emptyTable();
        $this->db->query('PRAGMA foreign_keys = ON;');

        $data = [
            ['id_prefixe' => 2, 'numero' => '1111112', 'id_user' => 2],
            ['id_prefixe' => 3, 'numero' => '2222222', 'id_user' => 2],
            ['id_prefixe' => 4, 'numero' => '1234567', 'id_user' => 3],
            ['id_prefixe' => 1, 'numero' => '9876543', 'id_user' => 4],
            ['id_prefixe' => 4, 'numero' => '7654321', 'id_user' => 5],
            ['id_prefixe' => 2, 'numero' => '9999999', 'id_user' => 1],
        ];

        $this->db->table('numero_user')->insertBatch($data);
    }
}
