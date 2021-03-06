<?php

return [
    'disk'  => [
        'label'        => 'Disque de stockage',
        'instructions' => 'Choisissez un disque où stocker les fichiers envoyés.'
    ],
    'path'  => [
        'label'        => 'Chemin d\'envoi',
        'instructions' => 'Entrez le chemin d\'envoi des fichiers ou laissez vide pour envoyer à la racine du disque.<br>Si le chemin n\'existe pas il sera créé à lors de l\'envoi.'
    ],
    'image' => [
        'label'        => 'Images uniquement',
        'instructions' => 'Restreindre les fichiers aux images seulement.'
    ],
    'mimes' => [
        'label'        => 'Types de fichier autorisés',
        'instructions' => 'Choisissez les extensions de fichier autorisées. Si aucune extension n\'est renseigné tout les types de fichier pourront être envoyé.'
    ],
    'max'   => [
        'label'        => 'Taille maximale',
        'instructions' => 'Entrez la taille maximale par fichier en <strong>méga-octets</strong>.<br>La taille par défaut et la taille maximale sont la taille maximale autorisée par le serveur.'
    ]
];
