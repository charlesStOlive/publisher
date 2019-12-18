<?php namespace Waka\Publisher\Classes\Compiler;

use stdClass;
use ToughDeveloper\ImageResizer\Classes\Image as ImageResizer;
use Storage;


Class Photos {

    public static $elem;


    public static function proceed($contents) {
        $datas= [];
        $index = 1;
        foreach($contents as $content ) {
            foreach($content->data as $sub) {
                $obj = new stdClass();
                $obj->value = $sub['value'];
                $obj->images = [
                    'path' => self::getImage($sub['path']),
                    'widfth' => '16cm',
                    'heignt' => '7cm',
                    'ratio' => true
                ];
                array_push($datas, $obj);
                if($index != count($contents)) {
                    $jump = new stdClass();
                    $jump->value = ' ';
                    $jump->path = null;  
                    array_push($datas, $jump);
                } else {
                    $index++;
                }
            }
        }
        return $datas;
    }

    public static function getImage($value) {
        if(!isset($value)) throw new ApplicationException("L'image n'est pas rensigné : ".$value);
        $existe = Storage::exists('media'. $value);
        if(!$existe) throw new ApplicationException("L'image n'existe pas");
        //$image = new ImageResizer(storage_path('app/media'. $value));
        //$image->resize(150, 200, [ 'mode' => 'crop' ]);
        return storage_path('app/media'. $value);
    }
}