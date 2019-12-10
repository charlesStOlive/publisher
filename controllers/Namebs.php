<?php namespace Waka\Publisher\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Namebs Back-end Controller
 */
class Namebs extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.ReorderController',
        'Backend.Behaviors.RelationController',
            
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $reorderConfig  = 'config_reorder.yaml';
    public $relationConfig = 'config_relation.yaml';

            public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Waka.Publisher', 'publisher', 'namebs');
    }
}
