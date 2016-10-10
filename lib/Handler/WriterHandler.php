<?php

namespace lib\Handler;

class WriterHandler extends Handler {

    public function getDependencies()
    {
        return array (
            'app',
        );
    }

    public function handle (\lib\Module\Module $module, \lib\Module\Controller $controller)
    {
        $args = array($module, $this->getPath($module));
        $module_action_method = $this->getModuleControllerAction('run', $module);
        call_user_func_array([$controller, $module_action_method], $args);
    }

}