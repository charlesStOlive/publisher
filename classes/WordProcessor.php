<?php namespace Waka\Publisher\Classes;

use \PhpOffice\PhpWord\TemplateProcessor;
use Waka\Publisher\Models\Document;
use Waka\Publisher\Models\BlocType;

use Storage;
use ApplicationException;
use October\Rain\Support\Collection;
use AjaxException;

Class WordProcessor {

    public static $id;

    /**
     * 
     */
    public static function checkTags($id, $context='null') {
        $document = Document::find($id);
        $document_path = self::getPath($document);
        $templateProcessor = new TemplateProcessor($document_path);
        $tagsCollection = self::filterTags($templateProcessor->getVariables(),$id);
        //
        $document->analyze = implode("\n", $tagsCollection->get('error'));
        if($context == 'create') $document->save();
        //

        return $tagsCollection;
    }
    /**
     * 
     */
    public static function filterTags($tags, $id) {
        $filteredTag = new Collection();
        $blocs = [];
        $appCode = BlocType::get()->toArray();
        trace_log($appCode);
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
            $blocType = array_shift($parts);
            $blocCode = array_shift($parts); 
            if($blocType == 'bloc' or $blocCode == 'raw') {
                // on commence un bloc
                $insideBlock = true;
                $obj = (object)['type' => $blocType, 'code' => $blocCode];
                array_push($blocs,$obj);
                
                array_push($logInfo, "Nouveau bloc détécté ".$blocType.'.'.$blocCode);
            } else {
                array_push($logInfo, "Valeur unique ".$tag);
            };
        }
        $filteredTag->put('error',$logInfo );
        $filteredTag->put('blocs',$blocs );

        return $filteredTag;
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
