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
        'Backend.Behaviors.RelationController',

    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    //public $duplicateConfig = 'config_duplicate.yaml';
    public $relationConfig = 'config_relation.yaml';

    public $reorderConfig = 'config_reorder.yaml';
    public $contextContent;

    //protected $itemFormWidget;
    protected $contentFormWidget;

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Waka.Publisher', 'publisher', 'side-menu-documents');

        $this->contentFormWidget = $this->createContentFormWidget();
        
    }
    public function onLoadCreateContentForm()
    {
        $model = $this->getBlocModel();

        $this->contentFormWidget->context = $this->getContentContext();

        $this->vars['contentFormWidget'] = $this->contentFormWidget;
        
        $this->vars['orderId'] = post('manage_id');
        $this->vars['update'] = false;
        
        return $this->makePartial('content_create_form');
    }
    //
    public function onLoadUpdateContentForm()
    {
        $recordId = post('record_id');
        $sk = post('_session_key');

        $this->contentFormWidget = $this->createContentFormWidget($recordId);
        $this->contentFormWidget->context = $this->getContentContext($recordId);
        //
        $this->vars['contentFormWidget'] = $this->contentFormWidget;
        //
        $this->vars['orderId'] = post('manage_id');
        $this->vars['recordId'] = $recordId;
        $this->vars['update'] = true;
        //
        return $this->makePartial('content_create_form');
    }

    public function onCreateItem()
    {
        $data = post('Content');
        $sk = post('_session_key');

        $model = new \Waka\Publisher\Models\Content;
        $model->fill($data);
        $model->save();

        $bloc = $this->getBlocModel();
        $bloc->contents()->add($model, $sk);

        return $this->refreshOrderItemList($sk);
    }

    public function onUpdateContent()
    {
        $recordId = post('record_id');
        $data = post('Content');
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
        $contents = $this->getBlocModel()
            ->contents()
            ->withDeferred($sk)
            ->get()
        ;

        $this->vars['contents'] = $contents;

        return ['#contentList' => $this->makePartial('content_list')];
    }

    protected function getBlocModel()
    {
        $manageId = post('manage_id');

        $bloc = $manageId
            ? \Waka\Publisher\Models\Bloc::find($manageId)
            : new \Waka\Publisher\Models\Bloc;

        return $bloc;
    }

    
    protected function getContentContext($recordId=null)
    {
        $model = $this->getBlocModel();
        $context = null;
        if(!$recordId) {
            if(count($model->contents)>0) {
                $context = "addVersion";
            } else {
                $context = "createBase";
            }
        } else {
            $content = \Waka\Publisher\Models\Content::find($recordId);
            if($content->sector) {
                $context = "updateVersion";
            } else {
                $context = "updateBase";
            }

        }
        return $context;
    }

    protected function createContentFormWidget($recordId=null)
    {
        $config = $this->makeConfig('$/waka/publisher/models/content/fields.yaml');
        $config->alias = 'contentForm';
        $config->arrayName = 'Content';

        if(!$recordId) {
            $config->model = new \Waka\Publisher\Models\Content;
        } else {
            $config->model = \Waka\Publisher\Models\Content::find($recordId);
        }

        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();

        return $widget;
    }
}
