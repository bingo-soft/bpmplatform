<?php

namespace Jabe\Impl\Cmd\Batch;

use Jabe\BadUserRequestException;
use Jabe\Authorization\BatchPermissions;
use Jabe\Batch\BatchInterface;
use Jabe\History\{
    HistoricProcessInstanceQueryInterface,
    UserOperationLogEntryInterface
};
use Jabe\Impl\{
    HistoricProcessInstanceQueryImpl,
    ProcessInstanceQueryImpl
};
use Jabe\Impl\Batch\{
    BatchConfiguration,
    BatchElementConfiguration
};
use Jabe\Impl\Batch\Builder\{
    BatchBuilder,
    OperationLogInstanceCountHandlerInterface
};
use Jabe\Impl\Batch\Deletion\DeleteProcessInstanceBatchConfiguration;
use Jabe\Impl\Interceptor\{
    CommandInterface,
    CommandContext
};
use Jabe\Impl\Persistence\Entity\PropertyChange;
use Jabe\Impl\Util\{
    CollectionUtil,
    EnsureUtil
};
use Jabe\Runtime\ProcessInstanceQueryInterface;

class DeleteProcessInstanceBatchCmd implements CommandInterface
{
    protected $deleteReason;
    protected $processInstanceIds = [];
    protected $processInstanceQuery;
    protected $historicProcessInstanceQuery;
    protected bool $skipCustomListeners = false;
    protected bool $skipSubprocesses = false;

    public function __construct(
        array $processInstances,
        ProcessInstanceQueryInterface $processInstanceQuery,
        HistoricProcessInstanceQueryInterface $historicProcessInstanceQuery,
        ?string $deleteReason,
        bool $skipCustomListeners,
        bool $skipSubprocesses
    ) {
        $this->processInstanceIds = $processInstances;
        $this->processInstanceQuery = $processInstanceQuery;
        $this->historicProcessInstanceQuery = $historicProcessInstanceQuery;
        $this->deleteReason = $deleteReason;
        $this->skipCustomListeners = $skipCustomListeners;
        $this->skipSubprocesses = $skipSubprocesses;
    }

    public function execute(CommandContext $commandContext, ...$args)
    {
        $elementConfiguration = $this->collectProcessInstanceIds($commandContext);

        EnsureUtil::ensureNotEmpty(BadUserRequestException::class, "processInstanceIds", $elementConfiguration->getIds());

        $scope = $this;
        return (new BatchBuilder($commandContext))
            ->type(BatchInterface::TYPE_PROCESS_INSTANCE_DELETION)
            ->config($this->getConfiguration($elementConfiguration))
            ->permission(BatchPermissions::createBatchDeleteRunningProcessInstances())
            ->operationLogHandler(new class ($scope) implements OperationLogInstanceCountHandlerInterface {
                private $scope;

                public function __construct($scope)
                {
                    $this->scope = $scope;
                }

                public function write(CommandContext $commandContext, int $instanceCount): void
                {
                    $op = $this->scope->writeUserOperationLog;
                    $op($commandContext, $instanceCount);
                }
            })
            ->build();
    }

    protected function collectProcessInstanceIds(CommandContext $commandContext): BatchElementConfiguration
    {
        $elementConfiguration = new BatchElementConfiguration();

        $processInstanceIds = $this->getProcessInstanceIds();
        if (!CollectionUtil::isEmpty($processInstanceIds)) {
            $query = new ProcessInstanceQueryImpl();
            $query->processInstanceIds($processInstanceIds);
            $elementConfiguration->addDeploymentMappings(
                $commandContext->runWithoutAuthorization(function () use ($query) {
                    $query->listDeploymentIdMappings();
                }),
                $processInstanceIds
            );
        }

        $processInstanceQuery = $this->processInstanceQuery;
        if ($processInstanceQuery !== null) {
            $elementConfiguration->addDeploymentMappings($processInstanceQuery->listDeploymentIdMappings());
        }

        $historicProcessInstanceQuery = $this->historicProcessInstanceQuery;
        if ($historicProcessInstanceQuery !== null) {
            $elementConfiguration->addDeploymentMappings($historicProcessInstanceQuery->listDeploymentIdMappings());
        }

        return $elementConfiguration;
    }

    public function getProcessInstanceIds(): array
    {
        return $this->processInstanceIds;
    }

    public function writeUserOperationLog(CommandContext $commandContext, int $numInstances): void
    {
        $propertyChanges = [];
        $propertyChanges[] = new PropertyChange("nrOfInstances", null, $numInstances);
        $propertyChanges[] = new PropertyChange("async", null, true);
        $propertyChanges[] = new PropertyChange("deleteReason", null, $this->deleteReason);
        $commandContext->getOperationLogManager()
            ->logProcessInstanceOperation(
                UserOperationLogEntryInterface::OPERATION_TYPE_DELETE,
                null,
                null,
                null,
                $propertyChanges,
                null
            );
    }

    public function getConfiguration(BatchElementConfiguration $elementConfiguration): BatchConfiguration
    {
        return new DeleteProcessInstanceBatchConfiguration(
            $elementConfiguration->getIds(),
            $elementConfiguration->getMappings(),
            $this->deleteReason,
            $this->skipCustomListeners,
            $this->skipSubprocesses,
            false
        );
    }

    public function isRetryable(): bool
    {
        return false;
    }
}
