<?php

namespace Jabe\Impl\Cmd;

use Jabe\History\UserOperationLogEntryInterface;
use Jabe\Impl\Persistence\Entity\{
    ExternalTaskEntity,
    PropertyChange
};
use Jabe\Impl\Util\EnsureUtil;

class SetExternalTaskRetriesCmd extends ExternalTaskCmd
{
    protected int $retries = 0;
    protected $writeUserOperationLog;

    public function __construct(?string $externalTaskId, int $retries, bool $writeUserOperationLog)
    {
        parent::__construct($externalTaskId);
        $this->retries = $retries;
        $this->writeUserOperationLog = $writeUserOperationLog;
    }

    protected function validateInput(): void
    {
        EnsureUtil::ensureGreaterThanOrEqual("The number of retries cannot be negative", "retries", $this->retries, 0);
    }

    protected function executeTask(ExternalTaskEntity $externalTask)
    {
        $externalTask->setRetriesAndManageIncidents($this->retries);
    }

    protected function getUserOperationLogOperationType(): ?string
    {
        if ($this->writeUserOperationLog) {
            return UserOperationLogEntryInterface::OPERATION_TYPE_SET_EXTERNAL_TASK_RETRIES;
        }
        return parent::getUserOperationLogOperationType();
    }

    protected function getUserOperationLogPropertyChanges(ExternalTaskEntity $externalTask): array
    {
        if ($this->writeUserOperationLog) {
            return [new PropertyChange("retries", $externalTask->getRetries(), $this->retries)];
        }
        return parent::getUserOperationLogPropertyChanges($externalTask);
    }
}
