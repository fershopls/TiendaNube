<?php

namespace lib\Handler;

use lib\App\Injector;

abstract class Handler {

    protected $injector;
    protected $dependencies = array();
    
    abstract public function getDependencies ();

    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
        $this->dependencies = $this->injector->solve($this->getDependencies());
    }

    public function dependency ($index)
    {
        return isset($this->dependencies[$index])?$this->dependencies[$index]:false;
    }

    public function availableFiles ($stringPath)
    {
        $available_files = scandir($stringPath);
        array_shift($available_files);array_shift($available_files);
        return $available_files;
    }

}