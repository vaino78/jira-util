<?php

namespace vaino78\JiraUtil\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Worklog extends AbstractCommand
{
    protected $limit;

    protected $projects;

    protected function configure()
    {
        parent::configure();

        $this
            ->setName('worklog')
            ->setDescription('')
            ->addOption(
                'limit',
                null,
                InputOption::VALUE_REQUIRED,
                'Transaction issues limit',
                1000
            )
            ->addOption(
                'project',
                'p',
                (InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY),
                'Project key'
            )
            ->addOption(
                'jql',
                'q',
                InputOption::VALUE_REQUIRED,
                'JQL to select issues'
            );
    }

    /** @inheritdoc */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->limit = $input->getOption('limit');
    }

    /** @inheritdoc */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }

    protected function getIssuesIterator(): \Iterator
    {
        
    }
}
