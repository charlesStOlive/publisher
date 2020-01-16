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

    use \Waka\Cloudis\Classes\Traits\CloudisKey;

    
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
        //Traitement des champs imagesKey
        foreach($originalTags['imagekeys'] as $imagekey) {
            $tag = $this->getWordImageKey($imagekey);
            $key = $this->cleanWordKey($tag);
            $url = $this->decryptKeyedImage($key, $this->dataSourceModel);
            $this->templateProcessor->setImageValue($tag, $url);
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
        $name = str_slug($this->document->name.'-'.$this->dataSourceModel->name);
        $coin = $this->templateProcessor->saveAs($name.'.docx');
        return response()->download($name.'.docx')->deleteFileAfterSend(true);
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

    private function selectBloc($content, $sector) {
        //trace_log("select bloc");
        $arrayContent = $content->get()->toArray();
        if(count($arrayContent)==1) {
            //trace_log("Il n' y a pas de variante");
            return $content->get();
        }
        if(count($arrayContent)>1) {
            // trace_log("tratiement des variantes");
            // trace_log("liste des contenus");
            // trace_log($content->get()->toArray());
            // trace_log('sector ID searched : '.$sector->id);
            // trace_log("content get id");
            // trace_log($content->get(['sector_id'])->toArray());
            $tempSector = $sector->findParentIds($content->get(['sector_id']));
            //trace_log('tempSector : '.$tempSector);
            if(!$tempSector) {
                return $content->whereNull('sector_id')->get();
            } else {
                //trace_log("on a trouvé un contenu liée");
                return $content->where('sector_id', '=',  $tempSector->id)->get();
            }
        } else {
            //trace_log("Error ? ");
            return $content->get();
        }
        
    }
    private function launchCompiler($bloc) {
        $bloc_type = $bloc->bloc_type;
        //
        $sector = $this->getModelSectorAccess($this->dataSourceModel, $this->document->data_source->sector_access);
        // On garde uniquement le bon secteur;
        $content = $this->selectBloc($bloc->contents(), $sector);
        // trace_log("133 content");
        // trace_log($content->toArray());
        //A partir du champs compiler de bloc_type on cherche la classe qui gère le bloc en question.
        $compiler = new $bloc_type->compiler;
        return $compiler->proceed($content, $this->dataSourceModel);        
    }
    private function getModelSectorAccess($model, $dotString) {
        if(!$dotString) return $model;
        $parts = explode('.', $dotString);
        if(count($parts) == 1) return $model[$dotString];
        if(count($parts) == 2) return $model[$parts[0]][$parts[1]];
        if(count($parts) == 3) return $model[$parts[0]][$parts[1]][$parts[2]];
        if(count($parts) == 4) return $model[$parts[0]][$parts[1]][$parts[2]][$parts[3]];
    }
}
