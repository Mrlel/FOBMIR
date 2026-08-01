# Audit de sécurité et de code — Projet FOBMIR

**Stack :** Laravel 10.10 / PHP 8.1
**Date de l'audit :** 2026-07-28
**Périmètre analysé :** routes, middleware, configuration, modèles Eloquent, migrations, contrôleurs, policies, seeders, vues Blade, dossier `public/`

---

## Sommaire

- [Résumé exécutif](#résumé-exécutif)
- [🔴 Critique](#-critique)
- [🟠 Élevé](#-élevé)
- [🟡 Moyen](#-moyen)
- [🟢 Faible](#-faible)
- [Plan d'action priorisé](#plan-daction-priorisé)

---

## Résumé exécutif

L'application présente **plusieurs vulnérabilités critiques qui, combinées, permettent une prise de contrôle totale non authentifiée** de l'application (création d'un compte superadmin sans authentification, associé à un mot de passe par défaut prévisible), ainsi que **plusieurs fonctionnalités cœur du métier qui ne peuvent pas fonctionner en l'état actuel du code** (incohérences entre le schéma de base de données et les modèles Eloquent, provoquant des erreurs SQL 500 sur le tableau de bord utilisateur, la création de documents, la vérification d'e-mail, la création de « chef de sous-quartier », etc.).

Un fichier `cni.png` (carte d'identité) est par ailleurs actuellement exposé publiquement, sans aucune authentification, dans le dossier `public/` du serveur.

| Gravité | Nombre de constats |
|---|---|
| 🔴 Critique | 12 |
| 🟠 Élevé | 9 |
| 🟡 Moyen | 10 |
| 🟢 Faible | 5 |

---

## 🔴 CRITIQUE

### C1. Aucune protection d'authentification sur les routes `users`, `documents`, `admin/dashboard`, `admin/utilisateurs`

**Fichier(s) :** `routes/web.php` lignes 23, 66-67, 70, 72-74 ; `app/Http/Controllers/Admin/AdminController.php` lignes 17-36

**Problème**
`Route::resource('menages', ...)` (L23), `Route::resource('documents', ...)` (L66-67), `Route::resource('users', ...)` (L70), ainsi que les routes `/admin/dashboard`, `/point_focal/dashboard` et `/admin/utilisateurs` (L72-74) ne sont **enveloppées dans aucun middleware `auth`**, contrairement au reste des routes du même fichier qui, elles, sont bien protégées par `Route::middleware('auth')->group(...)`.

De plus, `AdminController::index()` et `AdminController::utilisateurs()` ne font eux-mêmes **aucune vérification d'authentification ni de rôle** :

```php
public function index()
{
    $users = User::all();
    $menages = Menage::all();
    $documents = Document::all();
    ...
    return view('Admin.dashboard', compact('users','menages','documents','villages','quartiers'));
}
```

**Impact**
N'importe quel visiteur anonyme peut :
- lister tous les utilisateurs de l'application ;
- lister/consulter/télécharger tous les documents (potentiellement des CNI, actes de naissance, etc.) ;
- créer, modifier ou supprimer des utilisateurs et des documents ;
- consulter le tableau de bord d'administration complet.

**Solution recommandée**
1. Envelopper toutes ces routes dans `Route::middleware(['auth'])->group(...)`.
2. Ajouter un contrôle de rôle dédié pour les routes d'administration (`/admin/*`) : créer un middleware `EnsureUserIsAdmin` (`role:admin,superadmin`) ou utiliser un `Gate::define('access-admin', ...)` vérifié en amont des contrôleurs Admin.
3. Écrire un test Feature qui vérifie qu'un utilisateur non connecté reçoit une redirection/403 sur toutes les routes `/admin/*`, `/users/*`, `/documents/*`.

---

### C2. Mot de passe par défaut prévisible à la création d'un utilisateur → prise de contrôle totale (chaîné avec C1)

**Fichier(s) :**
- `app/Http/Controllers/UserController.php` ligne 37 : `Hash::make('12345678')`
- `app/Http/Controllers/Admin/UsersController.php` ligne 27 : `Hash::make('admin1234')`
- `database/seeders/AdminSeeder.php` ligne 22 : `Hash::make('12345678')` (compte superadmin réel, avec nom et numéro de téléphone en clair dans le code source versionné)

**Problème**
Le formulaire de création d'utilisateur permet de choisir librement `role => superadmin` :

```php
$request->validate([
    ...
    'role' => 'required|in:superadmin,admin,point_focal',
    ...
]);
$data['password'] = Hash::make('12345678');
User::create($data);
```

Le mot de passe est fixe pour tous les nouveaux comptes, jamais imposé au changement (pas de flag `must_change_password`, pas d'expiration).

**Impact**
Combiné à **C1** (route `POST /users` non protégée par `auth`), un attaquant non authentifié peut :
1. Envoyer `POST /users` avec `role=superadmin`.
2. Se connecter immédiatement avec le mot de passe `12345678`.
3. Obtenir un accès superadmin complet à l'application.

**Solution recommandée**
1. Générer un mot de passe aléatoire fort (`Str::password(16)`) à la création, jamais un mot de passe fixe.
2. Envoyer ce mot de passe (ou un lien d'activation à usage unique et à expiration courte) par SMS/e-mail au nouvel utilisateur, jamais l'afficher en clair côté serveur.
3. Ajouter un flag `must_change_password` forçant le changement au premier login.
4. Retirer les identifiants réels (nom, téléphone) du `AdminSeeder.php` versionné ; les faire venir de variables d'environnement dédiées (`env('ADMIN_NOM')`, `env('ADMIN_TELEPHONE')`, `env('ADMIN_PASSWORD')`).

---

### C3. Duplication de classe : `Admin/UsersController.php` déclare le même namespace/nom que `UserController.php`

**Fichier :** `app/Http/Controllers/Admin/UsersController.php` lignes 3 et 9

**Problème**
Le fichier est situé dans `app/Http/Controllers/Admin/` mais déclare :

```php
namespace App\Http\Controllers;   // devrait être App\Http\Controllers\Admin
class UserController extends Controller  // devrait être UsersController
```

C'est **exactement le même nom de classe pleinement qualifiée** (`App\Http\Controllers\UserController`) que `app/Http/Controllers/UserController.php`. Cela viole la convention PSR-4 utilisée par `composer.json` (`"App\\": "app/"`).

**Impact**
Avec `optimize-autoloader: true` (`composer.json`), Composer génère un classmap qui scanne tous les fichiers PHP du dossier `app/`. Deux fichiers déclarant la même classe provoquent soit :
- une erreur fatale *« Cannot declare class App\Http\Controllers\UserController, because the name is already in use »* lors du chargement des deux fichiers dans le même processus,
- soit, selon l'ordre de génération du classmap, un **écrasement silencieux et imprévisible** du contrôleur réellement utilisé par les routes (avec par exemple le mot de passe par défaut `admin1234` au lieu de `12345678`, ou l'absence des méthodes `index/create/show/edit/destroy`).

**Solution recommandée**
1. Renommer le fichier en respectant PSR-4 : `namespace App\Http\Controllers\Admin; class UsersController extends Controller`.
2. Vérifier qu'aucune route n'a besoin de ce contrôleur (il semble être un doublon abandonné d'une refactorisation) ; si c'est le cas, le supprimer purement et simplement.
3. Lancer `composer dump-autoload -o` après correction et vérifier l'absence de warning de classe dupliquée.

---

### C4. Colonnes `documents.user_id` et `documents.individu_independant_id` inexistantes en base

**Fichier(s) :**
- `app/Models/Document.php` lignes 23, 27, 73 (`$fillable`, relations `auteur()`, `individuIndependant()`)
- `database/migrations/2025_09_09_220314_create_documents_table.php`
- `database/migrations/2026_01_19_000003_update_documents_for_pochettes_and_classeurs.php`
- `app/Http/Controllers/DocumentController.php` lignes 34, 40
- `app/Http/Controllers/IndividusController.php` lignes 36, 327

**Problème**
Après les deux migrations concernées, la table `documents` contient uniquement : `libelle, numero, fichier, est_valider, remarque, type_document_id, individu_id (colonne orpheline inutilisée), classeur_id, menage_id, individu_menage_id, nom_fichier, date_ajout`.

**Ni `user_id` ni `individu_independant_id` n'ont jamais été créées par une migration** (recherche exhaustive confirmée sur les 32 fichiers de migration). Or :

```php
// app/Models/Document.php
protected $fillable = [..., 'user_id', ..., 'individu_independant_id', ...];
public function auteur(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
public function individuIndependant(): BelongsTo { return $this->belongsTo(IndividuIndependant::class, 'individu_independant_id'); }
```

```php
// DocumentController.php:33-40
$data = $request->except('fichier');
$data['user_id'] = auth()->id();
...
Document::create($data);   // QueryException: Unknown column 'user_id'
```

```php
// IndividusController.php:35-37
$documents = Document::with('typeDocument')
    ->where('user_id', $userId)   // QueryException: Unknown column 'user_id'
    ->get();
```

**Impact**
- Le **tableau de bord utilisateur** (`/userDashboard`, page affichée à tout point focal après connexion) **plante systématiquement** avec une erreur SQL 500.
- Toute création de document via `DocumentController::store()` ou `IndividusController::documentsStore()` échoue également.

**Solution recommandée**
1. Créer une migration ajoutant réellement la colonne `user_id` (foreign key vers `users`, nullable ou non selon le besoin métier) à la table `documents`.
2. Décider si `individu_independant_id` est nécessaire : si oui, l'ajouter par migration ; sinon, retirer la relation et le champ `$fillable` correspondants du modèle, ainsi que la colonne orpheline `individu_id`.
3. Ajouter un test Feature (`DocumentControllerTest`) qui crée réellement un document via le endpoint HTTP pour détecter ce type de régression schéma/modèle à l'avenir.

---

### C5. Colonnes `email_verifie`, `profil_complet`, `actif` inexistantes sur `individus_independants`

**Fichier(s) :** `app/Models/IndividuIndependant.php` lignes 43-45 ; migration `2025_09_09_220312_individus_independants_table.php` ; `app/Http/Controllers/AutoEnregistrementController.php` lignes 138-142

**Problème**
Le modèle déclare :

```php
protected $fillable = [..., 'email_verifie', 'profil_complet', ...];
protected $casts = [..., 'email_verifie' => 'boolean', 'profil_complet' => 'boolean', 'actif' => 'boolean', ...];
```

mais **aucune migration ne crée ces trois colonnes** sur `individus_independants`. Le contrôleur de vérification d'e-mail les utilise pourtant directement :

```php
$individu->update([
    'email_verifie' => true,
    'email_verified_at' => now(),
    'verification_token' => null
]); // QueryException: Unknown column 'email_verifie'
```

**Impact**
Le lien de vérification envoyé par e-mail à chaque inscription d'un individu indépendant (`/auto-enregistrement/verify-email/{token}`) **plante systématiquement** (erreur 500).

**Solution recommandée**
1. Créer la migration `add_email_verifie_profil_complet_actif_to_individus_independants_table` ajoutant les 3 colonnes booléennes (avec valeurs par défaut cohérentes : `email_verifie` et `profil_complet` à `false`, `actif` à `true`).
2. Exécuter les migrations en environnement de test et vérifier le flux complet d'inscription + vérification d'e-mail.

---

### C6. `IndividuIndependant::marquerProfilComplet()` — méthode appelée mais jamais définie

**Fichier(s) :** appels dans `AutoEnregistrementController.php:144` et `IndividuIndependantController.php:79` ; absente de `app/Models/IndividuIndependant.php`

**Problème**
Les deux contrôleurs appellent `$individu->marquerProfilComplet();`, mais cette méthode n'existe nulle part dans le modèle `IndividuIndependant` (qui ne définit que `dossier()`, `classeurs()`, `documents()`, `creerStructureDocuments()`).

**Impact**
`Error: Call to undefined method App\Models\IndividuIndependant::marquerProfilComplet()`. Plante la vérification d'e-mail **et** la mise à jour du profil personnel.

**Solution recommandée**
Implémenter la méthode sur le modèle, par exemple :

```php
public function marquerProfilComplet(): void
{
    $complet = filled($this->telephone) && filled($this->date_naissance) && filled($this->adresse_complete);
    if ($this->profil_complet !== $complet) {
        $this->update(['profil_complet' => $complet]);
    }
}
```

(à adapter selon les critères métier réels de « profil complet »), ou supprimer les appels si la fonctionnalité n'est plus souhaitée.

---

### C7. `IndividuIndependantController::showDossier()` appelle un scope et une colonne inexistants

**Fichier :** `app/Http/Controllers/IndividuIndependantController.php` ligne 114

**Problème**

```php
$classeurs = $individu->classeurs()->active()->with('documents')->orderBy('ordre')->get();
```

Le modèle `Classeur` (`app/Models/Classeur.php`) ne définit **aucun scope `active()`**, et aucune migration ne crée de colonne `ordre` sur la table `classeurs`.

**Impact**
`BadMethodCallException` (méthode `active()` inexistante), puis, même corrigée, `QueryException: Unknown column 'ordre'`. La page « mon dossier » d'un individu indépendant (`/individu/dossier`) est **inutilisable**.

**Solution recommandée**
- Si la fonctionnalité de tri/activation des classeurs est réellement souhaitée : ajouter la colonne `ordre` (migration) et le scope `scopeActive()` sur `Classeur`.
- Sinon, simplifier en `$individu->classeurs()->with('documents')->orderBy('created_at')->get();`

---

### C8. Sous-système de paiement parallèle, totalement incompatible avec le schéma réel

**Fichier :** `app/Http/Controllers/IndependantPerson/documentController.php` lignes 94-165

**Problème**
La méthode `download()` utilise `DocumentPayment::paid()`, ainsi que les colonnes `payer_type`, `payer_id`, `status`, `amount`, `provider_payload`, `payment_url`, `provider`, et fait appel à `App\Services\FedaPayService` ainsi qu'à `config('fedapay.secret_key')` / `config('fedapay.download_price')` :

```php
$alreadyPaid = DocumentPayment::paid()
    ->where('document_id', $document->id)
    ->where('payer_type', $individu::class)
    ->where('payer_id', $individu->id)
    ->exists();
...
$fedaPay = app(FedaPayService::class);
```

Or :
- la table `document_payments` (migration `2026_03_26_000001_create_document_payments_table.php`) a un schéma **totalement différent** : `document_id, email_acheteur, telephone_acheteur, nom_acheteur, statut, transaction_id, token_telechargement, paye_le` ;
- le modèle `DocumentPayment` définit `scopePaye()`, pas `scopePaid()`/`paid()` ;
- le dossier `app/Services/` est **vide** : `App\Services\FedaPayService` n'existe pas ;
- aucun fichier `config/fedapay.php` n'existe dans le projet ;
- aucune route nommée `fedapay.callback` n'est définie (utilisée en L145).

**Impact**
Tout appel à cette méthode `download()` échoue immédiatement (colonnes SQL inexistantes dès les premières lignes, avant même d'atteindre le `config('fedapay.secret_key')`). C'est un **second système de paiement complet, mort et incompatible**, mélangé dans le même code que le système fonctionnel (`DocumentPaymentController`), source de confusion pour toute maintenance future.

**Solution recommandée**
1. Supprimer entièrement ce second système de paiement mort (`IndependantPerson\documentController::download()` dans sa forme actuelle), **ou**
2. Le terminer proprement en l'alignant sur le schéma réel `document_payments` et en réutilisant `DocumentPaymentController` existant plutôt que de dupliquer la logique.
3. Ne conserver **qu'un seul** flux de paiement dans l'application.

---

### C9. Webhook FedaPay non vérifié — contournement de paiement possible

**Fichier(s) :** `app/Http/Controllers/DocumentPaymentController.php` lignes 168-190 ; route exemptée de CSRF dans `routes/web_independant.php` lignes 25-27

**Problème**

```php
public function webhook(Request $request)
{
    $payload = $request->all();
    $event = $payload['event'] ?? null;
    $transactionId = $payload['data']['entity']['id'] ?? null;
    $paymentId = $payload['data']['entity']['custom_metadata']['payment_id'] ?? null;

    if ($event === 'transaction.approved' && $transactionId && $paymentId) {
        $payment = DocumentPayment::find($paymentId);
        if ($payment && $payment->statut !== 'paye') {
            $payment->update(['statut' => 'paye', 'transaction_id' => $transactionId, 'paye_le' => now()]);
        }
    }
    return response()->json(['status' => 'ok']);
}
```

Aucune **vérification de signature** du webhook (FedaPay fournit un secret de signature à valider, généralement via un en-tête HTTP dédié). La route est publique, sans authentification, et explicitement exemptée de CSRF (nécessaire pour un webhook légitime — mais alors la vérification de signature devient indispensable).

**Impact**
Il suffit d'envoyer manuellement une requête `POST /api/webhook/fedapay` avec un corps JSON forgé :

```json
{"event":"transaction.approved","data":{"entity":{"id":1,"custom_metadata":{"payment_id":<id_dun_paiement_en_attente>}}}}
```

pour marquer **n'importe quel paiement en attente comme payé**, sans avoir réellement payé, puis télécharger le document correspondant gratuitement via le `token_telechargement`.

À noter : la méthode `verify()` (lignes 84-122) du même contrôleur fait, elle, les choses correctement en revalidant la transaction via `Transaction::retrieve($transactionId)` auprès de l'API FedaPay — cette même vérification doit être répliquée dans `webhook()`.

**Solution recommandée**
1. Implémenter la vérification de la signature HMAC du webhook FedaPay (en-tête fourni par FedaPay, secret dédié).
2. Systématiquement revalider la transaction via `Transaction::retrieve($transactionId)` côté serveur avant de marquer un paiement comme payé — ne jamais faire confiance au contenu brut de la requête entrante.
3. Ajouter un test qui vérifie qu'un webhook sans signature valide est rejeté (401/403).

---

### C10. Mass Assignment non maîtrisé (`$request->all()` / `$request->except(...)`) sur des modèles sensibles

**Fichiers concernés (liste non exhaustive) :**
- `app/Http/Controllers/MenageController.php` lignes 87, 170
- `app/Http/Controllers/QuartierController.php` lignes 69-71, 136
- `app/Http/Controllers/ChefSousQuartierController.php` lignes 34, 61
- `app/Http/Controllers/DocumentController.php` lignes 33-40, 65-75
- `app/Http/Controllers/MenageDocumentController.php` ligne 57
- `app/Http/Controllers/IndependantPerson/documentController.php` ligne 56
- `app/Http/Controllers/IndividusController.php` lignes 101, 230, 326-330

**Problème**
Le schéma récurrent est :

```php
$request->validate([...]);              // valide seulement certains champs
$data = $request->except('fichier');    // récupère TOUS les autres champs soumis
Document::create($data);                // mass assignment sur des champs non validés
```

`$request->all()`/`$request->except(...)` transmet l'intégralité des champs soumis, pas seulement ceux couverts par `validate()`.

**Impact**
Pour `Document` en particulier, un utilisateur peut :
- définir lui-même `est_valider=1` dans le corps de sa requête pour valider son propre document sans passer par un contrôle métier ;
- injecter des valeurs arbitraires de `classeur_id`, `menage_id`, `individu_menage_id` non prévues par le formulaire — potentiel IDOR (rattacher un document à un ménage/classeur qu'il ne possède pas), puisque seule la présence du champ dans `$fillable` est vérifiée, pas sa cohérence métier.

**Solution recommandée**
1. Systématiser l'usage de `$request->validated()` (retour de `validate()`) au lieu de `$request->all()`/`except()`.
2. Pour les champs calculés côté serveur (`classeur_id`, `menage_id`, `user_id`, `date_ajout`…), les définir **après** avoir récupéré `validated()`, jamais laisser l'utilisateur les fournir.
3. Envisager des classes `FormRequest` dédiées par action (actuellement absentes du projet — dossier `app/Http/Requests` inexistant), avec une méthode `authorize()` qui centralise aussi le contrôle d'accès.

---

### C11. `public/cni.png`, `doc.jfif`, `pla.jfif`, `vache.jfif` — documents exposés sans authentification dans le webroot

**Fichier(s) :** `public/cni.png`, `public/doc.jfif`, `public/pla.jfif`, `public/vache.jfif` (fichiers non suivis par Git — `git status` retourne `?? public/cni.png` etc., mais bien présents sur le disque)

**Problème**
`cni.png` (1,9 Mo) porte un nom explicite évoquant une **Carte Nationale d'Identité**. Le dossier `public/` est le *document root* servi directement par le serveur web. Tout fichier qui s'y trouve est accessible par quiconque à l'URL `https://votre-domaine/cni.png`, sans authentification et potentiellement indexable par des moteurs de recherche.

**Impact**
Ceci expose potentiellement un document d'identité réel, à l'opposé complet de l'architecture protégée (Pochette → Dossier → Classeur → Document, avec vérifications d'accès par rôle/village) que le reste de l'application met en œuvre soigneusement pour ce type de donnée.

**Solution recommandée**
1. **Supprimer immédiatement** ces fichiers du serveur de production s'ils y sont présents.
2. Ne jamais placer de document utilisateur dans `public/` — l'application utilise déjà correctement `Storage::disk('public')` (répertoire `storage/app/public`, exposé via le symlink `public/storage`) pour les vrais uploads : ces fichiers à la racine semblent être des tests manuels oubliés.
3. Vérifier les logs d'accès du serveur pour évaluer si ce fichier a déjà été consulté/indexé publiquement.
4. Ajouter une règle de vérification (CI ou pre-commit hook) qui bloque tout fichier binaire ajouté directement à la racine de `public/`.

---

### C12. `ChefSousQuartier` — modèle sans `$fillable` → création impossible

**Fichier(s) :** `app/Models/ChefSousQuartier.php` (fichier de 12 lignes, vide de toute logique) ; `app/Http/Controllers/ChefSousQuartierController.php` lignes 34, 61

**Problème**
Contrairement à ses équivalents `ChefQuartier` et `ChefVillage`, le modèle `ChefSousQuartier` ne définit ni `$fillable`, ni `$guarded`, ni aucune relation :

```php
class ChefSousQuartier extends Model
{
    use HasFactory;
}
```

Le contrôleur appelle pourtant `ChefSousQuartier::create($request->all())`.

**Impact**
Par défaut, Eloquent applique `$guarded = ['*']` en l'absence de `$fillable` : `create()` lève une `MassAssignmentException`. **La création d'un chef de sous-quartier est donc impossible en l'état.**

**Solution recommandée**

```php
class ChefSousQuartier extends Model
{
    use HasFactory;
    protected $table = 'chefs_sous_quartier';

    protected $fillable = ['nom', 'sexe', 'debut_mandat', 'fin_mandat', 'sous_quartier_id'];
    protected $casts = [
        'debut_mandat' => 'date:Y-m-d',
        'fin_mandat' => 'date:Y-m-d',
    ];

    public function sousQuartier()
    {
        return $this->belongsTo(SousQuartier::class);
    }
}
```

---

## 🟠 ÉLEVÉ

### E1. Aucune limitation de tentatives de connexion (brute force)

**Fichier(s) :** `app/Http/Controllers/Auth/authController.php` lignes 18-46 ; `app/Http/Controllers/AutoEnregistrementController.php` lignes 78-104

**Problème** : ni `throttle`, ni `Illuminate\Foundation\Auth\ThrottlesLogins`, ni verrouillage de compte après échecs répétés. Le groupe de middleware `web` (`app/Http/Kernel.php`) n'inclut pas non plus de throttle par défaut sur ces routes.

**Solution recommandée** : appliquer `->middleware('throttle:5,1')` sur les routes `POST /login` et `POST /auto-enregistrement/connexion`, ou définir un rate limiter dédié via `RateLimiter::for('login', fn($request) => Limit::perMinute(5)->by($request->ip().'|'.$request->input('telephone')))` dans `RouteServiceProvider`.

---

### E2. Policies définies mais jamais utilisées, et référençant des modèles inexistants

**Fichier(s) :** `app/Policies/ClasseurIndependantPolicy.php` ligne 5 ; `app/Policies/DocumentIndependantPolicy.php` ligne 5 ; `app/Providers/AuthServiceProvider.php` lignes 15-17

**Problème** : `$policies = []` (aucun enregistrement), et les deux fichiers de Policy importent/typent `App\Models\ClasseurIndependant` et `App\Models\DocumentIndependant`, qui **n'existent pas** (seuls `Classeur` et `Document` existent dans `app/Models`). Toute tentative d'utilisation lèverait une erreur de classe introuvable.

**Impact** : la protection d'accès aux classeurs/documents des individus indépendants repose donc entièrement sur des vérifications manuelles dispersées dans `IndependantPerson\classeurController`/`documentController` (qui fonctionnent actuellement), sans mécanisme d'autorisation centralisé et testable.

**Solution recommandée** : soit finaliser ces Policies sur les vrais modèles (`Classeur`, `Document`) et les enregistrer dans `AuthServiceProvider::$policies`, soit les supprimer pour éviter toute confusion future.

---

### E3. Exemption CSRF orpheline (`cp/n`)

**Fichier :** `app/Http/Middleware/VerifyCsrfToken.php` ligne 15

**Problème** : `protected $except = ['cp/n'];` exempte une route de la protection CSRF, alors qu'**aucune route `cp/n`** n'existe dans `routes/web.php`, `api.php`, `web_geographique.php` ni `web_independant.php` (recherche exhaustive effectuée).

**Impact** : résidu de code mort actuellement sans effet, mais si une route de ce nom est un jour réintroduite, elle hériterait silencieusement d'une exemption CSRF sans que quiconque s'en rende compte lors d'une revue de code future.

**Solution recommandée** : supprimer cette entrée, ou documenter explicitement (commentaire) pourquoi elle doit être conservée si un besoin futur précis est prévu.

---

### E4. Endpoints AJAX de création géographique sans authentification

**Fichier :** `routes/web_geographique.php` lignes 46-52

**Problème** : les endpoints de création rapide (`/ajax/create/pays`, `districts`, `regions`, `departements`, `sous-prefectures`, `communes`, `villages`, `menages`) n'ont **aucun contrôle d'authentification**, contrairement à `/ajax/create/quartiers` (lignes 53-66) qui vérifie correctement `auth()->check()`.

**Impact** : n'importe qui peut injecter de fausses données géographiques ou de faux ménages dans la base, sans être connecté.

**Solution recommandée** : appliquer `->middleware('auth')` à l'ensemble de ce groupe de routes, par cohérence avec la route `quartiers` du même fichier.

---

### E5. Ressources géographiques (`chefs-*`, `sous-quartiers`, `quartiers`) accessibles sans authentification

**Fichier :** `routes/web_geographique.php` lignes 19-24

**Problème** : `Route::resource('chefs-sous-quartier', ...)`, `sous-quartiers`, `quartiers`, `chefs-village`, `chefs-quartier` ne sont protégées par aucun `auth`. `QuartierController` effectue bien des vérifications de rôle internes (`isAdminOrSuperAdmin()`), mais celles-ci supposent un `Auth::user()` non nul — un visiteur anonyme provoque une **erreur fatale** (`Call to a member function on null`) plutôt qu'un refus propre. `ChefSousQuartierController`, `ChefQuartierController` et `ChefVillageController` n'ont, eux, **aucune vérification**.

**Solution recommandée** : envelopper ce groupe de routes dans `Route::middleware('auth')`.

---

### E6. Contrainte d'unicité `classeurs` silencieusement jamais créée (avalage d'exceptions trop large)

**Fichier :** `database/migrations/2026_01_19_153509_fix_classeurs_constraints.php` lignes 46-55

**Problème**

```php
try {
    DB::statement('CREATE UNIQUE INDEX classeurs_unique_theme ON classeurs (
        COALESCE(pochette_id, 0), COALESCE(dossier_id, 0), theme
    )');
} catch (Exception $e) { /* Ignore si l'index existe déjà */ }
```

La syntaxe MySQL pour un index sur expression nécessite une double parenthèse par expression fonctionnelle (`((COALESCE(...)))`). Telle qu'écrite, cette requête échoue très probablement avec une erreur de syntaxe — silencieusement avalée par le `catch (Exception $e)` générique.

**Impact** : la contrainte métier « un thème de classeur doit être unique au sein d'une même pochette/dossier » n'est **probablement jamais appliquée en base**, alors que la migration se termine sans erreur visible, laissant croire à tort que la contrainte est active.

**Solution recommandée** : ne jamais capturer `Exception` de façon générique dans une migration pour « ignorer si ça existe déjà ». Vérifier explicitement l'existence de l'objet avant création (comme fait correctement ligne 13 pour la colonne `dossier_id` via `Schema::hasColumn`), ou au minimum logger l'exception réellement levée pour vérifier qu'il s'agit bien du cas « existe déjà » et non d'une erreur de syntaxe.

---

### E7. Champs GPS obligatoires en base mais non requis à la validation

**Fichier :** `app/Http/Controllers/AutoEnregistrementController.php` lignes 41-44, comparé à la migration `individus_independants` (colonnes `latitude`/`longitude` en `decimal` non nullable)

**Problème** : `'latitude' => 'numeric|between:-90,90'` (sans `required`). Si le champ est absent de la requête (géolocalisation refusée par le navigateur, ou simple omission), `$request->latitude` vaut `null`.

**Impact** : `IndividuIndependant::create([..., 'latitude' => null, ...])` échoue avec `Column 'latitude' cannot be null`. **L'inscription d'un individu indépendant plante** dès que la géolocalisation n'est pas fournie par le client.

**Solution recommandée** : ajouter `required` aux règles de validation si la géolocalisation est réellement obligatoire au métier, ou rendre les colonnes `latitude`/`longitude` nullables en base si elle est optionnelle.

---

### E8. `env('FEDAPAY_SECRET_KEY')` appelé directement dans le contrôleur au lieu de `config()`

**Fichier :** `app/Http/Controllers/DocumentPaymentController.php` lignes 18-19

**Problème** : usage direct de `env()` en dehors des fichiers `config/*.php`, contraire aux bonnes pratiques Laravel.

**Impact** : après un `php artisan config:cache` en production, `env()` appelé hors des fichiers de configuration renvoie systématiquement `null`. Le module de paiement casserait silencieusement en production dès l'activation du cache de configuration.

**Solution recommandée** : créer une entrée `config/services.php['fedapay']` (ou un fichier `config/fedapay.php` dédié) et utiliser `config('services.fedapay.secret')` / `config('services.fedapay.environment')` partout dans le code applicatif.

---

### E9. Logique d'autorisation dupliquée dans 6 contrôleurs au lieu d'une Policy centralisée

**Fichier(s) :** `MenageController` (via le trait `FiltersByVillage`), `IndividusController::canAccessIndividu/canManageIndividuDocuments`, `MenageDocumentController::canAccessMenage/canManageMenage`, `ClasseurController` (idem), `DossierController` (idem), `PochetteController` (idem)

**Problème** : la même règle métier (« admin/superadmin → accès total ; point_focal → accès uniquement si son `village_id` correspond au village du ménage ») est recopiée **à l'identique 5 à 6 fois** dans des méthodes privées différentes.

**Impact** : toute évolution de cette règle (ajout d'un rôle, changement de granularité) doit être répercutée manuellement dans chaque contrôleur, avec un risque élevé d'oubli créant une brèche d'accès locale à un seul module.

**Solution recommandée** : centraliser la règle dans une `MenagePolicy` Laravel (`php artisan make:policy MenagePolicy --model=Menage`), enregistrée dans `AuthServiceProvider`, et utilisée via `$this->authorize('view', $menage)` / `@can('view', $menage)` dans tous les contrôleurs concernés. Supprimer les méthodes `canAccessMenage`/`canManageMenage` dupliquées.

---

## 🟡 MOYEN

### M1. `AdminController::dashboardPointFocal()` — accès non protégé contre `Auth::user()` nul

**Fichier :** `app/Http/Controllers/Admin/AdminController.php` ligne 28 — `Auth::user()->id` sans vérification préalable ; plante si l'utilisateur n'est pas authentifié (cohérent avec **C1**, la route n'étant pas protégée par `auth`).

**Solution** : ajouter `if (!Auth::check()) { return redirect()->route('login.form'); }` en début de méthode (et, une fois C1 corrigé, ce garde-fou devient redondant mais reste une bonne pratique défensive).

---

### M2. `Menage::quartier()` / `Menage::village()` ne retournent pas une relation Eloquent valide

**Fichier :** `app/Models/Menage.php` lignes 22-30

```php
public function quartier() { return $this->sousQuartier?->quartier(); }
public function village() { return $this->sousQuartier?->quartier?->village(); }
```

**Problème** : ces méthodes retournent l'objet `Relation` (query builder) issu de l'appel imbriqué, et non le modèle résolu ; ce ne sont pas non plus de véritables relations Eloquent de premier niveau sur `Menage` (elles ne fonctionnent pas correctement avec `->with('quartier')` pour l'eager loading). Le même schéma incorrect existe dans `Dossier::menage()` (`app/Models/Dossier.php` lignes 58-61), sans opérateur `?->` en plus — donc `Error` si `pochette` est `null`.

**Solution recommandée** : utiliser une relation `hasOneThrough`, ou simplement transformer ces méthodes en accesseurs qui retournent le modèle directement :

```php
public function getQuartierAttribute()
{
    return $this->sousQuartier?->quartier;
}
```

---

### M3. Migration `2025_09_23_101328_fobmir.php` totalement vide

**Fichier :** `database/migrations/2025_09_23_101328_fobmir.php`

**Problème** : les méthodes `up()` et `down()` ne contiennent aucune instruction. Cette migration pollue l'historique sans aucune utilité.

**Solution recommandée** : la supprimer (après vérification qu'elle a bien déjà été exécutée sans effet en environnement de production — auquel cas ne pas toucher à la table `migrations`, simplement ignorer/documenter ce fichier).

---

### M4. Colonne `documents.individu_id` orpheline

**Fichier :** migration `2025_09_09_220314_create_documents_table.php` ligne 22

**Problème** : clé étrangère vers `individus_independants`, jamais référencée par le modèle `Document` (qui utilise `individu_independant_id`, colonne elle-même absente — voir **C4**).

**Solution recommandée** : supprimer cette colonne orpheline via une nouvelle migration, ou corriger le modèle pour l'utiliser réellement si elle a un sens métier.

---

### M5. `DatabaseSeeder` appelle `Type_quartierSeeder` deux fois et n'appelle jamais `QuartierSeeder`

**Fichier :** `database/seeders/DatabaseSeeder.php` lignes 24 et 27 (doublon)

**Problème** : `Type_quartierSeeder::class` est listé deux fois dans le tableau passé à `$this->call([...])`. Par ailleurs, le fichier `database/seeders/QuartierSeeder.php` existe mais n'est référencé nulle part dans `DatabaseSeeder`.

**Solution recommandée** : retirer le doublon, ajouter l'appel à `QuartierSeeder::class` si les quartiers doivent être seedés.

---

### M6. `AdminSeeder` contient des données personnelles réelles en dur dans le code versionné

**Fichier :** `database/seeders/AdminSeeder.php` lignes 18-23

**Problème** : nom et numéro de téléphone réels codés en dur, associés à un mot de passe faible et prévisible (voir **C2**).

**Solution recommandée** : déplacer ces valeurs vers des variables d'environnement dédiées (`env('ADMIN_NOM')`, `env('ADMIN_TELEPHONE')`, mot de passe généré aléatoirement et affiché une seule fois en console lors du seed).

---

### M7. `numpiece` (numéro de pièce d'identité) non unique dans `individu_menage`

**Fichier :** migration `2025_09_09_220312_create_individu_menage_table.php` ligne 21 ; comparer `IndividuMenageSuperAdminController::store()` (valide l'unicité) et `IndividusController::store()` (ne la valide pas)

**Problème** : aucune contrainte `unique` en base sur `numpiece`. `IndividuMenageSuperAdminController` impose l'unicité via la règle de validation `unique:individu_menage,numpiece`, mais `IndividusController::store()` (utilisé par les points focaux) ne le fait pas.

**Impact** : un même numéro de CNI peut être enregistré plusieurs fois par des points focaux différents.

**Solution recommandée** : ajouter une contrainte `unique` en base de données (migration) et harmoniser la règle de validation dans tous les contrôleurs concernés.

---

### M8. Table `users` sans colonne `email`, incohérente avec la configuration de réinitialisation de mot de passe

**Fichier :** `config/auth.php` lignes 105-111 ; `database/migrations/2025_09_09_220303_create_users_table.php` (aucune colonne `email`)

**Problème** : l'authentification se fait par `telephone`, mais `config/auth.php` configure un broker `password_reset_tokens` basé implicitement sur `email` (comportement standard Laravel), inutilisable tel quel. Cohérent avec le lien mort « Oublié ? » de `resources/views/Auth/login.blade.php` ligne 192, qui pointe vers `/forgot-password`, route qui **n'existe pas** dans le projet.

**Solution recommandée** : soit implémenter un flux de réinitialisation par téléphone/SMS avec un broker personnalisé, soit ajouter une colonne `email` optionnelle aux utilisateurs et adapter la configuration en conséquence, soit retirer le lien mort de la vue en attendant.

---

### M9. Combinaison `APP_DEBUG=true` / `FEDAPAY_ENVIRONMENT=live` dans `.env`

**Fichier :** `.env` lignes 4 et 20

**Problème** : le fichier d'environnement actuel active simultanément le mode debug et un environnement de paiement « live ».

**Impact** : si ce fichier (ou une copie proche) est utilisé en production, toute exception non interceptée affiche la trace complète (chemins serveur, requêtes SQL, parfois configuration) à n'importe quel visiteur — risque de fuite d'information aggravé par le fait qu'un module de paiement réel serait actif en parallèle.

**Solution recommandée** : s'assurer que `APP_DEBUG=false` et `APP_ENV=production` sur tout environnement de production ; ne jamais dupliquer un `.env` de développement vers la production sans revue explicite ; ajouter une vérification de démarrage qui refuse de lancer l'application si `APP_ENV=production` et `APP_DEBUG=true` simultanément.

---

### M10. Deux routes webhook FedaPay identiques enregistrées

**Fichier :** `routes/web_independant.php` lignes 25-27 et ligne 30

**Problème** : la route `POST /api/webhook/fedapay` est déclarée deux fois sous le même nom `payment.webhook`. Seule la première déclaration (exemptée de CSRF) sera effectivement atteinte par le routeur ; la seconde est du code mort qui prête à confusion lors d'une relecture.

**Solution recommandée** : supprimer la déclaration en doublon (ligne 30).

---

## 🟢 FAIBLE

### F1. Imports incorrects ou inutilisés

- `app/Http/Controllers/AutoEnregistrementController.php` ligne 6 : `use App\Models\Ville;` — ce modèle n'existe pas (le vrai modèle s'appelle `Village`). Import mort, jamais utilisé dans le fichier, révélateur d'une confusion de nommage lors du développement.
- `routes/web.php` lignes 17-18 : `use App\Http\Controllers\ClasseurIndependantController;` et `DocumentIndependantController;` — classes inexistantes (remplacées en pratique par `IndependantPerson\classeurController`/`documentController`), jamais référencées dans le fichier.

**Solution recommandée** : nettoyer ces imports morts pour éviter toute confusion future.

---

### F2. CORS très permissif sur `sanctum/csrf-cookie`

**Fichier :** `config/cors.php` ligne 22 — `allowed_origins => ['*']`.

**Problème** : sans risque immédiat tant que `supports_credentials => false` (ligne 32), mais à resserrer explicitement aux domaines front réels si un client SPA (avec cookies/session) est ajouté à l'avenir.

**Solution recommandée** : remplacer `['*']` par la liste explicite des domaines autorisés dès qu'un front-end distinct consommera l'API en mode authentifié par cookie.

---

### F3. `session.secure` non forcé

**Fichier :** `config/session.php` ligne 171 — dépend de `SESSION_SECURE_COOKIE`, non défini dans `.env` (donc `null`/`false`).

**Solution recommandée** : forcer `SESSION_SECURE_COOKIE=true` sur tout environnement de production servi en HTTPS, afin d'empêcher l'envoi du cookie de session en clair sur une éventuelle connexion HTTP.

---

### F4. Absence totale de `Http/Requests` (FormRequest) et d'`Http/Resources` (API Resources)

**Problème** : toute la validation est réalisée en ligne dans les contrôleurs, avec des styles hétérogènes (`$request->validate()`, `$request->only()`, `$request->all()` — voir **C10**). Aucun dossier `app/Http/Requests` n'existe dans le projet.

**Solution recommandée** : migrer progressivement vers des classes `FormRequest` dédiées par action (`php artisan make:request StoreMenageRequest`), avec une méthode `authorize()` qui centralise également le contrôle d'accès et une méthode `rules()` qui centralise la validation.

---

### F5. Requêtes potentiellement N+1 / logique dupliquée

- `app/Http/Controllers/Admin/AdminController.php` lignes 19-23 : `User::all(); Menage::all(); Document::all(); Village::all(); Quartier::all();` sans `with()` — à comparer avec `UserController::index()` qui, lui, applique correctement `with([...])` et une pagination (bon pattern à généraliser dans tout le projet).
- `app/Http/Controllers/MenageController.php` : la logique de filtrage des sous-quartiers par village de point focal (lignes 18-42) est dupliquée à l'identique dans `index()`, `create()` et `edit()`.

**Solution recommandée** : extraire cette logique répétée dans une méthode privée du contrôleur (ou un scope Eloquent `SousQuartier::visibleFor($user)`), et systématiser l'eager loading (`with()`) partout où des relations sont consommées dans les vues.

---

## Plan d'action priorisé

| Priorité | Constats | Action |
|---|---|---|
| 1 | C1, C2, C3 | Bloquer l'accès aux routes `users`/`documents`/`admin/*`, corriger la classe dupliquée, supprimer les mots de passe par défaut prévisibles |
| 2 | C11 | Retirer `cni.png` et les autres fichiers du webroot `public/` sans délai |
| 3 | C4, C5, C6, C7, C8 | Corriger les incohérences schéma/modèle (dashboard, vérification e-mail, dossier individuel) et supprimer/finaliser le second système de paiement mort |
| 4 | C9 | Sécuriser le webhook FedaPay (risque financier direct) |
| 5 | C10, C12 | Nettoyer le mass assignment et compléter le modèle `ChefSousQuartier` |
| 6 | E1 → E9 | Throttling de connexion, harmonisation des contrôles d'accès en Policies, nettoyage des routes non protégées |
| 7 | M1 → M10 | Corrections de cohérence schéma/code, nettoyage des seeders et migrations |
| 8 | F1 → F5 | Nettoyage de code mort, durcissement CORS/session, adoption de FormRequest |

---

*Rapport généré à partir d'une revue manuelle exhaustive du code source (routes, middleware, configuration, modèles, migrations, contrôleurs, policies, seeders, vues). Aucune hypothèse n'a été faite au-delà du code effectivement présent dans le dépôt.*
