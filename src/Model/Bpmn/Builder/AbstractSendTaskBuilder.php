<?php

namespace BpmPlatform\Model\Bpmn\Builder;

use BpmPlatform\Model\Bpmn\BpmnModelInstanceInterface;
use BpmPlatform\Model\Bpmn\Instance\{
    MessageInterface,
    OperationInterface,
    SendTaskInterface
};

abstract class AbstractSendTaskBuilder extends AbstractTaskBuilder
{
    protected function __construct(
        BpmnModelInstanceInterface $modelInstance,
        SendTaskInterface $element,
        string $selfType
    ) {
        parent::__construct($modelInstance, $element, $selfType);
    }

    public function implementation(string $implementation): AbstractSendTaskBuilder
    {
        $this->element->setImplementation($implementation);
        return $this;
    }

    /**
     * @param mixed $message
     */
    public function message($message): AbstractReceiveTaskBuilder
    {
        if (is_string($message)) {
            $message = $this->findMessageForName($message);
        }
        $this->element->setMessage($message);
        return $this;
    }

    public function operation(OperationInterface $operation): AbstractReceiveTaskBuilder
    {
        $this->element->setOperation($operation);
        return $this;
    }

    public function setClass(string $className): AbstractReceiveTaskBuilder
    {
        $this->element->setClass($className);
        return $this;
    }

    public function expression(string $expression): AbstractReceiveTaskBuilder
    {
        $this->element->setExpression($expression);
        return $this;
    }

    public function delegateExpression(string $expression): AbstractReceiveTaskBuilder
    {
        $this->element->setDelegateExpression($expression);
        return $this;
    }

    public function resultVariable(string $resultVariable): AbstractReceiveTaskBuilder
    {
        $this->element->setResultVariable($resultVariable);
        return $this;
    }

    public function topic(string $topic): AbstractReceiveTaskBuilder
    {
        $this->element->setTopic($topic);
        return $this;
    }

    public function type(string $type): AbstractReceiveTaskBuilder
    {
        $this->element->setType($type);
        return $this;
    }

    public function taskPriority(string $taskPriority): AbstractReceiveTaskBuilder
    {
        $this->element->setTaskPriority($taskPriority);
        return $this;
    }
}
