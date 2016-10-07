<?php

namespace lib\App;

class Injector {

    protected $memory = array();

    public function solve ($arrayDependencies)
    {
        $instanced = array();

        foreach ($arrayDependencies as $index => $object)
        {
            if (!class_exists($object) && $this->memory($object))
            {
                $instanced[$object] = $this->memory($object);
            } else {
                $instanced[$index] = new $object;
            }
        }
        return $instanced;
    }

    public function memory ($index, $object = null)
    {
        if ($object)
        {
            $this->memory[$index] = $object;
        }
        return isset($this->memory[$index])?$this->memory[$index]:false;
    }

}