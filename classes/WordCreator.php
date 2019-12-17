<?php namespace Waka\Publisher\Classes;

use \PhpOffice\PhpWord\TemplateProcessor;
use Waka\Publisher\Models\Document;
use Waka\Publisher\Models\Content;

use Storage;
use ApplicationException;
use AjaxException;

Class WordCreator {

    private $id;
    private $document;
    private $templatePocessor;

    function __construct($id)
    {
        $this->id = $id;
        $this->createProcessor($id);
    }

    private function createProcessor() {
        $this->document = Document::find($this->id);
        $path = $this->getPath($this->document);
        $this->templateProcessor = new TemplateProcessor($path);
    }
    public function readContent() {
        $doc = $this->document;
        $blocs = $doc->blocs()->with('content')->toArray();
        return $blocs;
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
