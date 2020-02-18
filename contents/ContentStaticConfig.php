<?php namespace Waka\Publisher\Contents;

use Backend\Classes\ControllerBehavior;

class ContentStaticConfig extends ControllerBehavior
{
    public $contentStaticConfigFormWidget;
	public function __construct($controller)
    {
        parent::__construct($controller);
        $this->contentStaticConfigFormWidget = $this->createContentStaticConfigFormWidget();
    }
    

    public function onLoadCreateStaticConfigForm()
    {
        $bloc = $this->controller->getBlocModel();

        $this->contentStaticConfigFormWidget->context = post('context');

        $this->vars['behaviorWidget'] = $this->contentStaticConfigFormWidget;
        $this->vars['orderId'] = post('manage_id');
        $this->vars['update'] = false;
        
        return [
            '#popupPublisherContent' =>$this->makePartial('$/waka/publisher/contents/form/_content_create_form.htm')
        ]; 
    }
    //
    public function onLoadUpdateStaticConfigForm()
    {
        $bloc = $this->controller->getBlocModel();
        //
        $recordId = post('record_id');
        $sk = post('_session_key');

        $this->contentStaticConfigFormWidget = $this->createContentStaticConfigFormWidget($recordId);
        $this->contentStaticConfigFormWidget->context = post('context');
       
        //
        $this->vars['behaviorWidget'] = $this->contentStaticConfigFormWidget;
        //
        $this->vars['orderId'] = post('manage_id');
        $this->vars['recordId'] = $recordId;
        $this->vars['update'] = true;
        //
        return [
            '#popupPublisherContent' =>$this->makePartial('$/waka/publisher/contents/form/_content_create_form.htm')
        ]; 
    }

    protected function createContentStaticConfigFormWidget($recordId=null)
    {
        $config = $this->makeConfig('$/waka/publisher/contents/compilers/staticconfig.yaml');
        $config->alias = 'contentStaticConfigForm';
        $config->arrayName = 'StaticConfigForm';

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