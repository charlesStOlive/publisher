<?php namespace Waka\Publisher\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;

/**
 * Bloc Types Back-end Controller
 */
class BlocTypes extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.ReorderController',

    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();

        //BackendMenu::setContext('Waka.Publisher', 'publisher', 'side-menu-bloc-types');
        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('Waka.Publisher', 'documents');
    }
}
