<?php

namespace vaino78\JiraUtil\Command;

use JiraRestApi\Configuration\ArrayConfiguration;
use JiraRestApi\Configuration\ConfigurationInterface;
use JiraRestApi\Issue\IssueService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use vaino78\JiraUtil\AppException;

abstract class AbstractCommand extends Command
{
    const OPTION_JIRA_SERVER = 'server';

    const OPTION_JIRA_USER = 'user';

    const OPTION_JIRA_PASSWORD = 'pass';

    /** @var string */
    protected $jiraServer;

    /** @var string */
    protected $jiraUser;

    /** @var string */
    protected $jiraUserPass;

    /** @var IssueService */
    protected $issueService;

    /** @inheritdoc */
    protected function configure()
    {
        parent::configure();

        $this
            ->addJiraServerOption()
            ->addJiraUserOption()
            ->addJiraPasswordOption();
    }

    /** @inheritdoc */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->initJiraOptions($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initJiraOptions(InputInterface $input, OutputInterface $output)
    {
        $this->jiraServer = $this->getJiraServerOption($input);

        $this->jiraUser = $this->getJiraUserOption($input);

        $this->jiraUserPass = $this->getJiraPasswordOption($input);
    }

    /**
     * @param InputInterface $input
     * @return string|null
     */
    protected function getJiraServerOption(InputInterface $input)
    {
        return $input->getOption(static::OPTION_JIRA_SERVER);
    }

    /**
     * @param InputInterface $input
     * @return string|null
     */
    protected function getJiraUserOption(InputInterface $input)
    {
        return $input->getOption(static::OPTION_JIRA_USER);
    }

    /**
     * @param InputInterface $input
     * @return string|null
     */
    protected function getJiraPasswordOption(InputInterface $input)
    {
        return $input->getOption(static::OPTION_JIRA_PASSWORD);
    }

    /**
     * @return $this
     */
    protected function addJiraServerOption()
    {
        $this->addOption(
            static::OPTION_JIRA_SERVER,
            's',
            InputOption::VALUE_REQUIRED,
            'JIRA server url'
        );

        return $this;
    }

    /**
     * @return $this
     */
    protected function addJiraUserOption()
    {
        return $this->addOption(
            static::OPTION_JIRA_USER,
            'u',
            InputOption::VALUE_REQUIRED,
            'JIRA user name'
        );
    }

    /**
     * @return $this
     */
    protected function addJiraPasswordOption()
    {
        return $this->addOption(
            static::OPTION_JIRA_PASSWORD,
            'p',
            InputOption::VALUE_REQUIRED,
            'JIRA user password'
        );
    }

    /**
     * @param ConfigurationInterface|null $configuration
     * @return IssueService
     * @throws AppException
     * @throws \JiraRestApi\JiraException
     */
    protected function getJiraIssueService(ConfigurationInterface $configuration = null): IssueService
    {
        if(empty($this->issueService)) {
            $this->issueService = $this->createJiraIssueService($configuration);
        }

        return $this->issueService;
    }

    /**
     * @param ConfigurationInterface|null $configuration
     * @return IssueService
     * @throws AppException
     * @throws \JiraRestApi\JiraException
     */
    protected function createJiraIssueService(ConfigurationInterface $configuration = null): IssueService
    {
        if(empty($configuration)) {
            $configuration = $this->getJiraApiConfiguration();
        }

        return new IssueService($configuration);
    }

    /**
     * @return ConfigurationInterface
     * @throws AppException
     */
    protected function getJiraApiConfiguration(): ConfigurationInterface
    {
        if(empty($this->jiraServer)) {
            throw new AppException('Can not determine JIRA server');
        }

        if(empty($this->jiraUser)) {
            throw new AppException('Can not determine JIRA user');
        }

        if(empty($this->jiraUserPass)) {
            throw new AppException('Can not determine JIRA user pass');
        }

        return new ArrayConfiguration(array(
            'jiraHost' => $this->jiraServer,
            'jiraUser' => $this->jiraUser,
            'jiraPassword' => $this->jiraUserPass
        ));
    }
}
