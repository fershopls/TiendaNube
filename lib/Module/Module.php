<?php

namespace lib\Module;

abstract class Module {

    protected $dependencies = array();

    abstract public function getControllerClass();

    abstract public function getModuleRoutes();

    abstract public function getModuleAttributes();

    abstract public function getModuleDependencies();

}