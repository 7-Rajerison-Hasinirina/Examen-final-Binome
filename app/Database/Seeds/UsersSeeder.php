<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $this->db->query('PRAGMA foreign_keys = OFF;');
        $this->db->table('users')->emptyTable();
        $this->db->query('PRAGMA foreign_keys = ON;');

        $data = [
            ['nom' => 'Jean Paul',   'id_role' => 1],
            ['nom' => 'Rakoto Jean', 'id_role' => 2],
            ['nom' => 'Rabe Marie',  'id_role' => 2],
            ['nom' => 'Rasoa Aina',  'id_role' => 2],
            ['nom' => 'Yas',         'id_role' => 3],
            ['nom' => 'airtel',      'id_role' => 3],
            ['nom' => 'orange',      'id_role' => 3],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
