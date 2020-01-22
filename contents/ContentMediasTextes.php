<?php namespace Waka\Publisher\Contents;

use Backend\Classes\ControllerBehavior;

class ContentMediasTextes extends ControllerBehavior
{
    public $contentMediasTextesFormWidget;
	public function __construct($controller)
    {
        parent::__construct($controller);
        $this->contentMediasTextesFormWidget = $this->createContentMediasTextesFormWidget();
    }
    

    public function onLoadCreateMediasTextesForm()
    {
        $bloc = $this->controller->getBlocModel();

        $this->contentMediasTextesFormWidget->context = post('context');

        $this->vars['behaviorWidget'] = $this->contentMediasTextesFormWidget;
        $this->vars['orderId'] = post('manage_id');
        $this->vars['update'] = false;

        return [
            '#popupPublisherContent' =>$this->makePartial('$/waka/publisher/contents/form/_content_create_form.htm')
        ];
    }
    //
    public function onLoadUpdateMediasTextesForm()
    {
        $bloc = $this->controller->getBlocModel();
        //
        $recordId = post('record_id');
        $sk = post('_session_key');

        $this->contentMediasTextesFormWidget = $this->createContentMediasTextesFormWidget($recordId);
        $this->contentMediasTextesFormWidget->context = post('context');
       
        //
        $this->vars['behaviorWidget'] = $this->contentMediasTextesFormWidget;
        //
        $this->vars['orderId'] = post('manage_id');
        $this->vars['recordId'] = $recordId;
        $this->vars['update'] = true;
        //
        return [
            '#popupPublisherContent' =>$this->makePartial('$/waka/publisher/contents/form/_content_create_form.htm')
        ];
    }

    protected function createContentMediasTextesFormWidget($recordId=null)
    {
        $config = $this->makeConfig('$/waka/publisher/contents/compilers/mediastextes.yaml');
        $config->alias = 'contentMediasTextesForm';
        $config->arrayName = 'mediasTextesForm';

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