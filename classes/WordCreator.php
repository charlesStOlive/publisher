<?php namespace Waka\Publisher\Classes;


use Waka\Publisher\Models\Content;
use October\Rain\Support\Collection;
use Storage;
use ApplicationException;
use AjaxException;
use Lang;
use Redirect;
use Flash;

Class WordCreator extends WordProcessor {

    private $dataSourceModel;
    private $dataSourceId;

    
    public function prepareCreatorVars($dataSourceId) {
        $this->dataSourceModel = $this->linkModelSource($dataSourceId);
        $this->apiBlocs = $this->getApiBlocs(); 
        $this->apiInjections = $this->getApiInjections();
    }
    private function linkModelSource($dataSourceId) {
        $this->dataSourceId = $dataSourceId;
        // si vide on puise dans le test
        if(!$this->dataSourceId) $this->dataSourceId = $this->document->data_source->test_id;
        //on enregistre le modèle
        return $this->document->data_source->modelClass::find($this->dataSourceId);
    }
    public function renderWord($dataSourceId) {
        $this->prepareCreatorVars($dataSourceId);
        $originalTags = $this->checkTags();
        if($this->errors()) {
            Flash::error(Lang::get('waka.publisher::lang.word.processor.errors'));
            return Redirect::back();
            
        }
        //Traitement des champs simples
        foreach($originalTags['injections'] as $injection) {
            $value = $this->apiInjections[$injection];
            $this->templateProcessor->setValue($injection, $value);
        }
        //Traitement des blocs | je n'utilise pas les tags d'origine mais les miens.
        foreach($this->apiBlocs as $key => $rows) {
            $count = count($rows);
            //trace_log("foreach---------------------------".$key.' count '.$count);
            $this->templateProcessor->cloneBlock($key, $count, true, true);
            $i=1;
            foreach($rows as $row) {
                // trace_log($row);
                //trace_log("--------foreachkey------------------------");
                foreach($row as $cle => $data) {
                    // trace_log($cle.'#'.$i);
                    // trace_log($data);
                    if($cle == 'image') {
                        $this->templateProcessor->setImageValue($cle.'#'.$i, $data, 1);
                    } else {
                        $this->templateProcessor->setValue($cle.'#'.$i, $data, 1);
                    }
                    
                }  
                $i++;
            }
        }
        $coin = $this->templateProcessor->saveAs('temp.docx');
        return response()->download('temp.docx')->deleteFileAfterSend(true);
    }

    public function getApiBlocs() {
        $doc = $this->document;
        $compiledBlocs = [];
        foreach($doc->blocs as $bloc) {
            $tag = $this->rebuildTag($bloc);
            $datas = $this->launchCompiler($bloc);
            $compiledBlocs[$tag] = $datas;
        }
        return $compiledBlocs;
    }

    public function getApiInjections() {
        return $this->document->data_source->listApi($this->dataSourceId);
    }

    private function rebuildTag($bloc) {
        $blocType = $bloc->bloc_type;
        $tag =  $blocType->type.'.'.$blocType->code.'.'.$bloc->code;
        return $tag;
    }

    private function selectBloc($content, $sector=null) {
        if(!$sector) return $content->whereNull('sector_id')->get();
        return 'not know';
    }

    private function launchCompiler($bloc) {
        $bloc_type = $bloc->bloc_type;
        // On garde uniquement le bon secteur;
        $content = $this->selectBloc($bloc->contents());
        //A partir du champs compiler de bloc_type on cherche la classe qui gère le bloc en question.
        $compiler = new $bloc_type->compiler;
        return $compiler->proceed($content, $this->dataSourceModel);        
    }
}
