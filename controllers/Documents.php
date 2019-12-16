<?php namespace Waka\Publisher\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Documents Back-end Controller
 */
class Documents extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.ReorderController',
        'Waka.Informer.Behaviors.PopupInfo',

    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    //public $duplicateConfig = 'config_duplicate.yaml';
    //public $relationConfig = 'config_relation.yaml';

    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Waka.Publisher', 'publisher', 'side-menu-documents');
    }
    public function updatebloc($id)
    {
        $config = $this->makeConfig('$/waka/publisher/models/document/fields_rel_blocs.yaml');

        $config->model = \Waka\Publisher\Models\Document::find($id);

        trace_log($config->model->get());

        $widget = $this->makeWidget('Backend\Widgets\Form', $config);

        $this->vars['widget'] = $widget;

        $this->initForm($config->model);

    }
}
