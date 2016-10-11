<?php

namespace lib\Handler;

use lib\App\Injector;
use Phine\Path\Path;

abstract class Handler {

    const MODULE_CONFIG_FILENAME = 'config.json';

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

    public function getPath (\lib\Module\Module $module)
    {
        // Todo: error
        $_name = $this->getModuleName($module);
        return $this->getConfigSource($_name);
    }

    public function getModuleName (\lib\Module\Module $module)
    {
        $_name = explode("\\", get_class($module));
        return strtolower($_name[count($_name) -1]);
    }

    public function getConfigSource ($id)
    {
        $_path = $this->dependency('app')->set()->get('sources.'.$id, false);
        return $this->dependency('app')->getPath($_path);
    }

    public function getAttributesByPriority (\lib\Module\Module $module) {
        $_path = $this->getPath($module);
        $_file_config = Path::join([$_path, self::MODULE_CONFIG_FILENAME]);
        $_attributes = $module->getModuleAttributes();

        if (file_exists($_file_config))
            $_attributes = array_merge($_attributes, json_decode(file_get_contents($_file_config), True));
        else
            file_put_contents($_file_config, json_encode($_attributes, JSON_PRETTY_PRINT));

        return $_attributes;
    }

    public function getModuleControllerAction ($stringController, \lib\Module\Module $module) {
        // Todo: error
        $action = array_search($stringController, $module->getModuleRoutes());
        if (!$action)
            return False;
        return "do" . preg_replace("/\s/", "", ucwords(preg_replace("/[\-\_]/i", " ", $action)));
    }

    public function migrateFile($from, $to)
    {
        // TODO: Create migration log with files on every upload, delete, etc.
        // Todo: Validar que el archivo se ha leido correctamente por el modulo
        // TODO: Create a module who will take care about keep organized by month all the migration directory modules
        rename($from, $to);
        if (file_exists(realpath($to)))
            return True;
        return False;
    }

    public function assureDirectory ($stringPath)
    {
        if (!file_exists($stringPath)) {
            mkdir($stringPath,null,true);
            if (!file_exists($stringPath))
                return False;
        }
        return True;
    }

}