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

    //public $duplicateConfig = 'config_duplicate.yaml';
    public $relationConfig = 'config_relation.yaml';
    //
    public $morphOneWidget;

    public $targetModel;
    public $modelMOrphOneWidget;
    public $confiigMorphOneWidget;

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Waka.Publisher', 'publisher', 'side-menu-documents-blocsBlocsblocs');
        //$this->morphOneWidget = $this->createMorphOneFormWidget();
    }

    public function onLoadMorphOneForm()
    {
        //$this->modelParent = $this->GetModel('modelClass');

        $blocModel = \Waka\Publisher\Models\Bloc::find(post('id'));
        $this->vars['blocModel'] = $blocModel;
        //
        trace_log($blocModel->bloc_type->model);
        //
        $targetModel = new $blocModel->bloc_type->model;
        
        trace_log($targetModel->find(1)->toArray());
        //$model = $model->find($this->params[0]);
        return [
            '#side-content' => $this->makePartial('morphone_form')
        ];   
    }

    public function createMorphOneFormWidget() {
        
        // $configMorhOneForm = new Collection($this->getConfig('duplication'));
        // //opération pour retourver l'objet fields
        // // !! attention l'objet field doit être en dernier !
        // $configDuplication = $configDuplication->take(-1)->toArray();

        $config = $this->makeConfig('$/waka/publisher/models/bloctexte/fields_rel_blocs.yaml');
        $config->alias = 'morphOneformWidget';

        $config->arrayName = 'morphone_array';
        //$config->redirect = $this->getConfig('redirect').':id';
        $config->model = new $targetModel;

        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();

        return $widget;
    }
    public function onMorphSave() {

        // $data = $this->duplicateprojectWidget->getSaveData();
        // $manageId = post('manage_id');
        // $model_to_clone =   \Dom\Crm\Models\Project::find($manageId);

        // if($person->location) {
        //     return $person->location()->update($location);
        // }
        // return $person->location()->create($location);

    }
    

}
