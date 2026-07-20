# Travaux Binome: 
- ETU003962 : RAJERISON Hasinirina
- ETU004246 : RATOVONIAINA Faneva Mamisoa

## Taches 1: ETU003962
- Conception relationnelle: dans base.sql 
    - operateur
        - id
        - prefixe ( varchar )
        - operateur ( varchar )
    ex: 033 , airtel 


    - type_operation
        - id
        - libelle
    ex: depot, retrait, transfert

    - role
        - id
        - libelle
    ex: admin, client, operateur


    - users:
        - id
        - nom ( varchar )
        - id_role
    ex: Rakoto Jean , client
        Jean Paul, admin 
        Yas, operateur 


    - numero_user:
        - id
        - id_prefixe
        - numero
        - id_user
        - date_creation ( datetime automatique )
    ex:
    Rakoto : 037 11 111 11
    Rakoto: 033 11 111 12 
        

    - bareme_frais:
        - id
        - iD_type_operation
        - intervalle1 ( numerique 10,2)
        - intervalle2 ( numerique 10,2)
        - frais ( numerique 10,2)
        - id_operateur 
    ex: pour yas, transfert de l'argent entre 100Ar -> 1000 Ar : frais : 50 Ar


    - historique_operation:
        - id
        - date
        - id_user
        - id_operation
        - valeur
    ex:  20 juillet 2026, 11:31 , Rakoto Jean , fait un retrait, 5000 Ar, 
====
FINI
====

## Taches 2: ETU003962
    - Creation de la base de donees: operteur.db
    - Migrations
    - Seeder
====
FINI
====

## Taches 3: ETU004246
login -> Affichage page login.php

- page login.php 
    - Nom ( champs )
    - liste deorulante de prefix 
        - findAll() dans table operateur.prefixe
    - numero de telephone ( champs )
    - bouton: Valider

- elements necessaires:
    - liste des prefixes via table operateur

- Entity: 
    - UserModel.php:
    - OperateurModel.php
- Controller:
    - OperateurController.php
        index() -> listePrefixe() -> login.php

- Views:
    - login.php
    - ClientOffice.php
====
FINI
====

## Taches 4: ETU004246
- Validation apres login:  ( auto-login)
    - Verification via login.php:
        1-Si id_prefixe et numero existe deja dans numero_user:
            Si oui, si l'id_user correspond a un client ( id_role = 2 )
                -> redirection vers ClientOffice.php
            Si oui, si l'id_user correspond a un operateur ( id_role = 3 )
                -> redirection vers OperateurOffice.php
        2-Sinon, 
            -> inserer dans users
                - nom ( le nom via formulaire)
                - id_role : 2
            -> inserer dans numero_user
                - id_prefixe ( via formulaire )
                - numero ( via formulaire )
                - id_user ( via users )
                - date_creation ( automatique )
Controller:
    - OperateurController.php:
        - index() -> login.php
        - authentifier() 
            -> ClientOffice.php ou OperateurOffice.php

Views:
    - ClientOffice.php
    - OperateurOffice.php
====
FINI
====
        

## Taches 5: Creation de la page ClientOffice.php ETU03962


- Validation apres login:  ( auto-login)
    - Verification via login.php:
        1-Si id_prefixe et numero existe deja dans numero_user:
            Si oui, si l'id_user correspond a un client ( id_role = 2 )
                -> redirection vers ClientOffice.php
            Si oui, si l'id_user correspond a un operateur ( id_role = 3 )
                -> redirection vers OperateurOffice.php
        2-Sinon, 
            -> inserer dans users
                - nom ( le nom via formulaire)
                - id_role : 2
            -> inserer dans numero_user
                - id_prefixe ( via formulaire )
                - numero ( via formulaire )
                - id_user ( via users )
                - date_creation ( automatique )
Controller:
    - OperateurController.php:
        - index() -> login.php
        - authentifier() 
            -> ClientOffice.php ou OperateurOffice.php

Views:
    - ClientOffice.php
    - OperateurOffice.php


====
FINI
====


## Taches 6: Fonctionnalite Voir solde actuel: ETU004246
- Dans ClientOfficeController.php
- Methodes a creer:
    - getSoldeActuel($id_user)
        - recuperer le solde actuel du client via l'id_user
        - retourner le solde actuel
    - getHistoriqueOperations($id_user)
        - recuperer l'historique des operations du client via l'id_user
        - retourner l'historique des operations




## Taches 7: Fonctionnalite Cote Operateur: ETU003962
- on va ajouter des bloc 
    - COTE OPERATEUR:
        - Configuration de Prefixes:
            - 033
            - 037 
        + ajout , modification / affichge sous forme de tableau 

        - Creation de types d'operations
            - depot
            - retrait
            - transfert 
            + bareme de frais 
        * ajout , modification / affichge sous forme de tableau + filtre pour chaque type d'operation et operateur

        - Situation gain via les differents
            - ex: retrait 1000 Ar, frais 50 Ar ( modifiable ) -> gain : 50 Ar
        ( on affiche rien , on attend )

        - Situation des comptes clients
            - Clients: 
            - Reference
            - Solde actuel

on touche uniquement les fichiers:
OperateurOfficeController.php
OperateurModel.php 
OperateurOffice.php ( le views )



