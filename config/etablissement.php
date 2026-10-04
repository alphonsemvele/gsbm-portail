<?php

/*
 * Identite de l'etablissement. Le code est commun a IUM, IFPM et GSBM :
 * seul le .env de chaque application change ces valeurs.
 */
return [
    'sigle' => env('ETABLISSEMENT_SIGLE', 'IUM NDAZOA'),
    'nom' => env('ETABLISSEMENT_NOM', 'Institut Universitaire La Majestueuse'),
    'slogan' => env('ETABLISSEMENT_SLOGAN', "Le chemin le plus court vers l'emploi."),
    'adresse' => env('ETABLISSEMENT_ADRESSE', 'Ndazoa, 7 km de Mbankomo, route Yaoundé-Douala'),
    'telephone' => env('ETABLISSEMENT_TELEPHONE', '+237 691 612 145'),
    'email' => env('ETABLISSEMENT_EMAIL', ''),
    'domaine' => env('ETABLISSEMENT_DOMAINE', 'ium-ndazoa.com'),
    // Chemin relatif a public/ : sert aux pages (asset) et aux PDF (public_path).
    'logo' => env('ETABLISSEMENT_LOGO', 'images/logo.png'),

    /* Vocabulaire du menu d'administration, adapte au type d'etablissement. */
    'libelles' => [
        'cycle' => env('LIBELLE_CYCLE', 'Cycle'),
        'cycle_aide' => env('LIBELLE_CYCLE_AIDE', 'Licence, Master...'),
        'departement' => env('LIBELLE_DEPARTEMENT', 'Départements'),
        'departement_aide' => env('LIBELLE_DEPARTEMENT_AIDE', 'Droit,...'),
        'filiere' => env('LIBELLE_FILIERE', 'Filière'),
        'filiere_aide' => env('LIBELLE_FILIERE_AIDE', "Programmes d'études"),
        'specialite' => env('LIBELLE_SPECIALITE', 'Spécialité'),
        'specialite_aide' => env('LIBELLE_SPECIALITE_AIDE', 'Options de filière'),
        'ue' => env('LIBELLE_UE', 'UE'),
        'ue_aide' => env('LIBELLE_UE_AIDE', "Unités d'enseign."),
        'etudiants' => env('LIBELLE_ETUDIANTS', 'Étudiants'),
    ],

    /* Modules propres a un etablissement. */
    'modules' => [
        'inscriptions' => (bool) env('ETABLISSEMENT_MODULE_INSCRIPTIONS', false),
    ],
];
