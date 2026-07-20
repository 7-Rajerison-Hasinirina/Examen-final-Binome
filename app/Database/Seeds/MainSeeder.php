<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        $this->db->transStart();

        $this->call('OperateurSeeder');
        $this->call('TypeOperationSeeder');
        $this->call('RoleSeeder');
        $this->call('UsersSeeder');
        $this->call('NumeroUserSeeder');
        $this->call('BaremeFraisSeeder');
        $this->call('CommissionOperateurSeeder');
        $this->call('HistoriqueSeeder');

        $this->db->transComplete();
    }
}
