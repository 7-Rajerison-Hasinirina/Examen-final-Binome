# Back office - etat actuel et suite

## Ce que ton ami a fait (etat code actuel)
### Routes
- app/Config/Routes.php :
  - ajout de /regime/calculer et /regime (affichage programme)
  - ajout de /logout

### Logique regime + activites
- app/Controllers/GestionRegime.php :
  - calcule un programme combine regimes + activites
  - enregistre selection dans regime_selection
  - enregistre details dans regime_user et activite_user
  - nouvelle action afficherRegime qui affiche le programme

### Modeles
- app/Models/RegimeModel.php :
  - regles de validation
  - getListe() combine regimes + activites selon objectif
  - calculerRegime() subset-sum
  - sauvegarder() enregistre selection + liens
- app/Models/ActiviteModel.php : model table activite_sportive
- app/Models/RegimeSelection.php : model table regime_selection
- app/Models/RegimeUserModel.php : model table regime_user
- app/Models/ActiviteUserModel.php : model table activite_user

### Vue programme
- app/Views/regime.php : affichage detaille du programme (regimes + activites + cout + duree)

### Base de donnees
- sql/20260506-01-init-table.sql :
  - ajout table activite_user
  - activite_sportive contient duree_jours
  - regime_selection contient valeur_cible
- sql/20260506-02-insert.sql :
  - ajout donnees info_user
  - objectif reduit a 2 valeurs
  - activite_sportive avec duree_jours

## Ce qui reste a faire (back office complet)
### A. QA / tests (optionnel)
- tests validation + calculs

### B. Execution SQL (si pas encore fait)
- appliquer les modifications sql (tarif_duree)

## Suite - plan detaille (etat actuel)
### 1) Mise en place back office (routes + controller + vues)
- OK

### 2) CRUD regimes (admin)
- OK

### 3) CRUD activites sportives (admin)
- OK

### 4) Codes porte-monnaie
- OK (CRUD admin + saisie user + historique)

### 5) CRUD parametres
- OK (objectifs, normes IMC, tarifs)

### 6) Prix selon duree
- OK (tarif_duree + prix calcule)

### 7) Variation poids selon duree
- OK (affichage total + par semaine)

### 8) Dashboard + stats
- OK (stats + tableaux croises + export CSV/PDF)

### 9) QA / tests
- A faire (optionnel)

## Suite - references (fichiers a creer/modifier)
### A. Back office (routes/controller/vues)
- app/Config/Routes.php
- app/Controllers/BackOffice.php
- app/Views/back_office/*.php

### B. CRUD regimes
- app/Models/RegimeModel.php
- app/Views/back_office/regimes.php
- app/Views/back_office/regimes_form.php

### C. CRUD activites sportives
- app/Models/ActiviteModel.php
- app/Models/SportModel.php (si manquant)
- app/Models/NiveauIntensiteModel.php (si manquant)
- app/Views/back_office/activites.php
- app/Views/back_office/activites_form.php

### D. Codes porte-monnaie
- app/Models/CodeModel.php
- app/Models/CodeUserModel.php
- app/Controllers/GestionPorteMonnaie.php
- app/Views/porte_monnaie.php
- app/Views/back_office/codes.php
- app/Views/back_office/codes_form.php

### E. Parametres
- app/Models/ObjectifModel.php
- app/Models/NormeImcModel.php
- app/Models/TarifDureeModel.php
- app/Views/back_office/parametres.php
- app/Views/back_office/parametres_objectif_form.php
- app/Views/back_office/parametres_norme_form.php
- app/Views/back_office/parametres_tarif_form.php

### F. Dashboard
- app/Views/back_office/dashboard.php
- app/Controllers/BackOffice.php (stats + export)
### G. Wallet + securite
- app/Controllers/RoleFilter.php
- app/Views/layout.php (lien porte-monnaie)
