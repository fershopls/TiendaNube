<?php

namespace lib\Module;

use lib\Handler\ModuleHandler;

abstract class Module {

    protected $dependencies = array();

    abstract public function getControllerClass();

    abstract public function getModuleRoutes();

    abstract public function getModuleAttributes();

    abstract public function getModuleDependencies();

    public function getHandlerClass()
    {
        return ModuleHandler::class;
    }

}