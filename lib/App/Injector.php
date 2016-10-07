<?php

namespace lib\App;

class Injector {

    protected $memory = array();

    public function solve ($arrayDependencies)
    {
        $instanced = array();

        foreach ($arrayDependencies as $index => $required)
        {
            $id = $index;
            $object = $required;

            if (is_string($required) && $this->memory($required))
            {
                $id = $required;
                $object = $this->memory($required);
            }

            if (!is_object($object) && class_exists($object))
                $object = new $object;

            $instanced[$id] = $object;
        }
        return $instanced;
    }

    public function memory ($index, $object = null)
    {
        // Todo: error
        if ($object)
        {
            $this->memory[$index] = $object;
        }
        return isset($this->memory[$index])?$this->memory[$index]:false;
    }

}