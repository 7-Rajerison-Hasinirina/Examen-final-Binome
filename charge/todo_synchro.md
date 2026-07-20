# Handoff - suite a continuer

Le dossier `charge` contient 32 fichiers modifies/ajoutes. Il faut les copier dans ce projet car c'est la suite du projet.toutefois il y pourrait y avoir des incohérances dans ces fichiers par rapport au code actuels et je veux tu verifies/rectifies les erreurs tout en respectant le sujet.

## Etapes
1) Copier tous les fichiers nommés ci-dessous placé dans le dossier charge dans le projet , en respectant les chemins exacts.
2) Appliquer le SQL `tarif_duree` si la base n est pas encore a jour.
3) (Optionnel) Ajouter des tests QA/validation.

## Fichiers a placer (chemins exacts)
- back_office.md
- app/Config/Autoload.php
- app/Config/Routes.php
- app/Controllers/BackOffice.php
- app/Controllers/GestionPorteMonnaie.php
- app/Controllers/GestionRegime.php
- app/Controllers/RoleFilter.php
- app/Models/ActiviteModel.php
- app/Models/CodeModel.php
- app/Models/CodeUserModel.php
- app/Models/NiveauIntensiteModel.php
- app/Models/NormeImcModel.php
- app/Models/ObjectifModel.php
- app/Models/RegimeModel.php
- app/Models/SportModel.php
- app/Models/TarifDureeModel.php
- app/Views/layout.php
- app/Views/porte_monnaie.php
- app/Views/regime.php
- app/Views/back_office/activites.php
- app/Views/back_office/activites_form.php
- app/Views/back_office/codes.php
- app/Views/back_office/codes_form.php
- app/Views/back_office/dashboard.php
- app/Views/back_office/parametres.php
- app/Views/back_office/parametres_norme_form.php
- app/Views/back_office/parametres_objectif_form.php
- app/Views/back_office/parametres_tarif_form.php
- app/Views/back_office/regimes.php
- app/Views/back_office/regimes_form.php
- sql/20260506-01-init-table.sql
- sql/20260506-02-insert.sql

## Rappel
- Les routes back-office sont protegees par `auth,role:2`.
- Le module porte-monnaie est accessible via `porte-monnaie` (menu lateral mis a jour).
- La tarification par duree se base sur `tarif_duree`.
- La variation par semaine est affichee dans les vues.


