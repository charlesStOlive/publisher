<?php namespace Waka\Publisher\Classes\fields;

class MontageC extends BaseC
{
    public function __construct($model, $key, $config)
    {
        parent::__construct($model, $key, $config);
        $this->type = "image";
    }

    public function getValue()
    {
        $cfg = $this->config;
        $width = $cfg['width'] ?? "160";
        $height = $cfg['height'] ?? "160";
        $widthpx = $this->convertMmToPx($width);
        $heightpx = $this->convertMmToPx($height);
        $ratio = $cfg['ratio'] ?? true;
        trace_log("image path  = " . $this->model->{$this->key}->getPath());
        return [
            'path' => $this->model->getCloudiBaseUrl($this->key, 'jpg-' . $widthpx . '-' . $heightpx),
            'width' => $width . "mm",
            'height' => $height . "mm",
            'ratio' => $ratio,
        ];
        // return [
        //     'path' => $this->model->{$this->key}->getCloudiBaseUrl($this->key,'jpg-300-300'),
        //     'width' => "40mm",
        //     'height' => "40mm",
        //     'ratio' => true,
        // ];
    }
    public function convertMmToPx($value)
    {
        // en 96 dpi
        $value = $value * 3.8;
        return intval($value);
    }
}
