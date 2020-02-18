<?php namespace Waka\Publisher\Classes\fields;

class BaseC
{
    protected $model;
    protected $key;
    protected $config;
    protected $type;
    protected $error;

    public function __construct($model, $key, $config)
    {
        $this->model = $model;
        $this->key = $key;
        $this->config = $config;
        $this->type = "value";
        $this->error = $config['label_error'] ?? "Inc";

    }

    public function getKey()
    {
        return $this->type . "." . $this->key;

    }

    public function getValue()
    {
        return $this->model[$this->key] ?? $this->error;

    }

}
