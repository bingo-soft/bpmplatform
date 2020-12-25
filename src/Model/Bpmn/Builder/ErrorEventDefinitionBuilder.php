<?php

namespace BpmPlatform\Model\Bpmn\Builder;

use BpmPlatform\Model\Bpmn\BpmnModelInstanceInterface;
use BpmPlatform\Model\Bpmn\Instance\ErrorEventDefinitionInterface;

class ErrorEventDefinitionBuilder extends AbstractErrorEventDefinitionBuilder
{
    public function __construct(
        BpmnModelInstanceInterface $modelInstance,
        ErrorEventDefinitionInterface $element
    ) {
        parent::__construct($modelInstance, $element, ErrorEventDefinitionBuilder::class);
    }
}
