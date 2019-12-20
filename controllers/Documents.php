<?php namespace Waka\Publisher\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;
use Yaml;
use File;

/**
 * Documents Back-end Controller
 */
class Documents extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.ReorderController',
        'Backend.Behaviors.RelationController',
        'Waka.Informer.Behaviors.PopupInfo',
        'Waka.Publisher.Behaviors.WordBehavior',
        'Waka.Publisher.Behaviors.ContentTextes',
        'Waka.Publisher.Behaviors.ContentPhotos',

    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    //public $duplicateConfig = 'config_duplicate.yaml';
    public $relationConfig = 'config_relation.yaml';

    public $reorderConfig = 'config_reorder.yaml';
    public $contextContent;

    public function __construct()
    {
        parent::__construct();

        //BackendMenu::setContext('Waka.Publisher', 'publisher', 'side-menu-documents');
        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('Waka.Publisher', 'documents');
        
    }

    public function onTestList() {
        $model = \Waka\Publisher\Models\Document::find($this->params[0]);
        trace_log($model->data_source->listApi());
    }


    // public function relationExtendRefreshResults($field)
    // {
    //     $blocs = \Waka\Publisher\Models\Document::find($this->params[0])->blocs;
    //     foreach($blocs as $bloc) {
    //         trace_log($bloc->name);
    //         trace_log(count($bloc->contents));
    //         if(count($bloc->contents)) {
    //             $bloc->delete_informs();
    //         } else {
    //         }
    //     }
    // } 
    

    public function onCreateItem()
    {
        $bloc = $this->getBlocModel();

        $data = post($bloc->bloc_type->code.'Form');
        $sk = post('_session_key');
        $bloc->delete_informs();

        $model = new \Waka\Publisher\Models\Content;
        $model->fill($data);
        $model->save();

        $bloc->contents()->add($model, $sk);

        return $this->refreshOrderItemList($sk);
    }

    public function onUpdateContent()
    {
        $bloc = $this->getBlocModel();

        $recordId = post('record_id');
        $data = post($bloc->bloc_type->code.'Form');
        $sk = post('_session_key');

        $model = \Waka\Publisher\Models\Content::find($recordId);
        $model->fill($data);
        $model->save();

        return $this->refreshOrderItemList($sk);
    }

    

    public function onDeleteItem()
    {
        $recordId = post('record_id');
        $sk = post('_session_key');

        $model = \Waka\Publisher\Models\Content::find($recordId);

        $bloc = $this->getBlocModel();
        $bloc->contents()->remove($model, $sk);

        return $this->refreshOrderItemList($sk);
    }

    protected function refreshOrderItemList($sk)
    {
        $bloc = $this->getBlocModel();
        $contents = $bloc->contents()->withDeferred($sk)->get();

        $this->vars['contents'] = $contents;
        $this->vars['bloc_type'] = $bloc->bloc_type;
        return [
            '#contentList' => $this->makePartial('content_list')
        ];
    }

    public function getBlocModel()
    {
        $manageId = post('manage_id');
        

        $bloc = $manageId
            ? \Waka\Publisher\Models\Bloc::find($manageId)
            : new \Waka\Publisher\Models\Bloc;

        return $bloc;
    }

    
    // public function getContentContext($recordId=null)
    // {
    //     $model = $this->getBlocModel();
    //     $context = null;
    //     if(!$recordId) {
    //         $contents = $model->contents()->withDeferred($sk)->get();
    //         if(count($contents)>0) {
    //             $context = "addVersion";
    //         } else {
    //             $context = "createBase";
    //         }
    //     } else {
    //         $content = \Waka\Publisher\Models\Content::find($recordId);
    //         if($content->sector) {
    //             $context = "updateVersion";
    //         } else {
    //             $context = "updateBase";
    //         }

    //     }
    //     trace_log("getContentContext : ".$context);
    //     return $context;
    // }
}
