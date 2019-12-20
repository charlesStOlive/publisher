<?php namespace Waka\Publisher\Classes;

use \PhpOffice\PhpWord\TemplateProcessor;
use Waka\Publisher\Models\Document;
use Waka\Publisher\Models\BlocType;
use Waka\Publisher\Models\Bloc;
use Waka\Informer\Models\Inform;

use Storage;
use ApplicationException;
use AjaxException;

Class WordProcessor {

    public static $document_id;
    public static $document;
    public static $bloc_types;
    public static $AllBlocs;
    public static $increment;
    public static $blocFormatAccepted;
    public static $dataSourceName;


    public static function prepareVars($document_id) {
        self::$increment = 1;
        self::$document_id = $document_id;
        self::$bloc_types = BlocType::get(['id', 'code']);
        self::$AllBlocs = Bloc::get(['id', 'document_id', 'code', 'name']);
        
        $document = Document::find($document_id);
        self::$document = $document;
        // tous les champs qui ne sont pas des blocs ou des raw devront avoir ce type
        self::$dataSourceName = snake_case($document->data_source->model);
        self::$blocFormatAccepted = ['raw', 'bloc', self::$dataSourceName];
    }

    /**
     * 
     */
    public static function checkTags($id, $context='null') {
        self::prepareVars($id);
        $document_path = self::getPath(self::$document);
        $templateProcessor = new TemplateProcessor($document_path);
        //
        $allTags = self::filterTags($templateProcessor->getVariables());
        // 
        if($context == 'create') self::$document->save();
        //
        $create = self::createBlocs($allTags['blocs']);
        //
        return $allTags;
    }
    /**
     * 
     */
    public static function filterTags($tags) {
        self::$document->delete_informs();
        $blocs = [];
        $injections = [];
        $insideBlock = false; 
        foreach($tags as $tag) {
            // Si un / est détécté c'est une fin de bloc. on enregistre pas ce bloc.
            if(starts_with($tag, '/'))  {
                $insideBlock = false;
                continue;
            }
            // si on est dans un bloc on recherche  une value ou un path. 
            if($insideBlock) {
                //array_push($logInfo, $blocType.".".$blocCode." à le tag : ".$tag);
                continue;
               
            }
            $parts = explode('.', $tag);
            if(count($parts)<=1) {
                self::$document->record_inform('problem', 'code mal formaté, non utilisé : '.$tag );
                continue;
            } 
            $blocFormat = array_shift($parts);
            $blocType = array_shift($parts);
            $blocCode = implode( ".", $parts ); 
            //self::$document->record_inform('temp', $tag );
            if(!in_array($blocFormat, self::$blocFormatAccepted)) {
                trace_log("Pas in array");
                self::$document->record_inform('problem', 'les tags de CE document doivent commencer par : '.implode(", ", self::$blocFormatAccepted).' => '.$tag );
                continue;
            }
            if($blocFormat == self::$dataSourceName) {
                $tagOK = self::checkInjection($tag);
                if($tagOK) array_push($injections,$tag);
                continue;
            } 
            if(!$blocType || !$blocCode ) {
                self::$document->record_inform('warning', 'code mal formaté, non utilisé : '.$tag );
                continue;
            }
            if(!self::returnBlocTypeId($blocType)) {
                self::$document->record_inform('warning', 'type de bloc inexistant : '.$tag );
                continue;
            }
            // on commence un bloc
            $insideBlock = true;
            $obj = (object)['format' => $blocFormat, 'type' => $blocType, 'code' => $blocCode ];
            array_push($blocs,$obj);
        }

        return [
            'blocs' =>$blocs,
            'injections' =>$injections
        ];
    }
    /**
     * 
     */
    public static function checkInjection($tag) {
        $ModelVarArray = self::$document->data_source->listApi();
        if(!array_key_exists($tag, $ModelVarArray)) {
            self::$document->record_inform('problem', "Cette information n'existe pas : ".$tag );
            return false;
        } else {
            return true;
        }
    }
    /**
     * 
     */
    public static function createBLocs($blocs) {
        if(count(self::$document->blocs) == 0) {
            trace_log("il n' a pas encore de bloc");
            foreach($blocs as $bloc) {
                self::createBloc($bloc); 
            } 
        } else {
            // Bloc existe ? 
            foreach($blocs as $bloc) {
                $blocModel =  self::returnBlocModel($bloc);
                if(!$blocModel) {
                    self::createBloc($bloc);
                } else {
                    $blocModel->ready = 'ok';
                    $blocModel->save();
                }
            } 
        }
    }

    public static function createBloc($_bloc) {
        $bloc = new Bloc();
        $bloc->code = $_bloc->code;
        

        $bloc->bloc_type =  BlocType::find(self::returnBlocTypeId($_bloc->type));
        $bloc->name = $_bloc->code.' '.self::$increment++;
        $bloc->ready = 'ok';
        //trace_log($bloc->toArray());
        self::$document->blocs()->add($bloc);
    }

    public static function returnBlocModel($_bloc) {
        trace_log('returnBlocModel');
        $type_id = self::returnBlocTypeId($_bloc->type);
        $blocExiste = Bloc::where('document_id', '=' ,self::$document_id)
                            ->where('code','=', $_bloc->code)
                            ->where('bloc_type_id','=', $type_id)->first();
        return $blocExiste;
    }



    public static function returnBlocTypeId($name) {
        $blocTypes= self::$bloc_types;
        $id = null;
        foreach($blocTypes as $blocType ) {
            if($blocType->code == $name ) $id = $blocType->id; 
        }
        return $id;
    }
    /**
     * 
     */
    public static function getPath($document) {
        if(!isset($document)) throw new ApplicationException("L'id du document n'existe pas");
        $existe = Storage::exists('media'. $document->path);
        if(!$existe) throw new ApplicationException("Le document n'existe pas");
        return storage_path('app/media'. $document->path);
    }
}
