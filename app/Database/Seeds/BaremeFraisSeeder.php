<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BaremeFraisSeeder extends Seeder
{
    public function run()
    {
        $this->db->query('PRAGMA foreign_keys = OFF;');
        $this->db->table('bareme_frais')->emptyTable();
        $this->db->query('PRAGMA foreign_keys = ON;');

        $data = [
            ['id_type_operation' => 1, 'intervalle1' => 100, 'intervalle2' => 1000, 'frais' => 50, 'pourcentage' => 10, 'id_operateur' => 1],
            ['id_type_operation' => 2, 'intervalle1' => 1001, 'intervalle2' => 5000, 'frais' => 100, 'pourcentage' => 10,'id_operateur' => 1],
            ['id_type_operation' => 3, 'intervalle1' => 5001, 'intervalle2' => 10000, 'frais' => 200, 'pourcentage' => 10, 'id_operateur' => 1],
            ['id_type_operation' => 1, 'intervalle1' => 10001, 'intervalle2' => 50000, 'frais' => 500, 'pourcentage' => 10, 'id_operateur' => 1],

            ['id_type_operation' => 1, 'intervalle1' => 100, 'intervalle2' => 1000, 'frais' => 50, 'pourcentage' => 10, 'id_operateur' => 2],
            ['id_type_operation' => 2, 'intervalle1' => 1001, 'intervalle2' => 5000, 'frais' => 100, 'pourcentage' => 10, 'id_operateur' => 2],
            ['id_type_operation' => 3, 'intervalle1' => 5001, 'intervalle2' => 10000, 'frais' => 200, 'pourcentage' => 10, 'id_operateur' => 2],
            ['id_type_operation' => 1, 'intervalle1' => 10001, 'intervalle2' => 50000, 'frais' => 500, 'pourcentage' => 10, 'id_operateur' => 2],

            ['id_type_operation' => 1, 'intervalle1' => 100, 'intervalle2' => 1000, 'frais' => 50, 'pourcentage' => 10, 'id_operateur' => 3],
            ['id_type_operation' => 2, 'intervalle1' => 1001, 'intervalle2' => 5000, 'frais' => 100, 'pourcentage' => 10, 'id_operateur' => 3],
            ['id_type_operation' => 3, 'intervalle1' => 5001, 'intervalle2' => 10000, 'frais' => 200, 'pourcentage' => 10, 'id_operateur' => 3],
            ['id_type_operation' => 1, 'intervalle1' => 10001, 'intervalle2' => 50000, 'frais' => 500, 'pourcentage' => 10, 'id_operateur' => 3],

            ['id_type_operation' => 1, 'intervalle1' => 100, 'intervalle2' => 1000, 'frais' => 50, 'pourcentage' => 10, 'id_operateur' => 4],
            ['id_type_operation' => 2, 'intervalle1' => 1001, 'intervalle2' => 5000, 'frais' => 100, 'pourcentage' => 10, 'id_operateur' => 4],
            ['id_type_operation' => 3, 'intervalle1' => 5001, 'intervalle2' => 10000, 'frais' => 200, 'pourcentage' => 10, 'id_operateur' => 4],
            ['id_type_operation' => 1, 'intervalle1' => 10001, 'intervalle2' => 50000, 'frais' => 500, 'pourcentage' => 10, 'id_operateur' => 4],
        ];

        $this->db->table('bareme_frais')->insertBatch($data);
    }
}
