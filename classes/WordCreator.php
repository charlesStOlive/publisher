<?php namespace Waka\Publisher\Classes;

use \PhpOffice\PhpWord\TemplateProcessor;
use Waka\Publisher\Models\Document;
use Waka\Publisher\Models\Content;

use Storage;
use ApplicationException;
use AjaxException;
use October\Rain\Support\Collection;

Class WordCreator {

    private $id;
    private $document;
    private $templatePocessor;
    public $sector;
    public $apiBlocs;
    public $apiInjections;
    public $originalTags;


    function __construct($id, $targetId=null)
    {
        $this->id = $id;
        $this->targetId = $targetId;
        $this->createProcessor($id);
    }

    private function createProcessor() {
        $this->document = Document::find($this->id);
        $path = $this->getPath($this->document);
        $this->templateProcessor = new TemplateProcessor($path);
    }
    public function prepareVars() {
        $this->apiBlocs = $this->getApiBlocs(); 
        $this->apiInjections = $this->getApiInjections();
    }
    public function renderWord($originalTags) {
        $this->prepareVars();
        //Traitement des champs simples
        foreach($originalTags['injections'] as $injection) {
            $value = $this->apiInjections[$injection];
            trace_log($injection." = ".$value);
            $this->templateProcessor->setValue($injection, $value);
        }
        //Traitement des blocs | je n'utilise pas les tags d'origine mais les miens.
        foreach($this->apiBlocs as $key => $rows) {
            $count = count($rows);
            trace_log($count);
            trace_log($key);
            trace_log($rows);
            trace_log("foreach---------------------------");
            $this->templateProcessor->cloneBlock($key, $count, true);
            foreach($rows as $row) {
                trace_log($row);
                trace_log("--------foreachkey------------------------");
                foreach($row as $cle => $data) {
                    trace_log($cle);
                    trace_log($data);
                    if($cle == 'image') {
                        $this->templateProcessor->setImageValue($cle, $data, 1);
                    } else {
                        $this->templateProcessor->setValue($cle, $data, 1);
                    }
                }  
            }
        }
        $coin = $this->templateProcessor->saveAs('temp.docx');
        trace_log("ready to return");
        return response()->download('temp.docx')->deleteFileAfterSend(true);
    }

    public function testWord() {
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/media/template_textes_photos.docx'));
        $templateProcessor->setValue('name', 'John');
        $templateProcessor->setValue('surname', 'Doe');
        $templateProcessor->saveAs('temp.docx');
        trace_log("c est la merde");
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
        return $this->document->data_source->listApi();
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
        return $compiler::proceed($content);        
    }
    


    
    /**
     * 
     */
    private function returnBlocModel($_bloc) {
        trace_log('returnBlocModel');
        $type_id = self::returnBlocTypeId($_bloc->type);
        $blocExiste = Bloc::where('document_id', '=' ,self::$document_id)
                            ->where('code','=', $_bloc->code)
                            ->where('bloc_type_id','=', $type_id)->first();
        return $blocExiste;
    }
    /**
     * 
     */
    private function returnBlocTypeId($name) {
        $blocTypes= self::$bloc_types;
        $id = null;
        foreach($blocTypes as $blocType ) {
            trace_log($blocType->code.' = '.$name);
            if($blocType->code == $name ) $id = $blocType->id; 
        }
        return $id;
    }
    /**
     * 
     */
    private function getPath($document) {
        if(!isset($document)) throw new ApplicationException("L'id du document n'existe pas");
        $existe = Storage::exists('media'. $document->path);
        if(!$existe) throw new ApplicationException("Le document n'existe pas");
        return storage_path('app/media'. $document->path);
    }
}
