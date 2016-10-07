<?php

namespace lib\App;

// Core
use lib\Util\Settings;
use lib\App\Injector;
// Dependencies
use lib\App\Api;

class App {

    protected $settings;
    protected $injector;

    protected $modules = array();
    protected $dependencies = array();

    public function __construct($settings)
    {
        // Settings
        $this->settings = new Settings($settings);

        // Dependencies
        $this->injector = new Injector();
        $this->injector->memory('app', $this);
        $this->dependencies = $this->injector->solve($this->getDependencies());
    }

    public function getDependencies ()
    {
        return array(
            'api' => Api::class,
            'app'
        );
    }

    public function getDependency ($index)
    {
        return isset($this->dependencies[$index])?$this->dependencies[$index]:false;
    }

    public function register ($classModule)
    {
        if (class_exists($classModule))
        {
            $this->modules[$classModule] = new $classModule;
        } else {
            // Todo: error
        }
    }

    public function run ()
    {
        /* @var \lib\Module\Module $module */
        foreach ($this->modules as $module)
        {
            $_dependencies = $this->injector->solve($module->getModuleDependencies());
            $_controller = $module->getControllerClass();
            $_controller = new $_controller;

            $_controller->injectDependencies ($_dependencies);

            call_user_func_array([$_controller, 'run'], []);
            // Handler

        }
    }

}