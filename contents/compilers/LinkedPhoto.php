<?php namespace Waka\Publisher\Contents\Compilers;

use stdClass;

class LinkedPhoto
{
    use \Waka\Cloudis\Classes\Traits\CloudisKey;


    public function proceed($contents, $dataSourceModel) {
        
        // On conserve la même procedure que pour les autres en array bien que la valeur soit unique. cela simplifie le traitement
        $datas= [];
        foreach($contents as $content ) {
            $obj = new stdClass();
            trace_log("CONTENT");
            trace_log($content->data);
            trace_log("END CONTENT");
            // ici content_data nous retourne une clé crypté qui embarque from, key et type de média
            $url = $this->decryptKeyedImage($content->data['image'], $dataSourceModel);
            $obj->image = [
                'path' => $url,
                'width' => $content->data['width'].'cm',
                'height' => $content->data['height'].'cm',
                'ratio' => true
            ];
            array_push($datas, $obj);
        }
        return $datas;
    }

    
}