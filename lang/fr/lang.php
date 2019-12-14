<?php

return [
    'menu' => [
        'title' => 'Contenu',
        'bloc_name' => 'Gestion des blocs',
        'bloc_type' => 'types',
        'documents' => 'Documents',
    ],
    'bloc' => [
        'name' => 'Intitulé',
        'name_ex' => 'UNiquement utilisé dans le BO',
        'bloc_name' => 'Nom',
        'bloc_name_ex' => 'Liaison avec le document',
        'bloc_type' => 'Type de contenu',
        'code' => 'code'
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
        'path' => 'Fichier source',
        'analyze' => "Log d'analyse des codes du fichier source",
        'has_sectors_perso' => 'Personaliser le contenu en fonction du secteur'
    ],
    'objtext' => [
        'data' => 'Paragraphes',
        'data_prompt' => "Cliquez ici pour ajouter un paragraphe",
        'value' => 'Paragraphe',
        'jump' => 'Saut de ligne entre les deux paragraphes',
        
    ],
];
