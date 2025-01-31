<?php

namespace Jabe\Impl\Cmd;

use Jabe\EntityTypes;
use Jabe\Exception\{
    NotFoundException,
    NotValidException
};
use Jabe\History\UserOperationLogEntryInterface;
use Jabe\Impl\Interceptor\{
    CommandInterface,
    CommandContext
};
use Jabe\Impl\OpLog\{
    UserOperationLogContext,
    UserOperationLogContextEntry,
    UserOperationLogContextEntryBuilder
};
use Jabe\Impl\Persistence\Entity\{
    JobDefinitionEntity,
    PropertyChange
};
use Jabe\Impl\Util\EnsureUtil;

class SetJobDefinitionPriorityCmd implements CommandInterface
{
    public const JOB_DEFINITION_OVERRIDING_PRIORITY = "overridingPriority";

    protected $jobDefinitionId;
    protected $priority;
    protected bool $cascade = false;

    public function __construct(?string $jobDefinitionId, ?int $priority, bool $cascade)
    {
        $this->jobDefinitionId = $jobDefinitionId;
        $this->priority = $priority;
        $this->cascade = $cascade;
    }

    public function execute(CommandContext $commandContext, ...$args)
    {
        EnsureUtil::ensureNotNull(NotValidException::class, "jobDefinitionId", $this->jobDefinitionId);

        $jobDefinition = $commandContext->getJobDefinitionManager()->findById($this->jobDefinitionId);

        EnsureUtil::ensureNotNull(
            "Job definition with id '" . $this->jobDefinitionId . "' does not exist",
            "jobDefinition",
            $jobDefinition
        );

        $this->checkUpdateProcess($commandContext, $jobDefinition);

        $currentPriority = $jobDefinition->getOverridingJobPriority();
        $jobDefinition->setJobPriority($this->priority);

        $opLogContext = new UserOperationLogContext();
        $this->createJobDefinitionOperationLogEntry($opLogContext, $currentPriority, $jobDefinition);

        if ($this->cascade && $this->priority !== null) {
            $commandContext->getJobManager()->updateJobPriorityByDefinitionId($this->jobDefinitionId, $this->priority);
            $this->createCascadeJobsOperationLogEntry($opLogContext, $jobDefinition);
        }

        $commandContext->getOperationLogManager()->logUserOperations($opLogContext);

        return null;
    }

    protected function checkUpdateProcess(CommandContext $commandContext, JobDefinitionEntity $jobDefinition): void
    {
        $processDefinitionId = $jobDefinition->getProcessDefinitionId();

        foreach ($commandContext->getProcessEngineConfiguration()->getCommandCheckers() as $checker) {
            $checker->checkUpdateProcessDefinitionById($processDefinitionId);
            if ($this->cascade) {
                $checker->checkUpdateProcessInstanceByProcessDefinitionId($processDefinitionId);
            }
        }
    }

    protected function createJobDefinitionOperationLogEntry(
        UserOperationLogContext $opLogContext,
        int $previousPriority,
        JobDefinitionEntity $jobDefinition
    ): void {
        $propertyChange = new PropertyChange(
            self::JOB_DEFINITION_OVERRIDING_PRIORITY,
            $previousPriority,
            $jobDefinition->getOverridingJobPriority()
        );

        $entry = UserOperationLogContextEntryBuilder::entry(UserOperationLogEntryInterface::OPERATION_TYPE_SET_PRIORITY, EntityTypes::JOB_DEFINITION)
            ->inContextOf($jobDefinition)
            ->propertyChanges($propertyChange)
            ->category(UserOperationLogEntryInterface::CATEGORY_OPERATOR)
            ->create();

        $opLogContext->addEntry($entry);
    }

    protected function createCascadeJobsOperationLogEntry(UserOperationLogContext $opLogContext, JobDefinitionEntity $jobDefinition): void
    {
        // old value is unknown
        $propertyChange = new PropertyChange(
            SetJobPriorityCmd::JOB_PRIORITY_PROPERTY,
            null,
            $jobDefinition->getOverridingJobPriority()
        );

        $entry = UserOperationLogContextEntryBuilder::entry(UserOperationLogEntryInterface::OPERATION_TYPE_SET_PRIORITY, EntityTypes::JOB)
            ->inContextOf($jobDefinition)
            ->propertyChanges($propertyChange)
            ->category(UserOperationLogEntryInterface::CATEGORY_OPERATOR)
            ->create();

        $opLogContext->addEntry($entry);
    }

    public function isRetryable(): bool
    {
        return false;
    }
}
