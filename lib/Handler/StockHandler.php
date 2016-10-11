<?php
/**
 * Created by PhpStorm.
 * User: FershoPls
 * Date: 10/9/2016
 * Time: 6:47 PM
 */

namespace lib\Handler;

use lib\Util\PlainText;
use Phine\Path\Path;

class StockHandler extends Handler {

    public function getDependencies()
    {
        return array (
            'app',
            'plain_text' => PlainText::class,
        );
    }

    public function handle (\lib\Module\Module $module, \lib\Module\Controller $controller)
    {
        /** @var \lib\Util\PlainText $database */
        $database = $this->dependency('plain_text');


        $module_path = $this->getPath($module);
        $module_files = $this->availableFiles($module_path);
        $module_attributes = $this->getAttributesByPriority($module);

        foreach ($module_files as $module_action_time) {

            $module_action_time = Path::join([$module_path, $module_action_time]);

            if (!is_dir($module_action_time) || $module_action_time == self::MODULE_CONFIG_FILENAME)
                continue;
            
            foreach ($this->availableFiles($module_action_time) as $module_action_dir)
            {
                $module_action_method = $this->getModuleControllerAction($module_action_dir, $module);

                $module_action_files = $this->availableFiles(Path::join([$module_action_time, $module_action_dir]));

                // Todo: error
                // $logger->error("Action(method) uncallable.", ['method'=>$action, 'row_controller'=>$row['controller']]);
                if (!method_exists($controller, $module_action_method))
                    continue;

                foreach ($module_action_files as $action_file)
                {
                    $_action_file_path = Path::join([$module_action_time, $module_action_dir, $action_file]);
                    $_action_file_content = file_get_contents($_action_file_path);

                    $rows = $database->load($_action_file_content)->toArray($module_attributes);
                    foreach ($rows as $row)
                    {
                        $args = array($row);
                        call_user_func_array([$controller, $module_action_method], $args);
                    }
                }

            }

            // Todo: migrate
            $this->makeMigration($module, $module_action_time);
        }
    }

    public function makeMigration($module, $from)
    {
        $dirMigration = Path::join([$this->getConfigSource('migration'), $this->getModuleName($module)]);
        $this->assureDirectory($dirMigration);
        $dirname = date('Ymdhis_') . uniqid();
        $to = Path::join([$dirMigration, $dirname]);
        $this->migrateFile($from, $to);
    }

}