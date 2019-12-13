<?php namespace Waka\Publisher\Behaviors;

use Backend\Classes\ControllerBehavior;
use October\Rain\Exception\ApplicationException;
use Waka\Publisher\Models\ObjText;
use Waka\Publisher\Models\Bloc;
use Flash;
use Lang;

class BlocObjText extends ControllerBehavior
{
    protected $blocObjTextWidget;

	public function __construct($controller)
    {
        parent::__construct($controller);
        $this->blocObjTextWidget = $this->createBlocObjTextWidget();
    }


     //ci dessous tous les calculs pour permettre l'import excel. 
    public function onLoadBlocObjTextForm()
    {
        $modelId = post('id');
        $this->vars['modelId'] = $modelId;
        $model = Bloc::find($modelId);
        //
        $objId=null;
        if($model->obj) $objId=$model->obj->id; 
        $this->vars['objId'] = $objId;
        //
        $this->vars['blocObjTextWidget'] = $this->createBlocObjTextWidget($objId);
        return ['#side-content' => $this->makePartial('$/waka/publisher/behaviors/blocobjtext/_my_form.htm')];
    }

    public function createBlocObjTextWidget($objId=null) {

        $config = $this->makeConfig('$/waka/publisher/models/objtext/fields_for_bloc_mo.yaml');
        $config->alias = 'blocObjTextformWidget';
        $config->arrayName = 'blocObjText_array';
        if($objId) {
            $config->model = ObjText::find($objId);
        } else {
            $config->model = new ObjText();
        }
        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();
        return $widget;
    }

    public function onBlocObjTextValidation(){
        $objId = post('objId');
        $saveData = $this->blocObjTextWidget->getSaveData();
        $model = Bloc::find(post('modelId'));
        //
        if($model->obj) {
            $obj = ObjText::find($model->obj->id);
            $obj->data = $saveData['data'];
            $obj->save();
        } else {
            $obj = new ObjText;
            $obj->data = $saveData['data'];
            $obj->save();
            $obj->bloc()->save($model);
        }
        Flash::success(Lang::get('waka.utils::lang.global.save_success'));
        return true;

    }    
}