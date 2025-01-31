<?php

namespace Jabe\Impl\Bpmn\Behavior;

use Jabe\Impl\Pvm\Delegate\ActivityExecutionInterface;

class IntermediateCatchEventActivityBehavior extends AbstractBpmnActivityBehavior
{
    protected $isAfterEventBasedGateway;

    public function __construct(bool $isAfterEventBasedGateway)
    {
        parent::__construct();
        $this->isAfterEventBasedGateway = $isAfterEventBasedGateway;
    }

    public function execute(/*ActivityExecutionInterface*/$execution): void
    {
        if ($this->isAfterEventBasedGateway) {
            $this->leave($execution);
        } else {
            // Do nothing: waitstate behavior
        }
    }

    public function isAfterEventBasedGateway(): bool
    {
        return $this->isAfterEventBasedGateway;
    }

    public function signal(/*ActivityExecutionInterface*/$execution, ?string $signalName = null, $signalData = null, array $processVariables = []): void
    {
        $this->leave($execution);
    }
}
