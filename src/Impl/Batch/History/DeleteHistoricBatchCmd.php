<?php

namespace Jabe\Impl\Batch\History;

use Jabe\History\UserOperationLogEntryInterface;
use Jabe\Impl\Interceptor\{
    CommandInterface,
    CommandContext
};
use Jabe\Impl\Persistence\Entity\PropertyChange;
use Jabe\Impl\Util\EnsureUtil;

class DeleteHistoricBatchCmd implements CommandInterface
{
    protected $batchId;

    public function __construct(?string $batchId)
    {
        $this->batchId = $batchId;
    }

    public function execute(CommandContext $commandContext, ...$args)
    {
        EnsureUtil::ensureNotNull("Historic batch id must not be null", "historic batch id", $this->batchId);

        $historicBatch = $commandContext->getHistoricBatchManager()->findHistoricBatchById($this->batchId);
        EnsureUtil::ensureNotNull("Historic batch for id '" . $this->batchId . "' cannot be found", "historic batch", $historicBatch);

        $this->checkAccess($commandContext, $historicBatch);

        $this->writeUserOperationLog($commandContext);

        $historicBatch->delete();

        return null;
    }

    protected function checkAccess(CommandContext $commandContext, HistoricBatchEntity $batch): void
    {
        foreach ($commandContext->getProcessEngineConfiguration()->getCommandCheckers() as $checker) {
            $checker->checkDeleteHistoricBatch($batch);
        }
    }

    protected function writeUserOperationLog(CommandContext $commandContext): void
    {
        $commandContext->getOperationLogManager()
            ->logBatchOperation(UserOperationLogEntryInterface::OPERATION_TYPE_DELETE_HISTORY, $this->batchId, PropertyChange::emptyChange());
    }

    public function isRetryable(): bool
    {
        return false;
    }
}
