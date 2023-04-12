<?php

namespace Jabe\Impl;

use Jabe\History\HistoricProcessInstanceReportInterface;
use Jabe\Impl\Interceptor\{
    CommandInterface,
    CommandContext
};

class HistoricTaskInstanceCountByNameCmd implements CommandInterface
{
    private $scope;

    public function __construct(HistoricProcessInstanceReportInterface $scope)
    {
        $this->scope = $scope;
    }

    public function execute(CommandContext $commandContext, ...$args)
    {
        return $this->scope->executeCountByTaskName($commandContext);
    }

    public function isRetryable(): bool
    {
        return false;
    }
}
