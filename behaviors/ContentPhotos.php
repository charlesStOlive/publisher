<?php namespace Waka\Publisher\Behaviors;

use Backend\Classes\ControllerBehavior;

class ContentPhotos extends ControllerBehavior
{
    public $contentPhotosFormWidget;
	public function __construct($controller)
    {
        parent::__construct($controller);
        $this->contentPhotosFormWidget = $this->createContentPhotosFormWidget();
    }
    

    public function onLoadCreatePhotosForm()
    {
        $bloc = $this->controller->getBlocModel();

        $this->contentPhotosFormWidget->context = post('context');

        $this->vars['behaviorWidget'] = $this->contentPhotosFormWidget;
        $this->vars['orderId'] = post('manage_id');
        $this->vars['update'] = false;
        
        return $this->makePartial('$/waka/publisher/controllers/documents/_content_create_form.htm');
    }
    //
    public function onLoadUpdatePhotosForm()
    {
        $bloc = $this->controller->getBlocModel();
        //
        $recordId = post('record_id');
        $sk = post('_session_key');

        $this->contentPhotosFormWidget = $this->createContentPhotosFormWidget($recordId);
        $this->contentPhotosFormWidget->context = post('context');
       
        //
        $this->vars['behaviorWidget'] = $this->contentPhotosFormWidget;
        //
        $this->vars['orderId'] = post('manage_id');
        $this->vars['recordId'] = $recordId;
        $this->vars['update'] = true;
        //
        return $this->makePartial('$/waka/publisher/controllers/documents/_content_create_form.htm');
    }

    protected function createContentPhotosFormWidget($recordId=null)
    {
        $config = $this->makeConfig('$/waka/publisher/models/yaml/photos.yaml');
        $config->alias = 'contentPhotosForm';
        $config->arrayName = 'photosForm';

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