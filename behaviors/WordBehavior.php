<?php namespace Waka\Publisher\Behaviors;

use Backend\Classes\ControllerBehavior;
use October\Rain\Exception\ApplicationException;
use Waka\Publisher\Models\Document;
use Waka\Publisher\Classes\WordCreator;
use Waka\Publisher\Classes\WordProcessor;
use Flash;
use Lang;
use Redirect;

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
        $id = post('id');
        //
        $tags = $this->checkWord($id);  
        //
        
        // $this->vars['modelId'] = $id;
        // $this->vars['wordBehaviorWidget'] = $this->createWordBehaviorWidget();
        // return $this->makePartial('$/waka/publisher/behaviors/wordbehavior/_my_form.htm');
        //return true;
        return Redirect::to('/backend/waka/publisher/documents/makeword/'.$id);
    }

    public function makeword($id){
        $tags = $this->checkWord($id);
        $wc = new WordCreator($id);
        return $wc->renderWord($tags);   
    }

    public function CheckWord($id){
        $returnTag = WordProcessor::checkTags($id);
        $model = Document::find($id);
        if($model->has_informs('problem')) {
            Flash::error('Le document à des erreurs');
            return Redirect::refresh();
        } else {
            foreach($model->blocs as $bloc) {
                if($bloc->has_informs('problem')) {
                    Flash::error('Le document à des erreurs');
                    return Redirect::refresh();
                }
            }

        }
        return $returnTag;
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