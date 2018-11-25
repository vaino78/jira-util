<?php

namespace vaino78\JiraUtil;

use Symfony\Component\Console\Application;

class App extends Application
{
    const NAME = 'JIRA Util';
    const VERSION = '0.1';

    /** @inheritdoc */
    public function __construct($name = '', $version = '')
    {
        if(empty($name)) {
            $name = static::NAME;
        }
        if(empty($version)) {
            $version = static::VERSION;
        }

        parent::__construct($name, $version);

        $this->initAppCommands();
    }

    protected function initAppCommands()
    {
        $this->addCommands(array(
            new Command\Worklog()
        ));
    }
}
