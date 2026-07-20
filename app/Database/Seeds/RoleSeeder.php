<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $this->db->query('PRAGMA foreign_keys = OFF;');
        $this->db->table('role')->emptyTable();
        $this->db->query('PRAGMA foreign_keys = ON;');

        $data = [
            ['libelle' => 'Admin'],
            ['libelle' => 'Client'],
            ['libelle' => 'Operateur'],
        ];

        $this->db->table('role')->insertBatch($data);
    }
}
