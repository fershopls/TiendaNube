<?php

namespace lib\Handler;

use Phine\Path\Path;
use lib\Util\PlainText;

class ModuleHandler extends Handler {

    public function getDependencies ()
    {
        return array (
            'app',
            'plain_text' => PlainText::class,
        );
    }

    public function handle (\lib\Module\Module $module, \lib\Module\Controller $controller) {
        /** @var \lib\Util\PlainText $database */
        $database = $this->dependency('plain_text');

        $_path = $this->getPath($module);
        $_files = $this->availableFiles($_path);
        $_attributes = $this->getAttributesByPriority($module);

        foreach ($_files as $filename) {

            $_filename = Path::join([$_path,$filename]);

            if (is_dir($_filename) || $filename == self::MODULE_CONFIG_FILENAME)
                continue;

            $file_content = file_get_contents($_filename);
            $rows = $database->load($file_content)->toArray($_attributes);

            foreach ($rows as $row) {
                // TODO: Verify when `getModuleControllerAction` is called if method(action) actually exists.

                $action = $this->getModuleControllerAction($row['controller'], $module);


//                $_name = get_class($module);
//                $logger->pushProcessor(function($record) use ($action, $_name, $filename){
//                    $record['extra']['method'] = $action;
//                    $record['extra']['module'] = $_name;
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


            $this->makeMigration($module, $_filename);
        }
    }

    public function makeMigration($module, $from)
    {
        $dirMigration = Path::join([$this->getConfigSource('migration'), $this->getModuleName($module)]);
        $this->assureDirectory($dirMigration);
        $filename = date('Ymdhis_') . uniqid() . '.txt';
        $to = Path::join([$dirMigration, $filename]);
        $this->migrateFile($from, $to);
    }

}