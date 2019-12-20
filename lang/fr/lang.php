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
    ],
    'document' => [
        'name' => 'Nom',
        'path' => 'Fichier source',
        'analyze' => "Log d'analyse des codes du fichier source",
        'has_sectors_perso' => 'Personaliser le contenu en fonction du secteur',
        'data_source' => ' Sources des données',
        'data_source_placeholder' => 'Choisissez une source de données'
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
        'create_content' => 'Créer votre contenu',
        'version_for_sector' => 'Version pour le secteur : ',
    ],
    'word' => [
        'processor' => [
            'bad_format' =>'Fromat du tag incorrect',
            'bad_tag' =>'Le type de tag est incorrect',
            'type_not_exist' =>"Ce type de tag n'existe pas",
            'field_not_existe' =>"Le champs n'existe pas",
            'id_not_exist' =>"L'id n'existe pas",
            'document_not_exist' =>" La source du document n'a pas été trouvé",
        ]
    ]
    
];
