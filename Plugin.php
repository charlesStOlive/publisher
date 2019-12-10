<?php namespace Waka\Publisher;

use Backend;
use Lang;
use System\Classes\PluginBase;

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

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

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

        return [
            'publisher' => [
                'label'       => Lang::get('waka.publisher::lang.menu.title'),
                'url'         => Backend::url('waka/publisher/blocnames'),
                'icon'        => 'icon-leaf',
                'permissions' => ['waka.publisher.*'],
                'order'       => 500,
                'sideMenu' => [
                    'side-menu-bloc-name' => [
                        'label'       => Lang::get('waka.publisher::lang.menu.bloc_name'),
                        'icon'        => 'icon-building',
                        'url'         => Backend::url('waka/publisher/blocnames'),
                    ],
                    'side-menu-bloc-type' => [
                        'label'       => Lang::get('waka.publisher::lang.menu.bloc_type'),
                        'icon'        => 'icon-users',
                        'url'         => Backend::url('waka/publisher/bloctypes'),
                    ],
                ]
                
            ],
            
        ];
    }
}