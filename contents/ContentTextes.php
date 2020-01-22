<?php namespace Waka\Publisher\Contents;

use Backend\Classes\ControllerBehavior;

class ContentTextes extends ControllerBehavior
{
    public $contentTextesFormWidget;
	public function __construct($controller)
    {
        parent::__construct($controller);
        $this->contentTextesFormWidget = $this->createContentTextesFormWidget();
    }
    

    public function onLoadCreateTextesForm()
    {
        $bloc = $this->controller->getBlocModel();

        $this->contentTextesFormWidget->context = post('context');

        $this->vars['behaviorWidget'] = $this->contentTextesFormWidget;
        $this->vars['orderId'] = post('manage_id');
        $this->vars['update'] = false;
        
        return [
            '#popupPublisherContent' =>$this->makePartial('$/waka/publisher/contents/form/_content_create_form.htm')
        ]; 
    }
    //
    public function onLoadUpdateTextesForm()
    {
        $bloc = $this->controller->getBlocModel();
        //
        $recordId = post('record_id');
        $sk = post('_session_key');

        $this->contentTextesFormWidget = $this->createContentTextesFormWidget($recordId);
        $this->contentTextesFormWidget->context = post('context');
       
        //
        $this->vars['behaviorWidget'] = $this->contentTextesFormWidget;
        //
        $this->vars['orderId'] = post('manage_id');
        $this->vars['recordId'] = $recordId;
        $this->vars['update'] = true;
        //
        return [
            '#popupPublisherContent' =>$this->makePartial('$/waka/publisher/contents/form/_content_create_form.htm')
        ];
    }

    protected function createContentTextesFormWidget($recordId=null)
    {
        $config = $this->makeConfig('$/waka/publisher/contents/compilers/textes.yaml');
        $config->alias = 'contentTextesForm';
        $config->arrayName = 'textesForm';

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