<?php namespace Waka\Publisher\Classes;

use ApplicationException;
use stdClass;
use Waka\Publisher\Classes\Fields\FileC;
use Waka\Publisher\Classes\Fields\MontageC;
use Waka\Publisher\Classes\Fields\ValueC;

class ContentParser
{
    protected $fieldsType;
    public $error;
    private $model;
    private $modelCollection;

    public function __construct()
    {
        $this->fieldsType = [
            'value',
            'media',
            'file',
            'montage',
        ];
    }
    public function setModel($src, $model, $relation)
    {
        if ($src && $relation) {
            new ApplicationException("Il y a un model et une relation ce n'est pas possible");
        }

        if ($src) {
            trace_log("ContentParser : la source n est pas une relation");
            $this->modelCollection = $src::get();
        }

        if ($model && $relation) {
            trace_log("ContentParser : source est une relation");
            $this->modelCollection = $model[$relation];
        }
    }
    public function parseFields($fields)
    {
        trace_log("fields condif in parseFields");
        trace_log($fields);
        $datas = [];
        if (!$this->modelCollection) {
            new ApplicationException("Le modèle n'a pas été correctement initialisé ");
        }
        foreach ($this->modelCollection as $rowModel) {

            $returnObject = new stdClass();
            // trace_log("Model à lire");
            // trace_log($model->toArray());

            foreach ($fields as $key => $config) {
                $type = $config['type'] ?? 'value';
                //trace_log("type de '.$key.' : " . $type);
                if (!in_array($type, $this->fieldsType)) {
                    throw new ApplicationException("le type " . $type . " n' existe pas");
                }
                $fieldToReturn;
                switch ($type) {
                    case 'value':
                        $fieldToReturn = new ValueC($rowModel, $key, $config);
                        break;
                    // case 'media':
                    //     $val = new MediaC($this->model, $key, $config);
                    //     $fieldToReturn = $val->getValue();
                    //     break;
                    case 'file':
                        $fieldToReturn = new FileC($rowModel, $key, $config);
                        break;
                    case 'montage':
                        $fieldToReturn = new MontageC($rowModel, $key, $config);
                        break;
                }
                $objKey = $fieldToReturn->getKey();
                $objValue = $fieldToReturn->getValue();
                $returnObject->{$objKey} = $objValue;

            }
            array_push($datas, $returnObject);

        }
        trace_log("datas return from parser");
        trace_log($datas);
        return $datas;
    }

}
