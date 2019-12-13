<?php namespace Waka\Publisher\Behaviors;

use Backend\Classes\ControllerBehavior;
use October\Rain\Support\Collection;
use October\Rain\Exception\ApplicationException;
use Waka\Publisher\Models\ObjText;
use Waka\Publisher\Models\Bloc;
use Flash;

class BlocObjText extends ControllerBehavior
{
    protected $blocObjTextWidget;
    protected $model;

	public function __construct($controller)
    {
        parent::__construct($controller);
        $this->blocObjTextWidget = $this->createBlocObjTextFormWidget();
    }


     //ci dessous tous les calculs pour permettre l'import excel. 
    public function onLoadBlocObjTextForm()
    {
        $model = Bloc::find(post('id'));
        if($model->obj) {
            $this->blocObjTextWidget->config->model = ObjText::find($model->obj->id);
            $fields = $this->blocObjTextWidget->fields;
            foreach($fields as $field) {
                //$field->defaults = '[{"value":"xxxxxx","jump":"1"}]';
                trace_log($field);
            }
            //trace_log($this->blocObjTextWidget->config->model->data);

        }
        //$this->vars['model'] = $model;
        $this->vars['modelId'] = post('id');
        trace_log($this->blocObjTextWidget->config);
        return ['#side-content' => $this->makePartial('$/waka/publisher/behaviors/blocobjtext/_my_form.htm')];
    }

    public function createBlocObjTextFormWidget() {

        $config = $this->makeConfig('$/waka/publisher/models/objtext/fields_for_bloc_mo.yaml');
        $config->alias = 'blocObjTextformWidget';

        $config->arrayName = 'blocObjText_array';
        //$config->redirect = $this->getConfig('redirect').':id';
        $config->model = new ObjText;

        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();

        return $widget;
    }

    public function onBlocObjTextValidation(){
        $saveData = $this->blocObjTextWidget->getSaveData();


        $id = post('id');
        $model = \Waka\Publisher\Models\Bloc::find($id);
        
        

        if($model->obj) {
            $modelObjText = ObjText::find($model->obj->id);
            $modelObjText->data = $saveData['data'];
            $modelObjText->save();
        } else {
            $modelObjText = new ObjText;
            $modelObjText->data = $saveData['data'];
            $modelObjText->save();
            $modelObjText->bloc()->save($model);
        }

        //$association = $model->obj;

        
        
        //Create a new Tag instance (fill the array with your own database fields)

        //In the tag relationship, save a new video

        Flash::info("Données enregistré");
        //return  Redirect::to($this->getConfig('redirect').$cloneModel->id);
        return true;

    }    
}