<?php

namespace lib\Module;

abstract class Controller {

    protected $dependencies = array();

    public function injectDependencies ($arrayDependencies)
    {
        $this->dependencies = $arrayDependencies;
    }

    public function dependency ($index)
    {
        // Todo: error
        return isset($this->dependencies[$index])?$this->dependencies[$index]:false;
    }

}