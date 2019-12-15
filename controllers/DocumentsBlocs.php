<?php namespace Waka\Publisher\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * DocumentsBlocsBlocsBlocs Back-end Controller
 */
class DocumentsBlocs extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.RelationController',
        'Waka.Publisher.Behaviors.BlocObjText',
        
    ];
    public $formConfig = 'config_form.yaml';
    /**
     * @var string Body CSS class to add to the layout.
     */
    public $bodyClass = 'compact-container';

    public $duplicateConfig = 'config_duplicate.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Waka.Publisher', 'publisher', 'side-menu-documents-blocsBlocsblocs');
        //$this->morphOneWidget = $this->createMorphOneFormWidget();
    }

    public function onCallInfoPopupBehavior() {
        trace_log('onCallInfoPopupBehavior');
        trace_log(post('model'));
        trace_log(post('id'));
        $modelName = post('model');
        $model = new $modelName;
        trace_log(get_class($model));
        $model = $model->find(post('id'));

        trace_log($model->name);
    }
    
}