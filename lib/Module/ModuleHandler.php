<?php

namespace lib\Module;

use lib\Module\Module;
use lib\Module\Controller;
use lib\App\Injector;

use Phine\Path\Path;
use lib\Util\PlainText;

class ModuleHandler {

    protected $injector;
    protected $dependencies = array();

    const MODULE_CONFIG_FILENAME = 'config.json';

    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
        $this->dependencies = $this->injector->solve($this->getDependencies());
    }

    public function getDependencies ()
    {
        return array (
            'app',
            'plain_text' => PlainText::class,
        );
    }

    public function dependency ($index)
    {
        return isset($this->dependencies[$index])?$this->dependencies[$index]:false;
    }

    public function handle (Module $entity, Controller $controller) {
        /** @var \lib\Util\PlainText $database */
        /** @var \lib\App\Api $api */
        $database = $this->dependency('plain_text');
        $api = $this->dependency('api');

        $_path = $this->getPath($entity);
        $_files = $this->getAvailableFiles($_path);
        $_attributes = $this->getAttributesByPriority($entity);

        foreach ($_files as $filename) {

            $_filename = Path::join([$_path,$filename]);

            if (is_dir($_filename) || $filename == self::MODULE_CONFIG_FILENAME)
                continue;

            $file_content = file_get_contents($_filename);
            $rows = $database->load($file_content)->toArray($_attributes);

            foreach ($rows as $row) {
                // TODO: Verify when `getModuleControllerAction` is called if method(action) actually exists.

                $action = $this->getModuleControllerAction($row['controller'], $entity);

                $_name = get_class($entity);

//                $logger->pushProcessor(function($record) use ($action, $_name, $filename){
//                    $record['extra']['method'] = $action;
//                    $record['extra']['module'] = $entity_name;
//                    $record['extra']['file_path'] = $filename;
//                    return $record;
//                });

                if ($action)
                {
                    // Todo: error
                    // $logger->error("Action(method) uncallable.", ['method'=>$action, 'row_controller'=>$row['controller']]);
                    if (method_exists($controller, $action))
                    {
                        $args = array($row);
                        call_user_func_array([$controller, $action], $args);
                    }
                }

//                $logger->popProcessor();
            }

            # $this->migrateModuleFile($entity_path, $filename);
        }
    }


    public function getPrefixPath ()
    {
        return Path::join([APP_PATH, 'support']);
    }

    public function getPath (Module $module)
    {
        // Todo: error
        $_name = explode("\\", get_class($module));
        $_name = strtolower($_name[count($_name) -1]);
        $_path = $this->dependency('app')->set()->get('sources.'.$_name, false);
        return preg_replace("/^%/i", $this->getPrefixPath(), $_path);
    }

    public function getAvailableFiles ($stringPath)
    {
        $available_files = scandir($stringPath);
        array_shift($available_files);array_shift($available_files);
        return $available_files;
    }

    public function getAttributesByPriority (Module $entity) {
        $_path = $this->getPath($entity);
        $_file_config = Path::join([$_path, self::MODULE_CONFIG_FILENAME]);
        $_attributes = $entity->getModuleAttributes();

        if (file_exists($_file_config))
            $_attributes = array_merge($_attributes, json_decode(file_get_contents($_file_config), True));
        else
            file_put_contents($_file_config, json_encode($_attributes, JSON_PRETTY_PRINT));

        return $_attributes;
    }

    public function getModuleControllerAction ($stringController, Module $entity) {
        // Todo: error
        $action = array_search($stringController, $entity->getModuleRoutes());
        if (!$action)
            return False;
        return "do" . preg_replace("/\s/", "", ucwords(preg_replace("/[\-\_]/i", " ", $action)));
    }

}