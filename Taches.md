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
        

## Taches 5: Creation de la page ClientOffice.php


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


## Taches 6: Fonctionnalite Voir solde actuel:
- Dans ClientOfficeController.php
- Methodes a creer:
    - getSoldeActuel($id_user)
        - recuperer le solde actuel du client via l'id_user
        - retourner le solde actuel
    - getHistoriqueOperations($id_user)
        - recuperer l'historique des operations du client via l'id_user
        - retourner l'historique des operations


====
FINI
====



======================================================================================================================
# Version 2:
## COTE OPERATEUR:
### Taches 1: ETU004246
- Configuration des prefixes valables pour les autres operateurs:
    - Table: operateur
        - id
        - prefixe ( varchar )
        - operateur ( varchar )
    ex: 033 , airtel
    - Controller: OperateurOfficeController.php
        - index() -> listePrefixe() -> OperateurOffice.php

====
FINI
====
    
### Taches 2: ETU003962
- Configuration % en plus de commissions pour les transferts vers les autres operateurs:
    - Table commission_operateur:
        - id
        - id_operateur ( operateur vers lequel le transfert est fait )
        - pourcentage ( numerique 10,2)

    - donnee:
        - Airtel: 5%
        - Orange: 10%
        - Telma: 15%


### Taches 3: ETU004246
COTE CLIENT:
 Option inclure frais de retrait lors de l'envoi:
    exemple:
        un operateur yas fait un transfert de 5000 Ar vers yas, :
                - frais de transfert : 250 Ar , selon la table bareme_frais
                - frais de retrait : 0 Ar pour le frais de retrait ( pour le meme operateur )
            l'argent a transferer = argenet a transferer + frais de transfert + frais de retrait
        un operateur yas fait un transfert de 5000 Ar vers airtel, :
                - frais de transfert : 250 Ar , selon la table bareme_frais ( par exemple )
                - frais de retrait : on utilsie la talbe commission_operateur pour calculer le % de frais de retrait selon l'operateur du destinataire , dans notre exmeple , c'est airtel, donc 10% de 5000 Ar = 500 Ar
            argent a transferer = argenet a transferer + frais de transfert + frais de retrait


 Envoi multiple vers plusieurs numeros ( divise le montannt  pour chaque numero ) pour le meme operateur UNIQUEMENT 
    - exemple :
    un client yas : 0347777777 a un solde actuel 2000 Ar
    et il souhaite effectuer un transfert de 1500 Ar vers 0348888888 et 500Ar vers 0349999999

Alea
Promotion pendant transfert au meme operateur , Exemple -10 pourcent 
Base 10 pourcent 
si possibe creer une page pour changer le pourcentage ,Bonus

Todo:
- Correction base:OK
- Correction fonction dans ClientOfficeController:
    . calculateTransfertAmounts : En cours
    . function traiterTransfert() :En cours


==================================================================================================================================================

# Alea 2 3962
- Notion d'une epargne :
    - on a une interface : % Epargne -> a enregistrer dans une base: ( c'est le client qui va le choisir )
        - ref misy vola tong any aminy de , x% anle vola makany amin Epargne any , de ny ambiny mankany @ solde principal 
        - @ transfert ihany 

# Todolisst:
- Table a creer: 
[ok]   - pourcentage_epargne ( id, id_user, pourcentage )
[ok]   - epargne ( id, id_user, montant , date  )

- Models:
[ok]    - EpargneModel.php:

- Views: 
    ClientOffice.php:
[ok]        1- ajout Menu: % Epargne

    client/epargne.php
[ok]        2- Formulaire Pourcentage Epargne: ( client-office/pourcentage)
                - Pourcentage 
                - Bouton Enregistrer -> 
                - Bouton Annuler

- Routes.php:
[ok]    $routes->post('/client-office/pourcentage', 'ClientOfficeController::pourcentage_epargne');
[ok]    $routes->get('/client-office/pourcentage-actuel/', 'ClientOfficeController::pourcentage_epargne_actuel');


- Controller:
    - ClientOfficeController.php:
        1- Methode: pourcentage_epargne( id_user, pourcentage) : insertion de pourcentage_epargene pour un user
        ```- Table pourcentage_epargne

        2- Methode: pourcentage_epargne_actuel( id_user)
        ```- retourne la derniere pourcentage epargne 

        


