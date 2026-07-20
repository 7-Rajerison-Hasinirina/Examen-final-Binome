<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null){
        $session = session();
        $user = $session->get('user');
        $allowedRoles = $arguments ?? [2];
        if (!is_array($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }
        $allowedRoles = array_map('intval', $allowedRoles);

        if (!$user || !in_array((int) $user['id_statut'], $allowedRoles, true)) {
            return redirect()->back()->with('error', 'Accès refusé : droits insuffisants');
        }
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){
        
    }
}
