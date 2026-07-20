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
            ['id_user' => 2, 'id_operation' => 1, 'valeur' => 5000.00, 'numero_source' => null, 'numero_destination' => '1111112', 'reference' => 'Dépôt initial', 'sens' => 'entree'],
            ['id_user' => 2, 'id_operation' => 1, 'valeur' => 10000.00, 'numero_source' => null, 'numero_destination' => '2222222', 'reference' => 'Dépôt initial', 'sens' => 'entree'],
            ['id_user' => 2, 'id_operation' => 3, 'valeur' => 3000.00, 'numero_source' => '1111112', 'numero_destination' => '2222222', 'reference' => 'Transfert interne', 'sens' => 'sortie'],
            ['id_user' => 2, 'id_operation' => 3, 'valeur' => 3000.00, 'numero_source' => '1111112', 'numero_destination' => '2222222', 'reference' => 'Transfert interne', 'sens' => 'entree'],
            ['id_user' => 2, 'id_operation' => 2, 'valeur' => 5000.00, 'numero_source' => '1111112', 'numero_destination' => null, 'reference' => 'Retrait guichet', 'sens' => 'sortie'],
            ['id_user' => 2, 'id_operation' => 2, 'valeur' => 2500.00, 'numero_source' => '2222222', 'numero_destination' => null, 'reference' => 'Retrait guichet', 'sens' => 'sortie'],
            ['id_user' => 3, 'id_operation' => 1, 'valeur' => 15000.00, 'numero_source' => null, 'numero_destination' => '1234567', 'reference' => 'Dépôt initial', 'sens' => 'entree'],
            ['id_user' => 3, 'id_operation' => 3, 'valeur' => 7000.00, 'numero_source' => '1234567', 'numero_destination' => '9876543', 'reference' => 'Transfert client', 'sens' => 'sortie'],
            ['id_user' => 4, 'id_operation' => 3, 'valeur' => 7000.00, 'numero_source' => '1234567', 'numero_destination' => '9876543', 'reference' => 'Transfert client', 'sens' => 'entree'],
            ['id_user' => 2, 'id_operation' => 1, 'valeur' => 20000.00, 'numero_source' => null, 'numero_destination' => '1111112', 'reference' => 'Dépôt bonus', 'sens' => 'entree'],
        ];

        $this->db->table('historique_operation')->insertBatch($data);
    }
}
