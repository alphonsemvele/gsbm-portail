<?php

/*
 * Menu lateral de chaque espace. Un lien n'est affiche que si l'utilisateur
 * a le droit d'ouvrir la page (User::allowedPrefixes) ; un groupe vide
 * disparait. Les libelles de la scolarite suivent config/etablissement.php.
 *
 * Icones disponibles : voir components/layouts/app.blade.php ($icones).
 */
return [

    'admin' => [
        ['titre' => 'Tableau de bord', 'lien' => '/admin', 'icone' => 'accueil'],

        ['groupe' => 'Élèves', 'module' => 'inscriptions', 'liens' => [
            ['titre' => 'Inscriptions', 'lien' => '/admin/inscriptions', 'icone' => 'eleves'],
            ['titre' => 'Nouvelle inscription', 'lien' => '/admin/inscriptions/nouvelle?type=inscription', 'icone' => 'ajouter'],
            ['titre' => 'Réinscription', 'lien' => '/admin/inscriptions/nouvelle?type=reinscription', 'icone' => 'reinscrire'],
            ['titre' => 'Import Excel', 'lien' => '/admin/inscriptions/import', 'icone' => 'importer'],
        ]],

        ['groupe' => 'Scolarité', 'liens' => [
            ['titre' => 'libelles.specialite', 'lien' => '/admin/specialite', 'icone' => 'classe'],
            ['titre' => 'libelles.filiere', 'lien' => '/admin/filiere', 'icone' => 'niveaux'],
            ['titre' => 'libelles.departement', 'lien' => '/admin/departement', 'icone' => 'sections'],
            ['titre' => 'libelles.cycle', 'lien' => '/admin/cycle', 'icone' => 'cycle'],
            ['titre' => 'libelles.ue', 'lien' => '/admin/ue', 'icone' => 'domaines'],
            ['titre' => 'Matières', 'lien' => '/admin/cours', 'icone' => 'matieres'],
            ['titre' => 'Évaluations', 'lien' => '/admin/examen', 'icone' => 'evaluation'],
            ['titre' => 'Saisie des notes', 'lien' => '/admin/notes', 'icone' => 'notes'],
            ['titre' => 'Barbillard', 'lien' => '/admin/barbillard', 'icone' => 'barbillard'],
            ['titre' => 'Salles', 'lien' => '/admin/salle', 'icone' => 'salle'],
        ]],

        ['groupe' => 'Vie scolaire', 'liens' => [
            ['titre' => 'Cantine', 'lien' => '/admin/menu', 'icone' => 'cantine'],
            ['titre' => 'Bibliothèque', 'lien' => '/admin/bibliotheque', 'icone' => 'livre'],
            ['titre' => 'Support', 'lien' => '/admin/support', 'icone' => 'support'],
        ]],

        ['groupe' => 'Communication', 'liens' => [
            ['titre' => 'Annonces', 'lien' => '/admin/annonce', 'icone' => 'annonce'],
            ['titre' => 'Articles', 'lien' => '/admin/article', 'icone' => 'document'],
            ['titre' => 'Contacts à notifier', 'lien' => '/admin/notification', 'icone' => 'cloche'],
        ]],
    ],

    'personnel' => [
        ['titre' => 'Mon espace', 'lien' => '/personnel', 'icone' => 'accueil'],
        ['groupe' => 'Ma classe', 'liens' => [
            ['titre' => 'Vue d’ensemble', 'lien' => '/specialite', 'icone' => 'classe'],
            ['titre' => 'Élèves', 'lien' => '/specialite/etudiant', 'icone' => 'eleves'],
            ['titre' => 'Matières', 'lien' => '/specialite/cours', 'icone' => 'matieres'],
            ['titre' => 'Évaluations & notes', 'lien' => '/specialite/examens', 'icone' => 'notes'],
        ]],
        ['groupe' => 'Moi', 'liens' => [
            ['titre' => 'Mon profil', 'lien' => '/personnel/profil', 'icone' => 'profil'],
        ]],
    ],

    'eleve' => [
        ['titre' => 'Accueil', 'lien' => '/dashboard', 'icone' => 'accueil'],
        ['groupe' => 'Scolarité', 'liens' => [
            ['titre' => 'Mes notes', 'lien' => '/dashboard/notes', 'icone' => 'notes'],
            ['titre' => 'Barbillard', 'lien' => '/dashboard/barbillard', 'icone' => 'barbillard'],
            ['titre' => 'Mes évaluations', 'lien' => '/dashboard/examen', 'icone' => 'evaluation'],
            ['titre' => 'Mes matières', 'lien' => '/dashboard/cours', 'icone' => 'matieres'],
        ]],
        ['groupe' => 'Vie scolaire', 'liens' => [
            ['titre' => 'Cantine', 'lien' => '/dashboard/menu', 'icone' => 'cantine'],
            ['titre' => 'Bibliothèque', 'lien' => '/dashboard/bibliotheque', 'icone' => 'livre'],
            ['titre' => 'Support', 'lien' => '/dashboard/support', 'icone' => 'support'],
            ['titre' => 'Mon compte', 'lien' => '/dashboard/compte', 'icone' => 'profil'],
        ]],
    ],
];
