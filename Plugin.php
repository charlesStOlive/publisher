<?php namespace Waka\Publisher;

use Backend;
use Lang;
use System\Classes\PluginBase;
use Event;
use View;

/**
 * Publisher Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Publisher',
            'description' => 'No description provided yet...',
            'author'      => 'Waka',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    public function registerFormWidgets(): array
    {
        return [
            'Waka\Publisher\FormWidgets\ShowAttributes' => 'showattributes',
        ];
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        Event::listen('backend.down.update', function($controller) {
            if(get_class($controller) == 'Waka\Publisher\Controllers\Documents') return;
            if(in_array('Waka.Publisher.Behaviors.WordBehavior', $controller->implement )) {
                $data = [
                    'model' => $modelClass = str_replace('\\', '\\\\', get_class($controller->formGetModel())),
                    'modelId' => $controller->formGetModel()->id
                ];
                return View::make('waka.publisher::publishWord')->withData($data);;
            }
        });
        Event::listen('popup.actions.line1', function($controller, $model, $id) {
            if(get_class($controller) == 'Waka\Publisher\Controllers\Documents') return;
            if(in_array('Waka.Publisher.Behaviors.WordBehavior', $controller->implement)) {
                $data = [
                    'model' => str_replace('\\', '\\\\', $model),
                    'modelId' => $id
                ];
                return View::make('waka.publisher::publishWordContent')->withData($data);;
            }
        });

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Waka\Publisher\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'waka.publisher.some_permission' => [
                'tab' => 'Publisher',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return[];

    }
    public function registerSettings()
    {
        return [
            'documents' => [
                'label'       => Lang::get('waka.publisher::lang.menu.documents'),
                'description' => Lang::get('waka.publisher::lang.menu.documents_description'),
                'category'    => Lang::get('waka.publisher::lang.menu.settings_category'),
                'icon'        => 'icon-file-word-o',
                'url'         => Backend::url('waka/publisher/documents'),
                'order'       => 1,
            ],
            'bloc_types' => [
                'label'       => Lang::get('waka.publisher::lang.menu.bloc_type'),
                'description' => Lang::get('waka.publisher::lang.menu.bloc_type_description'),
                'category'    => Lang::get('waka.publisher::lang.menu.settings_category'),
                'icon'        => 'icon-th-large',
                'url'         => Backend::url('waka/publisher/bloctypes'),
                'order'       => 1,
            ]
        ];
    }
}
