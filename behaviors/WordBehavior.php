<?php namespace Waka\Publisher\Behaviors;

use Backend\Classes\ControllerBehavior;
use October\Rain\Exception\ApplicationException;
use Waka\Publisher\Models\Document;
use Waka\Publisher\Classes\WordCreator;
use Flash;
use Lang;

class WordBehavior extends ControllerBehavior
{
    protected $wordBehaviorWidget;

	public function __construct($controller)
    {
        parent::__construct($controller);
        $this->wordBehaviorWidget = $this->createWordBehaviorWidget();
    }


     //ci dessous tous les calculs pour permettre l'import excel. 
    public function onLoadWordBehaviorForm()
    {
        $modelId = post('id');
        $this->vars['modelId'] = $modelId;
        $this->vars['wordBehaviorWidget'] = $this->createWordBehaviorWidget();
        //return $this->makePartial('$/waka/publisher/behaviors/wordbehavior/_my_form.htm');
        return true;
    }

    public function onTestWordValidation(){
        $id = post('modelId');
        trace_log($id);
        $wp = new WordCreator($id);
        trace_log($wp->readContent());
        return true;

    }
    public function createWordBehaviorWidget() {

        $config = $this->makeConfig('$/waka/publisher/models/document/fields_for_test.yaml');
        $config->alias = 'wordBehaviorformWidget';
        $config->arrayName = 'wordBehavior_array';
        $config->model = new Document();
        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();
        return $widget;
    }
}