<?php namespace Waka\Publisher\Behaviors;

use Backend\Classes\ControllerBehavior;
use October\Rain\Support\Collection;
use October\Rain\Exception\ApplicationException;
use Flash;

class BlocObjText extends ControllerBehavior
{
    protected $blocObjTextWidget;

	public function __construct($controller)
    {
        parent::__construct($controller);
        $this->blocObjTextWidget = $this->createBlocObjTextFormWidget();
    }


     //ci dessous tous les calculs pour permettre l'import excel. 
    public function onLoadBlocObjTextForm()
    {
        $blocModel = \Waka\Publisher\Models\Bloc::find(post('id'));
        $this->vars['blocModel'] = $blocModel;
        $this->vars['modelId'] = post('id');
        return ['#side-content' => $this->makePartial('$/waka/publisher/behaviors/blocobjtext/_my_form.htm')];
    }

    public function createBlocObjTextFormWidget() {

        $config = $this->makeConfig('$/waka/publisher/models/objtext/fields_for_bloc_mo.yaml');
        $config->alias = 'blocObjTextformWidget';

        $config->arrayName = 'blocObjText_array';
        //$config->redirect = $this->getConfig('redirect').':id';
        $config->model = new \Waka\Publisher\Models\BlocText;

        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();

        return $widget;
    }

    public function onBlocObjTextValidation(){
        $data = $this->duplicateWidget->getSaveData();

        Flash::info("Données enregistré");
        //return  Redirect::to($this->getConfig('redirect').$cloneModel->id);
        return true;

    }    
}