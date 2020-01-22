<?php

return [
    'menu' => [
        'title' => 'Contenu',
        'documents' => 'Templates Words',
        'documents_description' => 'Gestion des templates et de leurs blocs',
        'bloc_name' => 'Blocs',
        'bloc_type' => 'types de blocs',
        'bloc_type_description' => 'Administration des types de blocs et exemples',
        'settings_category' => 'Wakaari publisher',
    ],
    'bloc' => [
        'name' => 'Intitulé',
        'name_ex' => 'UNiquement utilisé dans le BO',
        'bloc_name' => 'Nom',
        'bloc_name_ex' => 'Liaison avec le document',
        'bloc_type' => 'Type de contenu',
        'code' => 'Code',
        'version' => 'Les versions',
        'nb_content' => 'Variante'
    ],
    'bloc_name' => [
        'name' => 'Intitulé',
        'name_ex' => 'Uniquement utilisé dans le BO',
        'bloc' => 'Bloc contenu de références',
        'bloc_name' => 'Nom',
        'bloc_name_ex' => 'Liaison avec le document',
        'bloc_type' => 'Type de contenu',
    ],
    'bloc_type' => [
        'name' => 'Intitulé',
        'type' => 'Type de bloc',
        'type_bloc' => "Le contenu sera de type : 'bloc'",
        'type_raw' => "Le contenu sera de type : 'raw'",
        'code' => "Code d'itentification du bloc",
        'model' => 'Model associé',
        'ajax_method' => 'Méthode Ajax',
        'use_icon' => 'Utiliser une icone October',
        'icon_png' => 'Utiliser une icone PNG',
        'compiler' => 'Model Source du compilateur',
        'scr_explication' => 'Fichier Word d explication du bloc'
    ],
    'document' => [
        'name' => 'Nom',
        'path' => 'Fichier source',
        'analyze' => "Log d'analyse des codes du fichier source",
        'has_sectors_perso' => 'Personaliser le contenu en fonction du secteur',
        'data_source' => ' Sources des données',
        'data_source_placeholder' => 'Choisissez une source de données',
        'download' => 'Télécharger un exemple',
        'check' => 'Vérifier'
    ],
    'objtext' => [
        'data' => 'Paragraphes',
        'data_prompt' => "Cliquez ici pour ajouter un paragraphe",
        'value' => 'Paragraphe',
        'jump' => 'Saut de ligne entre les deux paragraphes',
    ],
    'content' => [
        'name' => 'Contenu',
        'sector' => "Secteur",
        'sector_placeholder' => 'Choisissez un secteur',
        'versions' => 'Les versions',
        'add_version' => 'Nouvelle version',
        'add_base' => 'Créer le contenu de base',
        'create_content' => "Création d'une version : ",
        'update_content' => "Mise à jour d'une version ",
        'version_for_sector' => 'Version pour le secteur : ',
        'sector' => 'Secteur de cette version',
        'reminder_content' => "Choisisir ou créer une version dans le tableau du dessus. Mettre à jour avant de quitter",
    ],
    'word' => [
        'processor' => [
            'bad_format' =>'Fromat du tag incorrect',
            'bad_tag' =>'Le type de tag est incorrect',
            'type_not_exist' =>"Ce type de tag n'existe pas",
            'field_not_existe' =>"Le champs n'existe pas",
            'id_not_exist' =>"L'id n'existe pas",
            'document_not_exist' =>" La source du document n'a pas été trouvé",
            'errors' => "Ce document à des erreurs, veuillez les corriger.",
            'success' => "Le systhème n'a pas trouvé d'erreurs. Pensez à verifier votre document après édition"
        ]
    ],
    'contents' => [
        'linkedphoto' => [
            'image' => "Choisissez une image",
            'image_placeholder' => "--Choisissez une image--",
            'width' => "Largeur (mm)",
            'height' => "Hauteur (mm) ",
            'width_explication' => "165 mm est la largeur du contenu par défaut",
            'keep_ratio'=> "Conserver le ration",
            'unit' => "Unité",
        ],
        'mediastextes' => [
            'data_prompt' => "Créer un pararaphe texte + photo",
            'value' => "Texte",
            'path' => "Choisir une image",
            'Jump' => "Ajouter un second saut de paragraphe",
        ],
        'textes' => [
            'data_prompt' => "Créer un pararaphe de texte",
            'value' => "Texte",
            'Jump' => "Ajouter un second saut de paragraphe",
        ]
        



    ]
    
];
