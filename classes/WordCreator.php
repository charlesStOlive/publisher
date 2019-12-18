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
    public $wordCollection;


    function __construct($id)
    {
        $this->id = $id;
        $this->createProcessor($id);
        $this->wordCollection = new Collection();
    }

    private function createProcessor() {
        $this->document = Document::find($this->id);
        $path = $this->getPath($this->document);
        $this->templateProcessor = new TemplateProcessor($path);
    }

    public function readContent() {
        $doc = $this->document;
        foreach($doc->blocs as $bloc) {
            $tag = $this->rebuildTag($bloc);
            $datas = $this->launchCompiler($bloc);
            $this->wordCollection->put($tag , $datas);
        }
        trace_log($this->wordCollection);
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
