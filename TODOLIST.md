# Rendu : 12h 00 

## Techniques: 
    - php CI4
    - SQLite
    - html, css, JS
    - Bootstrap

## Theme: Operateur mobile money
## Version 1: 
- COTE OPERATEUR:
==================
- Configuration de Prefixes:
    - 033
    - 037 
    - ....

- Creation de types d'operations
    - depot
    - retrait
    - transfert 
    + bareme de frais 

- Situation gain via les differents
    - ex: retrait 1000 Ar, frais 50 Ar ( modifiable ) -> gain : 50 Ar

- Situation des comptes clients
    - Clients: 
    - Reference
    - Solde actuel 


- COTE CLIENT:
==================
- Login automatique:
    - Numero de telephone -> espace client correspondant

- Operations:
    - Voir le solde actuel ( + historique )
        ex: 20 500 Ar
    - Creation d'un depot
    - Faire de retrait
    - Faire un transfert 
    - voir les historique 
        * retrait
        * depot
        * transfert 
            + filtres 




## base: 'operateur.db'
### Tables:
- operateur
    - id
    - prefixe ( varchar )
    - operateur ( varchar )
ex: 033 , airtel 


- type_operation
    - id
    - libelle
ex: depot, retrait, transfert


- numero:
    - id
    - numero ( unique )
ex: 3377745


- role
    - id
    - libelle
ex: admin, client, operateur


- user:
    - id
    - nom
    - id_role
ex: Rakoto Jean , client
    Jean Paul, admin 
    Yas, operateur 


- numero_user:
    - id
    - id_prefixe
    - id_numero
    - id_user
ex:
Rakoto : 037 11 111 11
Rakoto: 033 11 111 12 
      

- bareme_frais:
    - id
    - intervalle1
    - intervalle2
    - frais
    - id_operateur 
ex: pour yas, retrait ou transfert de l'argent entre 100Ar -> 1000 Ar : frais : 50 Ar


- historique_operation:
    - id
    - date
    - id_user
    - id_operation
    - valeur
ex:  20 juillet 2026, 11:31 , Rakoto Jean , fait un retrait, 5000 Ar, 