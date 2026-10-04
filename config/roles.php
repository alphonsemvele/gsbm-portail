<?php

/*
|--------------------------------------------------------------------------
| Catalogue des rôles de l'application
|--------------------------------------------------------------------------
|
| C'est l'application qui définit ses rôles. Le portail les récupère
| (bouton « Synchroniser » ou `php artisan portail:synchroniser`) et
| l'administrateur les attribue au personnel. Un employé peut en cumuler
| plusieurs : ses accès et son menu sont alors l'union de ceux de ses rôles.
|
| L'ordre compte : le premier rôle détenu décide de l'espace d'accueil.
|
| - libelle / description : affichés dans l'administration du portail ;
| - role_local : valeur de la colonne users.role (enum) pour ce rôle ;
| - espace : menu affiché (config/navigation.php) ;
| - acces : chemins ouverts en plus de l'espace personnel.
|
*/

return [

    'admin' => [
        'libelle' => 'Administrateur',
        'description' => "Direction : accès complet à l'administration de l'école.",
        'role_local' => 'admin',
        'espace' => 'admin',
    ],

    'inscriptions' => [
        'libelle' => 'Chargé des inscriptions',
        'description' => 'Inscriptions, réinscriptions, import Excel et export de la liste des élèves.',
        'role_local' => 'personnel',
        'espace' => 'personnel',
        'acces' => ['admin/inscriptions', 'admin/inscriptions/*'],
    ],

    'enseignant' => [
        'libelle' => 'Enseignant',
        'description' => 'Sa classe : élèves, matières, évaluations et saisie des notes.',
        'role_local' => 'enseignant',
        'espace' => 'personnel',
        'acces' => ['specialite', 'specialite/*'],
    ],

    'bibliothecaire' => [
        'libelle' => 'Bibliothécaire',
        'description' => 'Gestion de la bibliothèque : ouvrages et emprunts.',
        'role_local' => 'bibliothecaire',
        'espace' => 'personnel',
        'acces' => ['admin/bibliotheque', 'admin/bibliotheque/*'],
    ],

    'cantine' => [
        'libelle' => 'Responsable cantine',
        'description' => 'Menus de la cantine.',
        'role_local' => 'personnel',
        'espace' => 'personnel',
        'acces' => ['admin/menu', 'admin/menu/*'],
    ],

    'concierge' => [
        'libelle' => 'Concierge',
        'description' => 'Espace personnel.',
        'role_local' => 'concierge',
        'espace' => 'personnel',
    ],

    'personnel' => [
        'libelle' => 'Personnel',
        'description' => 'Espace personnel.',
        'role_local' => 'personnel',
        'espace' => 'personnel',
    ],

];
