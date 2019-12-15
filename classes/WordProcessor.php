<?php namespace Waka\Publisher\Classes;

use \PhpOffice\PhpWord\TemplateProcessor;
use Waka\Publisher\Models\Document;
use Waka\Publisher\Models\BlocType;
use Waka\Publisher\Models\Bloc;

use Storage;
use ApplicationException;
use October\Rain\Support\Collection;
use AjaxException;

Class WordProcessor {

    public static $document_id;
    public static $document;
    public static $bloc_types;
    public static $AllBlocs;
    public static $increment;


    public static function prepareVars($document_id) {
        self::$increment = 1;
        self::$document_id = $document_id;
        self::$bloc_types = BlocType::get(['id', 'code']);
        self::$AllBlocs = Bloc::get(['id', 'document_id', 'code', 'name']);
        self::$document = Document::find($document_id);
        
    }

    /**
     * 
     */
    public static function checkTags($id, $context='null') {
        self::prepareVars($id);
        $document_path = self::getPath(self::$document);
        $templateProcessor = new TemplateProcessor($document_path);
        //
        $tagsCollection = self::filterTags($templateProcessor->getVariables());
        //
        self::$document->analyze = implode("\n", $tagsCollection->get('error'));
        //
        if($context == 'create') self::$document->save();
        //
        $create = self::createBlocs($tagsCollection->get('blocs'));
        //
        return $tagsCollection;
    }
    /**
     * 
     */
    public static function filterTags($tags) {
        $filteredTag = new Collection();
        $blocs = [];
        $uniqueValue = [];
        $logInfo = ['Log chargement du document'];

        //permet de savoir si nous sommes dans un bloc 
        $insideBlock = false; 
        $blocType = null;
        $bloccode = null;
        foreach($tags as $tag) {
            // Si un / est détécté c'est une fin de bloc. on enregistre pas ce bloc.
            if(starts_with($tag, '/'))  {
                $insideBlock = false;
                continue;
            }
            // si on est dans un bloc on recherche  une value ou un path. 
            if($insideBlock) {
                array_push($logInfo, $blocType.".".$blocCode." à le tag : ".$tag);
                continue;
               
            }
            $parts = explode('.', $tag);
            if(count($parts)<=1) {
                array_push($logInfo, "Un code est mal formaté il ne sera pas pris en compte :  ".$tag);
                continue;
            } 
            $blocFormat = array_shift($parts);
            $blocType = array_shift($parts);
            $blocCode = implode( ".", $parts ); 
            if($blocFormat == 'bloc' or $blocFormat == 'raw') {
                if(!$blocType || !$blocCode ) {
                    array_push($logInfo, "Un bloc est mal formaté il ne sera pas pris en compte :  ".$tag);
                    continue;
                }
                if(!self::returnBlocTypeId($blocType)) {
                    array_push($logInfo, "le code  : ".$blocType."n'existe pas dans l'application il ne sera pas pris en compte bloc : ".$tag);
                    continue;
                }
                // on commence un bloc
                $insideBlock = true;
                $obj = (object)['format' => $blocFormat, 'type' => $blocType, 'code' => $blocCode ];
                array_push($blocs,$obj);
                array_push($logInfo, "Nouveau bloc détécté ".$blocFormat.'.'.$blocType.'.'.$blocCode);
            } else {
                array_push($logInfo, "Valeur unique ".$tag);
                array_push($uniqueValue,$tag);
            };
        }
        // Traduction du bloc :
        $filteredTag->put('error',$logInfo );
        $filteredTag->put('blocs',$blocs );
        $filteredTag->put('uniques',$uniqueValue );

        return $filteredTag;
    }
    /**
     * 
     */
    public static function createBLocs($blocs) {
        trace_log($blocs);
        if(count(self::$document->blocs) == 0) {
            trace_log("il n' a pas encore de bloc");
            foreach($blocs as $bloc) {
                self::createBloc($bloc); 
            } 
        } else {
            // Bloc existe ? 
            foreach($blocs as $bloc) {
                $blocModel =  self::returnBlocModel($bloc);
                if($blocModel) trace_log($blocModel);
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
        trace_log($bloc->toArray());
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
            trace_log($blocType->code.' = '.$name);
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
