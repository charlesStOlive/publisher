<?php namespace Waka\Publisher\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Sectors Contents Back-end Controller
 */
class SectorsContents extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Waka.Publisher', 'publisher', 'sectorscontents');
    }
}
