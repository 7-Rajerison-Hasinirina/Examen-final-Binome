# Les fichiers de configuration les plus utiles dans `app/Config` (CodeIgniter 4)

## 1. Database.php ⭐⭐⭐⭐⭐
Configure la connexion à la base de données.

### Utilisation
- Choisir le pilote (MySQL, SQLite, PostgreSQL...)
- Définir l'hôte
- Définir l'utilisateur et le mot de passe
- Choisir la base de données

### Exemple

```php
public array $default = [
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'regime_alimentaire',
    'DBDriver' => 'SQLite3',
];
```

---

## 2. Routes.php ⭐⭐⭐⭐⭐
Définit les routes de l'application.

### Utilisation
- Associer une URL à un contrôleur
- Définir les routes GET, POST, PUT, DELETE

### Exemple

```php
$routes->get('/', 'Home::index');

$routes->get('/aliments', 'AlimentController::index');

$routes->post('/aliments/create', 'AlimentController::create');
```

---

## 3. App.php ⭐⭐⭐⭐☆
Configuration générale de l'application.

### Utilisation
- baseURL
- Langue
- Fuseau horaire
- Encodage

### Exemple

```php
public string $baseURL = 'http://localhost:8080/';

public string $defaultLocale = 'fr';

public string $appTimezone = 'Indian/Antananarivo';
```

---

## 4. Validation.php ⭐⭐⭐⭐⭐
Permet de créer des règles de validation réutilisables.

### Utilisation
- Validation des formulaires
- Messages d'erreur
- Groupes de règles

### Exemple

```php
public array $user = [

    'nom' => 'required|min_length[3]',

    'email' => 'required|valid_email',

    'age' => 'required|integer|greater_than[0]'

];
```

Dans un contrôleur :

```php
if (! $this->validate('user')) {
    return redirect()->back()->withInput();
}
```

---

## 5. Filters.php ⭐⭐⭐⭐☆
Configure les filtres exécutés avant ou après une requête.

### Utilisation
- Authentification
- Protection CSRF
- Vérification des permissions

### Exemple

```php
public array $globals = [

    'before' => [
        'csrf',
    ],

    'after' => [
        'toolbar',
    ],

];
```

---

## 6. Security.php ⭐⭐⭐⭐☆
Paramètres liés à la sécurité.

### Utilisation
- Protection CSRF
- Sécurité des cookies
- En-têtes de sécurité

### Exemple

```php
public string $csrfProtection = 'cookie';

public bool $regenerate = true;
```

---

## 7. Session.php ⭐⭐⭐⭐☆
Configure les sessions.

### Utilisation
- Durée de vie
- Emplacement de stockage
- Nom de la session

### Exemple

```php
public string $driver = 'CodeIgniter\Session\Handlers\FileHandler';

public int $expiration = 7200;

public string $cookieName = 'ci_session';
```

---

## 8. Email.php ⭐⭐⭐☆☆
Configuration des emails.

### Exemple

```php
public string $fromEmail = 'admin@example.com';

public string $SMTPHost = 'smtp.gmail.com';

public int $SMTPPort = 587;
```

---

# Les fichiers les plus importants pour les formulaires

Lorsque l'on développe des formulaires CRUD (Créer, Lire, Modifier, Supprimer), les fichiers suivants sont les plus utilisés.

| Fichier | Utilité |
|----------|----------|
| Routes.php | Définir les URL des formulaires |
| Validation.php | Vérifier les données saisies |
| Filters.php | Sécuriser les formulaires (CSRF, authentification) |
| Security.php | Protection contre les attaques |
| Database.php | Enregistrer les données dans la base |

---

# Exemple complet

## Route

```php
$routes->get('/utilisateur/new', 'Utilisateur::new');
$routes->post('/utilisateur/save', 'Utilisateur::save');
```

## Validation

```php
public array $user = [

    'nom' => 'required|min_length[3]',

    'email' => 'required|valid_email',

];
```

## Contrôleur

```php
public function save()
{
    if (! $this->validate('user')) {

        return redirect()->back()->withInput();

    }

    // Enregistrement dans la base

    return redirect()->to('/utilisateur');
}
```

## Vue

```php
<form action="/utilisateur/save" method="post">

    <?= csrf_field() ?>

    <input type="text" name="nom">

    <input type="email" name="email">

    <button type="submit">Enregistrer</button>

</form>
```

---

# Résumé

| Fichier | Priorité |
|----------|-----------|
| Database.php | ⭐⭐⭐⭐⭐ |
| Routes.php | ⭐⭐⭐⭐⭐ |
| Validation.php | ⭐⭐⭐⭐⭐ |
| Filters.php | ⭐⭐⭐⭐☆ |
| App.php | ⭐⭐⭐⭐☆ |
| Security.php | ⭐⭐⭐⭐☆ |
| Session.php | ⭐⭐⭐⭐☆ |
| Email.php | ⭐⭐⭐☆☆ |

> Pour le développement de formulaires avec CodeIgniter 4, les trois fichiers les plus importants sont **Routes.php**, **Validation.php** et **Database.php**. Ils permettent de définir les URL, de valider les données saisies et d'interagir avec la base de données.








## base: 'operateur.db'
### Tables:
- operateur
    - id
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