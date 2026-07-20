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




# Fonctionnalites attendues:
1- Creation d'une formulaire:
    - Nom ( champs )
    - liste deorulante de prefix 
        - findAll() dans table operateur.prefixe
    - numero de telephone ( champs )
    - bouton: Valider

----> Valider: 
    - Verifier si le numero ( prefixe et numero) existe deja dans la table numero_user
        - si oui: message d'erreur: "Numero deja existant"
        - si non: 
            - inserer le numero dans la table numero_user
            - inserer l'id du prefixe, l'id du numero, l'id de l'utilisateur et la date de creation automatique


creation de tag:
