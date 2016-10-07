<?php

namespace lib\Handler;

class Handler {

    // Properties
    protected $handlerDependencies = array();
    // Objects
    protected $app;
    protected $controller;
    protected $dependencies = array();

    public function getHandlerDependencies () {
        return $this->handlerDependencies;
    }

    public function setDependencies ($dependencies = []) {
        if (is_array($dependencies))
            $this->dependencies = array_merge($this->dependencies, $dependencies);
    }

    public function dependency ($index) {
        return isset($this->dependencies[$index])?$this->dependencies[$index]:$this->dependencies;
    }

    public function controller ($instance = null) {
        if ($instance and is_object($instance))
            $this->controller = $instance;
        else
            return $this->controller;
    }

}