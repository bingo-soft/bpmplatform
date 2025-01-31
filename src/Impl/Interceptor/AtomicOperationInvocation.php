<?php

namespace Jabe\Impl\Interceptor;

use Jabe\Impl\ProcessEngineLogger;
use Jabe\Impl\Context\Context;
use Jabe\Impl\Persistence\Entity\ExecutionEntity;
use Jabe\Impl\Pvm\Runtime\{
    AtomicOperation,
    AtomicOperationInterface
};

class AtomicOperationInvocation
{
    //private final static ContextLogger LOG = ProcessEngineLogger.CONTEXT_LOGGER;

    public $operation;

    public $execution;

    protected $performAsync = false;

    // for logging
    protected $applicationContextName = null;
    protected $activityId = null;
    protected $activityName = null;

    public function __construct(AtomicOperationInterface $operation, ExecutionEntity $execution, bool $performAsync)
    {
        $this->init($operation, $execution, $performAsync);
    }

    protected function init(AtomicOperationInterface $operation, ExecutionEntity $execution, bool $performAsync): void
    {
        $this->operation = $operation;
        $this->execution = $execution;
        $this->performAsync = $performAsync;
    }

    public function execute(BpmnStackTrace $stackTrace, ProcessDataContext $processDataContext, ...$args): void
    {
        if (
            $this->operation != AtomicOperation::activityStartCancelScope()
            && $this->operation != AtomicOperation::activityStartInterruptScope()
            && $this->operation != AtomicOperation::activityStartConcurrent()
            && $this->operation != AtomicOperation::deleteCascade()
        ) {
            // execution might be replaced in the meantime:
            $replacedBy = $this->execution->getReplacedBy();
            if ($replacedBy !== null) {
                $this->execution = $replacedBy;
            }
        }

        //execution was canceled for example via terminate end event
        if (
            $this->execution->isCanceled()
            && (
                $this->operation == AtomicOperation::transitionNotifyListenerEnd()
                || $this->operation == AtomicOperation::activityNotifyListenerEnd()
            )
        ) {
            return;
        }

        // execution might have ended in the meanwhile
        if (
            $this->execution->isEnded()
            && (
                $this->operation == AtomicOperation::transitionNotifyListenerTake()
                || $this->operation == AtomicOperation::activityStartCreateScope()
            )
        ) {
            return;
        }

        $currentPa = Context::getCurrentProcessApplication();
        if ($currentPa !== null) {
            $this->applicationContextName = $currentPa->getName();
        }
        $this->activityId = $this->execution->getActivityId();
        $this->activityName = $this->execution->getCurrentActivityName();
        $stackTrace->add($this);

        $popProcessDataContextSection = $processDataContext->pushSection($this->execution);

        try {
            Context::setExecutionContext($this->execution);
            if (!$this->performAsync) {
                //LOG.debugExecutingAtomicOperation(operation, execution);
                $this->operation->execute($this->execution, ...$args);
            } else {
                $this->execution->scheduleAtomicOperationAsync($this);
            }
            if ($popProcessDataContextSection) {
                $processDataContext->popSection();
            }
        } finally {
            Context::removeExecutionContext();
        }
    }

    // getters / setters ////////////////////////////////////

    public function getOperation(): AtomicOperationInterface
    {
        return $this->operation;
    }

    public function getExecution(): ?ExecutionEntity
    {
        return $this->execution;
    }

    public function isPerformAsync(): bool
    {
        return $this->performAsync;
    }

    public function getApplicationContextName(): ?string
    {
        return $this->applicationContextName;
    }

    public function getActivityId(): ?string
    {
        return $this->activityId;
    }

    public function getActivityName(): ?string
    {
        return $this->activityName;
    }
}
