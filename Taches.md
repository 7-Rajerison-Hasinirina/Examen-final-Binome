# Travaux Binome: 
- ETU003962 : RAJERISON Hasinirina
- ETU004246 : RATOVONIAINA Faneva Mamisoa

## Taches 1: ETU003962
- Conception relationnelle:
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
        - numero ( unique ) ( varchar )
    ex: 3377745


    - role
        - id
        - libelle
    ex: admin, client, operateur


    - user:
        - id
        - nom ( varchar )
        - id_role
    ex: Rakoto Jean , client
        Jean Paul, admin 
        Yas, operateur 


    - numero_user:
        - id
        - id_prefixe
        - id_numero
        - id_user
        - date_creation ( datetime automatique )
    ex:
    Rakoto : 037 11 111 11
    Rakoto: 033 11 111 12 
        

    - bareme_frais:
        - id
        - intervalle1 ( numerique 10,2)
        - intervalle2 ( numerique 10,2)
        - frais ( numerique 10,2)
        - id_operateur 
    ex: pour yas, retrait ou transfert de l'argent entre 100Ar -> 1000 Ar : frais : 50 Ar


    - historique_operation:
        - id
        - date
        - id_user
        - id_operation
        - valeur
    ex:  20 juillet 2026, 11:31 , Rakoto Jean , fait un retrait, 5000 Ar, 
