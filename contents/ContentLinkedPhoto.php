<?php namespace Waka\Publisher\Contents;

use Backend\Classes\ControllerBehavior;
use Session;

class ContentLinkedPhoto extends ControllerBehavior
{
    public $contentLinkedPhotoFormWidget;
	public function __construct($controller)
    {
        parent::__construct($controller);
        $this->contentLinkedPhotoFormWidget = $this->createContentLinkedPhotoFormWidget();
    }
    

    public function onLoadCreateLinkedPhotoForm()
    {
        $bloc = $this->controller->getBlocModel();

        $this->contentLinkedPhotoFormWidget->context = post('context');

        $this->vars['behaviorWidget'] = $this->contentLinkedPhotoFormWidget;
        $this->vars['orderId'] = post('manage_id');
        Session::put('manage_bloc_id_for_mediaList', post('manage_id'));
        $this->vars['update'] = false;
        
        return [
            '#popupPublisherContent' =>$this->makePartial('$/waka/publisher/contents/form/_content_create_form.htm')
        ];
    }
    //
    public function onLoadUpdateLinkedPhotoForm()
    {
        $bloc = $this->controller->getBlocModel();
        //
        $recordId = post('record_id');
        $sk = post('_session_key');

        $this->contentLinkedPhotoFormWidget = $this->createContentLinkedPhotoFormWidget($recordId);
        $this->contentLinkedPhotoFormWidget->context = post('context');
       
        //
        $this->vars['behaviorWidget'] = $this->contentLinkedPhotoFormWidget;
        //
        $this->vars['orderId'] = post('manage_id');
        Session::put('manage_bloc_id_for_mediaList', post('manage_id'));
        $this->vars['recordId'] = $recordId;
        $this->vars['update'] = true;
        //
        return [
            '#popupPublisherContent' =>$this->makePartial('$/waka/publisher/contents/form/_content_create_form.htm')
        ];
    }

    protected function createContentLinkedPhotoFormWidget($recordId=null)
    {
        $config = $this->makeConfig('$/waka/publisher/contents/compilers/linkedphoto.yaml');
        $config->alias = 'contentLinkedPhotoForm';
        $config->arrayName = 'linkedPhotoForm';

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