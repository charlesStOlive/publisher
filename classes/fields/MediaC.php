<?php namespace Waka\Publisher\Classes\fields;

class MediaC
{
    public function __construct($model, $key, $config)
    {
        $result = $model[$key] ?? 'error MediaC';
        return ['image.' . $key => $result];
    }
}
