<?php namespace Waka\Publisher\Classes\fields;

class FileC extends BaseC
{
    public function __construct($model, $key, $config)
    {
        parent::__construct($model, $key, $config);
        $this->type = "image";
    }

    public function getValue()
    {
        $cfg = $this->config;
        $width = $cfg['width'] ?? "160mm";
        $height = $cfg['height'] ?? "160mm";
        $ratio = $cfg['ratio'] ?? true;
        trace_log("image path  = " . $this->model->{$this->key}->getPath());
        return [
            'path' => $this->model->{$this->key}->getPath(),
            'width' => $width,
            'height' => $height,
            'ratio' => $ratio,
        ];
        // return [
        //     'path' => $this->model->{$this->key}->getCloudiBaseUrl($this->key,'jpg-300-300'),
        //     'width' => "40mm",
        //     'height' => "40mm",
        //     'ratio' => true,
        // ];
    }
}
